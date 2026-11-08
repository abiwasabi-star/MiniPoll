<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
//Schema::table modifies an existing table
//Schema::create creates a new table
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->after('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->unique(['user_id', 'category_id']);
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['skater_id']);

            $table->foreign('skater_id')
                ->references('id')
                ->on('skaters')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'category_id']);

            $table->dropForeign(['skater_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['user_id']);

            $table->dropColumn(['user_id', 'category_id']);

            $table->foreign('skater_id')
                ->references('id')
                ->on('skaters')
                ->cascadeOnDelete();
        });
    }
};