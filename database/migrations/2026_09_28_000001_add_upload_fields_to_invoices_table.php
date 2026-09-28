<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number', 50)->nullable();
            $table->string('description')->nullable();
            $table->string('file_path')->nullable(); // private storage path of an uploaded invoice file
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'description', 'file_path']);
        });
    }
};
