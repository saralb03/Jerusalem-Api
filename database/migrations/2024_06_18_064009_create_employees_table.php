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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('personal_id');
            $table->string('personal_number');
            $table->string('ranks');
            $table->string('surname');
            $table->string('first_name');
            $table->string('department');
            $table->string('division');
            $table->string('service_type');
            $table->date('date_of_birth');
            $table->string('service_type_code');
            $table->date('security_class_start_date');
            $table->date('service_start_date');
            $table->string('solider_type');
            $table->integer('age');
            $table->string('classification');
            $table->string('phone_number');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
