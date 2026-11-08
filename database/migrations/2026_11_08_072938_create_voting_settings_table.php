<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('voting_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('voting_ends_at')->nullable();
            $table->timestamps();
        });

        //creates the single settings record.
        DB::table('voting_settings')->insert([
            'id' => 1,
            'voting_ends_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('voting_settings');
    }
};
