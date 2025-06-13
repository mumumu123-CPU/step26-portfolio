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
        // Schema(設計士)　Blueprint（大工）設計士が作成。何を'hospitals'を。大工を呼び出して$tableで呼び出す。ちなみに$tableは$unkoでもOK
        Schema::create('hospitals', function (Blueprint $table) { //Blueprint（大工） $tableはBlueprint（大工）のインスタンス
            $table->id();//主キー   
            $table->string('name');//病院名
            $table->string('address');//住所
            $table->string('type')->default('clinic');//tyoe(病院の種類)
            $table->string('homepage_url')->nullable();//HP
            $table->string('map_url')->nullable();//地図情報

            $table->string('prefecture')->nullable();//都道府県
            $table->string('station')->nullable();//最寄えき
            $table->string('day_of_week')->nullable();//診療曜日
            $table->string('am_open')->nullable();//午前診療
            $table->string('pm_open')->nullable();//午後診療

            $table->string('feature')->nullable();//特徴
            $table->string('treatment')->nullable();//治療法

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
