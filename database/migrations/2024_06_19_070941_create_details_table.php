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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->string('ranks')->nullable();
            $table->string('department')->nullable();
            $table->string('branch')->nullable();
            $table->string('section')->nullable();
            $table->string('division')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('security_class_start_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('classification')->nullable();
            $table->string('classification_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('profession')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->date('release_date')->nullable();

            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
