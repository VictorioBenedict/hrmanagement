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

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('empno')->nullable();
            $table->string('department')->nullable();
            $table->timestamp('date')->nullable();
            $table->longText('purposes')->nullable();
            $table->longText('requestedDocs');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('statuses_id')->nullable();
            $table->string('released_by')->nullable();
            $table->timestamp('released_timestamp')->nullable();
            $table->string('received_by')->nullable();
            $table->timestamp('received_timestamp')->nullable();
            $table->longText('reject_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('document_fields', function (Blueprint $table) {
            $table->id();
            $table->string('document_fieldname')->nullable();
            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->tinyInteger('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->nullable();
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
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_fields');
        Schema::dropIfExists('document_types');
    }
};
