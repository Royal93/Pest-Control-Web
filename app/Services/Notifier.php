<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\Lead;
use App\Models\LeadClaim;
use App\Models\Sale;
use App\Models\User;
use App\Notifications\PortalAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Every in-app notification the system sends, in one place, so it's clear who
 * hears about what. A failed notification is logged and never stops the action
 * that triggered it (a claim, a payment, a contact form, etc).
 */
class Notifier
{
    // ---- Admin side -------------------------------------------------------

    public static function leadClaimed(LeadClaim $claim): void
    {
        $claim->loadMissing(['lead', 'rep']);

        self::toAdmins(new PortalAlert(
            'claim',
            'New claim on '.$claim->lead->name,
            $claim->rep->fullName().' wants to work this lead.'.($claim->note ? ' Note: '.$claim->note : ''),
            route('admin.leads.show', $claim->lead, false),
        ));
    }

    public static function claimWithdrawn(LeadClaim $claim): void
    {
        $claim->loadMissing(['lead', 'rep']);

        self::toAdmins(new PortalAlert(
            'claim_withdrawn',
            'Claim withdrawn on '.$claim->lead->name,
            $claim->rep->fullName().' withdrew their claim.',
            route('admin.leads.show', $claim->lead, false),
        ));
    }

    public static function newWebsiteLead(Lead $lead): void
    {
        self::toAdmins(new PortalAlert(
            'new_lead',
            'New website enquiry: '.$lead->name,
            trim(($lead->service_interest ? $lead->service_interest.'. ' : '').'Not assigned to a sales rep yet.'),
            route('admin.leads.show', $lead, false),
        ));
    }

    // ---- Sales rep side ---------------------------------------------------

    public static function claimApproved(LeadClaim $claim): void
    {
        $claim->loadMissing(['lead', 'rep']);

        self::to($claim->rep, new PortalAlert(
            'claim_approved',
            'Claim approved: '.$claim->lead->name,
            'The lead is now yours and is in My Leads, with their contact details.',
            route('marketing.leads.edit', $claim->lead, false),
        ));
    }

    /** $givenToOther: the lead went to another rep (true), or admin only turned this claim down (false). */
    public static function claimDeclined(LeadClaim $claim, bool $givenToOther = true): void
    {
        $claim->loadMissing(['lead', 'rep']);

        self::to($claim->rep, new PortalAlert(
            'claim_declined',
            'Claim not approved: '.$claim->lead->name,
            $givenToOther ? 'This lead was given to another sales rep.' : 'Admin did not approve your claim on this lead.',
            route('marketing.open.index', ['tab' => 'claims'], false),
        ));
    }

    public static function leadAssigned(Lead $lead, User $rep): void
    {
        self::to($rep, new PortalAlert(
            'lead_assigned',
            'New lead assigned: '.$lead->name,
            'Admin assigned this lead to you.'.($lead->pest_type ? ' Pest: '.$lead->pest_type.'.' : ''),
            route('marketing.leads.edit', $lead, false),
        ));
    }

    // ---- Both sides -------------------------------------------------------

    public static function agreementAccepted(Sale $sale): void
    {
        $title = 'Agreement accepted: '.$sale->customer_name;
        $body = $sale->reference().' ('.$sale->serviceLabel().') was accepted online. Waiting for the first payment.';

        self::toAdmins(new PortalAlert('sale_accepted', $title, $body, route('admin.sales.show', $sale, false)));

        if ($sale->salesRep) {
            self::to($sale->salesRep, new PortalAlert('sale_accepted', $title, $body, route('marketing.sales.show', $sale, false)));
        }
    }

    public static function salePaid(Sale $sale): void
    {
        $title = 'Payment received: '.$sale->customer_name;
        $body = $sale->reference().' is paid and the customer\'s plan is active.';

        self::toAdmins(new PortalAlert('sale_paid', $title, $body, route('admin.sales.show', $sale, false)));

        if ($sale->salesRep) {
            self::to($sale->salesRep, new PortalAlert('sale_paid', $title, $body.' Sale converted.', route('marketing.sales.show', $sale, false)));
        }
    }

    // ---- Plumbing ---------------------------------------------------------

    private static function toAdmins(PortalAlert $alert): void
    {
        try {
            Notification::send(AdminUser::all(), $alert);
        } catch (\Throwable $e) {
            Log::error('Admin notification failed', ['kind' => $alert->kind, 'error' => $e->getMessage()]);
        }
    }

    private static function to(?User $user, PortalAlert $alert): void
    {
        if (! $user) {
            return;
        }

        try {
            $user->notify($alert);
        } catch (\Throwable $e) {
            Log::error('Notification failed', ['kind' => $alert->kind, 'user_id' => $user->id, 'error' => $e->getMessage()]);
        }
    }
}
