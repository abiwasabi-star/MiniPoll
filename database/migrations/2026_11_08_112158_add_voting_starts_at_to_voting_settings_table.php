<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('voting_settings', function (Blueprint $table) {
            $table->timestamp('voting_starts_at')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('voting_settings', function (Blueprint $table) {
            $table->dropColumn('voting_starts_at');
        });
    }
};
