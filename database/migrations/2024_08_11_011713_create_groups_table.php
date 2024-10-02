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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mday')->nullable();
            $table->string('mtime')->nullable();
            $table->string('mplace')->nullable();
            $table->string('motto')->nullable();
            $table->string('text')->nullable();
            $table->string('song')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('church_id');
            $table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
