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
            $table->string('personal_id')->nullable()->unique();
            $table->string('prefix')->nullable();
            $table->string('ranks')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('department')->nullable();
            $table->string('branch')->nullable();
            $table->string('section')->nullable();
            $table->string('division')->nullable();
            $table->string('service_type')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('service_type_code')->nullable();
            $table->date('security_class_start_date')->nullable();
            $table->date('service_start_date')->nullable();
            $table->string('solider_type')->nullable();
            $table->integer('age')->nullable();
            $table->string('classification')->nullable();
            $table->string('classification_name')->nullable();
            $table->string('population_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
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
