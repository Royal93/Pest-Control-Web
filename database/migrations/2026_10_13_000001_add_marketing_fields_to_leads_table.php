<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Which marketing/sales rep captured this lead in the field. Null for
            // leads that came from the contact form or chatbot, not a rep visit.
            $table->foreignId('captured_by')->nullable()->after('id')
                ->constrained('users')->nullOnDelete();

            // new -> contacted -> quoted -> converted, or not_interested at any point.
            $table->string('status', 30)->default('new')->after('source');

            $table->string('address')->nullable()->after('status');
            $table->string('suburb')->nullable()->after('address');
            $table->string('province')->nullable()->after('suburb');

            // The pest problem itself, distinct from service_interest (which plan
            // they're interested in) already on this table.
            $table->string('pest_type')->nullable()->after('province');

            // How they heard about SP Pest Control: referral, saw the van, flyer, etc.
            $table->string('referral_channel')->nullable()->after('pest_type');

            // Free-text notes from the rep's conversation with the prospect.
            $table->text('notes')->nullable()->after('message');

            $table->date('next_follow_up_date')->nullable()->after('notes');

            // POPIA: the prospect agreed to be contacted about this enquiry.
            // Only meaningful for rep-captured leads; a contact-form or chatbot
            // lead gives consent by submitting the form themselves.
            $table->boolean('consent')->default(false)->after('next_follow_up_date');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('captured_by');
            $table->dropColumn([
                'status', 'address', 'suburb', 'province', 'pest_type',
                'referral_channel', 'notes', 'next_follow_up_date', 'consent',
            ]);
        });
    }
};
