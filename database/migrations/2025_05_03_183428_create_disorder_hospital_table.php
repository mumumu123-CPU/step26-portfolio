<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disorder_hospital', function (Blueprint $table) {
            $table->id();

            $table->foreignId('disorder_id')
                  ->constrained()
                  // 親が消されたら、子も道連れにする
                  ->onDelete('cascade');

            $table->foreignId('hospital_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disorder_hospital');
    }
};
