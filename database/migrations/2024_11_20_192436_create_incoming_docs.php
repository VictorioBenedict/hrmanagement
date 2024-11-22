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
        Schema::create('incoming_docs', function (Blueprint $table) {
            $table->id();
            $table->string('ctrl_no')->nullable();
            $table->string('title_description')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('empno')->nullable();
            $table->string('department')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('date')->nullable();
            $table->longtext('actions_id')->nullable();
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('statuses_id')->nullable();
            $table->string('released_by')->nullable();
            $table->timestamp('released_timestamp')->nullable();
            $table->string('received_by')->nullable();
            $table->timestamp('received_timestamp')->nullable();
            $table->longText('reject_reason')->nullable();
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
        Schema::dropIfExists('incoming_docs');
    }
};
