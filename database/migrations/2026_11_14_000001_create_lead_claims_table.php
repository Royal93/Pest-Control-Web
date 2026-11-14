<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A sales rep asking to work an unassigned lead. Any rep can claim; admin
     * approves one claim, which assigns the lead to that rep and declines the rest.
     */
    public function up(): void
    {
        Schema::create('lead_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('note')->nullable();
            // pending, approved, declined, withdrawn
            $table->string('status', 20)->default('pending');
            $table->timestamp('decided_at')->nullable();
            $table->foreignId('decided_by_admin')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamps();

            // One claim row per rep per lead; claiming again reuses it.
            $table->unique(['lead_id', 'user_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_claims');
    }
};
