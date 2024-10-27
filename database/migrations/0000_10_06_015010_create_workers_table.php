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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->enum('document_type', ['DNI'])->default('DNI');
            $table->string('document_number', 8)->unique();
            $table->string('name', 100);
            $table->string('paternal_last_name', 100);
            $table->string('maternal_last_name', 100);
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->string('phone_number', 9);
            $table->string('email', 120)->nullable();
            $table->boolean('status')->default(1);
            
            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->unsignedBigInteger('office_id')->nullable();
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
