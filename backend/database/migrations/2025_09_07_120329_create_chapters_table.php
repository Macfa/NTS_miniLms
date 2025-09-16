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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->dateTime("start");
            $table->dateTime("end");
            $table->string('week_days')->comment("수업 요일 (예: '0(Mon),2(Wed)')");
            $table->boolean("status")->default(1)->comment("챕터 활성화 여부 (1: 활성, 0: 비활성)");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
