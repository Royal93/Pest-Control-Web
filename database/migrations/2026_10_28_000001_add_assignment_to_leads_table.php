<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // The sales rep this lead is assigned to. Only admin assigns leads now;
            // a rep sees exactly the leads assigned to them on SP SALES.
            $table->foreignId('assigned_to')->nullable()->after('captured_by')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable()->after('assigned_to');

            // The admin who captured the lead on the admin portal (admins live in
            // admin_users, not users, so this is separate from captured_by).
            $table->foreignId('captured_by_admin')->nullable()->after('assigned_at')
                ->constrained('admin_users')->nullOnDelete();

            // Leads captured by phone often have no email yet.
            $table->string('email')->nullable()->change();
        });

        // Leads reps captured themselves before this change stay with them:
        // they become assigned to the same rep, so nobody loses their work.
        DB::table('leads')
            ->whereNotNull('captured_by')
            ->whereNull('assigned_to')
            ->update([
                'assigned_to' => DB::raw('captured_by'),
                'assigned_at' => DB::raw('created_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('captured_by_admin');
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropColumn('assigned_at');
        });
    }
};
