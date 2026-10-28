<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Where the sale came from and who made it.
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sales_rep_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('captured_by_admin')->nullable()->constrained('admin_users')->nullOnDelete();

            // The customer login created (or matched) once the first payment clears.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Questionnaire: customer details.
            $table->string('customer_name');
            $table->string('contact_person');
            $table->string('phone', 30);
            $table->string('email');
            $table->string('service_address');
            $table->string('suburb', 120);
            $table->string('property_type', 30);   // house | flat | townhouse | business

            // Questionnaire: service.
            $table->string('service', 20);         // cockroach | rodent | both
            $table->boolean('children_or_pets')->default(false);
            $table->unsignedSmallInteger('bait_stations')->nullable();   // rodent / both
            $table->text('treatment_areas')->nullable();                 // cockroach / both

            // Questionnaire: dates and money.
            $table->date('agreement_date');
            $table->date('inception_date');
            $table->decimal('activation_fee', 10, 2)->default(0);
            $table->decimal('monthly_subscription', 10, 2);
            $table->string('payment_frequency', 20); // monthly | quarterly | biannual | annual

            // Lifecycle: sent -> accepted -> paid, or cancelled.
            $table->string('status', 20)->default('sent');
            $table->string('token', 64)->unique(); // the customer's private agreement link
            $table->string('agreement_path')->nullable();
            $table->timestamp('sent_at')->nullable();

            // Electronic acceptance record.
            $table->timestamp('accepted_at')->nullable();
            $table->string('accepted_name')->nullable();
            $table->string('accepted_ip', 45)->nullable();
            $table->string('accepted_user_agent', 500)->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
