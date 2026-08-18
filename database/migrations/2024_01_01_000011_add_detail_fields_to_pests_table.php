<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pests', function (Blueprint $table) {
            $table->text('infestation_signs')->nullable()->after('description');
            $table->text('health_risks')->nullable()->after('infestation_signs');
            $table->text('business_impact')->nullable()->after('health_risks');
        });
    }

    public function down(): void
    {
        Schema::table('pests', function (Blueprint $table) {
            $table->dropColumn(['infestation_signs', 'health_risks', 'business_impact']);
        });
    }
};
