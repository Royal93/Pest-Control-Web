<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->date('visit_date')->nullable();
            $table->text('technician_notes')->nullable();
            $table->string('status')->default('pending'); // pending | completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_visits');
    }
};
