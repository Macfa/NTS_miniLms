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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('name');
            $table->text('description')->nullable()->comment('프로그램 상세 설명');
            $table->unsignedBigInteger('manager_id');
            $table->integer('total_week')->comment("총 주차");
            $table->integer('limit_count')->default(30)->comment("수강인원 제한");
            $table->integer('total_price')->nullable()->comment("총 가격");
            $table->tinyInteger('status')->default(1)->comment("프로그램 상태 (1: 활성화, 0: 비활성화)");
            $table->tinyInteger('approval_status')->default(0)->comment('관리자 승인 여부 (1: 승인, 0: 승인대기, -1: 승인거부)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
