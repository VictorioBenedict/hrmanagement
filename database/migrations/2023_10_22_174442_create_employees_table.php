<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('name')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('employee_id', 12)->unique();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('salary_structure_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('email', 30);
            $table->boolean('isArchived')->default(false); //
            $table->string('phone', 15)->nullable();
            $table->string('location')->nullable();
            $table->string('employee_image')->nullable();
            $table->string('joining_mode', 30)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('role')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
