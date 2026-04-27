<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultants', function (Blueprint $table) {
            if (!Schema::hasColumn('consultants', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('meeting_link');
            }
            if (!Schema::hasColumn('consultants', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('consultants', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_account_name');
            }
            if (!Schema::hasColumn('consultants', 'bank_iban')) {
                $table->string('bank_iban')->nullable()->after('bank_account_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account_name', 'bank_account_number', 'bank_iban']);
        });
    }
};



