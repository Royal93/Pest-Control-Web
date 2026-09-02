<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // What this payment is for. Kept generic (morphs) so it can pay
            // for a one-off inspection/quote, or a plan subscription, without
            // needing a separate payments table for each.
            $table->nullableMorphs('payable');

            // The reference WE generate and send as p2 — must be unique per
            // attempt, this is how we find the row again when Netcash posts back.
            $table->string('reference')->unique();

            $table->string('description');
            $table->decimal('amount', 10, 2);

            // pending -> accepted / declined. Set from the Notify URL, which
            // is the source of truth; Accept/Decline just update the same row
            // for a nicer customer-facing status if they arrive first.
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');

            $table->string('method')->nullable();      // card / eft / retail / etc, from Netcash's Method code
            $table->string('request_trace')->nullable(); // Netcash's RequestTrace id, useful for support queries
            $table->string('decline_reason')->nullable();

            // Only populated for tokenised card payments (subscriptions)
            $table->string('cc_token')->nullable();
            $table->string('cc_holder')->nullable();
            $table->string('cc_masked')->nullable();
            $table->string('cc_expiry')->nullable();

            // Subscription fields — only used when this payment kicks off a
            // recurring plan (RatGuard / RoachGuard / AntArmor)
            $table->boolean('is_subscription')->default(false);
            $table->unsignedTinyInteger('subscription_frequency')->nullable(); // Netcash's m18 code
            $table->date('subscription_start_date')->nullable();
            $table->decimal('subscription_amount', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
