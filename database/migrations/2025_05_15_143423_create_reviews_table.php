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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade'); // 病院に紐づく（1対多）
            $table->text('comment'); // コメント本文
            $table->tinyInteger('rating'); // 評価：1〜5の整数
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
