<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\Lead;
use App\Models\LeadClaim;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Claiming unassigned leads. Any sales rep can claim a lead on Open Leads;
 * only admin decides. Approving one claim assigns the lead to that rep and
 * declines every other pending claim on it.
 */
class LeadClaimService
{
    /** A rep claims an open lead. Claiming again after withdrawing reuses the same row. */
    public function claim(Lead $lead, User $rep, ?string $note): LeadClaim
    {
        $claim = DB::transaction(function () use ($lead, $rep, $note) {
            $lead = Lead::whereKey($lead->id)->lockForUpdate()->firstOrFail();

            abort_unless($lead->isOpenForClaims(), 422, 'This lead is no longer open to claim.');

            $claim = LeadClaim::firstOrNew(['lead_id' => $lead->id, 'user_id' => $rep->id]);

            abort_if($claim->exists && $claim->status === 'pending', 422, 'You have already claimed this lead.');

            $claim->fill([
                'note' => $note ?: null,
                'status' => 'pending',
                'decided_at' => null,
                'decided_by_admin' => null,
            ]);
            // A fresh claim goes to the back of the queue, by claim time.
            $claim->created_at = now();
            $claim->save();

            return $claim;
        });

        Notifier::leadClaimed($claim);

        return $claim;
    }

    public function withdraw(LeadClaim $claim): void
    {
        abort_unless($claim->isPending(), 422, 'Only a claim that is still waiting can be withdrawn.');

        $claim->update(['status' => 'withdrawn', 'decided_at' => now()]);

        Notifier::claimWithdrawn($claim);
    }

    /** Admin approves one claim: the lead goes to that rep, every other pending claim is declined. */
    public function approve(LeadClaim $claim, ?AdminUser $admin): void
    {
        $declined = DB::transaction(function () use ($claim, $admin) {
            $lead = Lead::whereKey($claim->lead_id)->lockForUpdate()->firstOrFail();
            $claim->refresh();

            abort_unless($claim->isPending(), 422, 'This claim has already been dealt with.');
            abort_unless($lead->assigned_to === null, 422, 'This lead is already assigned. Unassign it first if you want to give it to this rep.');

            $lead->update(['assigned_to' => $claim->user_id, 'assigned_at' => now()]);

            $claim->update(['status' => 'approved', 'decided_at' => now(), 'decided_by_admin' => $admin?->id]);

            return $this->declineOthers($lead, $claim->user_id, $admin);
        });

        Notifier::claimApproved($claim);
        $declined->each(fn (LeadClaim $other) => Notifier::claimDeclined($other));
    }

    /** Admin turns down one claim and leaves the lead open for the others. */
    public function decline(LeadClaim $claim, ?AdminUser $admin): void
    {
        abort_unless($claim->isPending(), 422, 'This claim has already been dealt with.');

        $claim->update(['status' => 'declined', 'decided_at' => now(), 'decided_by_admin' => $admin?->id]);

        Notifier::claimDeclined($claim, false);
    }

    /**
     * Admin assigned the lead directly (Assign / Reassign). If that rep had claimed
     * it, their claim counts as approved; everyone else's pending claim is declined.
     * Returns true when the assigned rep's own claim was approved this way.
     */
    public function settleOnDirectAssign(Lead $lead, int $repId, ?AdminUser $admin): bool
    {
        [$approved, $declined] = DB::transaction(function () use ($lead, $repId, $admin) {
            $approved = LeadClaim::pending()->where('lead_id', $lead->id)->where('user_id', $repId)->first();
            $approved?->update(['status' => 'approved', 'decided_at' => now(), 'decided_by_admin' => $admin?->id]);

            return [$approved, $this->declineOthers($lead, $repId, $admin)];
        });

        $declined->each(fn (LeadClaim $other) => Notifier::claimDeclined($other));

        return (bool) $approved;
    }

    private function declineOthers(Lead $lead, int $exceptRepId, ?AdminUser $admin)
    {
        $others = LeadClaim::pending()
            ->where('lead_id', $lead->id)
            ->where('user_id', '!=', $exceptRepId)
            ->get();

        foreach ($others as $other) {
            $other->update(['status' => 'declined', 'decided_at' => now(), 'decided_by_admin' => $admin?->id]);
        }

        return $others;
    }
}
