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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('empno')->nullable();
            $table->string('department')->nullable();
            $table->timestamp('date_filed')->nullable();
            $table->timestamp('date_leave')->nullable();
            $table->longText('requestedLeaves');
            $table->unsignedBigInteger('statuses_id')->nullable();
            $table->longText('illness')->nullable();
            $table->longText('place')->nullable();
            $table->string('signature')->nullable();
            $table->string('status')->nullable();
            $table->string('released_by')->nullable();
            $table->timestamp('released_timestamp')->nullable();
            $table->string('received_by')->nullable();
            $table->timestamp('received_timestamp')->nullable();
            $table->longText('reject_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('leave_fields', function (Blueprint $table) {
            $table->id();
            $table->string('leave_fieldname')->nullable();
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->tinyInteger('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('leave_type_id');
            $table->unsignedInteger('leave_days')->nullable();
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
        Schema::dropIfExists('leaves');

        Schema::dropIfExists('leave_fields');

        Schema::dropIfExists('leave_types');
    }
};
