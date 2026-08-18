<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // ratguard | roachguard | antarmor
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('billing_cycle')->default('monthly');
            $table->text('description')->nullable();
            $table->json('meta')->nullable(); // visits/yr, excess call-out rate, terms, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
