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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('document');
            $table->string('address')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('email')->nullable();
            $table->date('birthdate')->nullable();
            $table->date('baptism')->nullable();
            $table->enum('position',['MIEMBRO','MAESTRO(A)','ASOCIADO(A)'])->default('MIEMBRO');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
