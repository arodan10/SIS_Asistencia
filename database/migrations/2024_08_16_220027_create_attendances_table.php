<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->integer('study');
            $table->date('date');
            $table->unsignedBigInteger('member_id');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('attendances');
    }
};
