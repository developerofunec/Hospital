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
    Schema::create('medical_records', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('doctor_id');
        $table->unsignedBigInteger('patient_id');
        $table->text('diagnose');
        $table->date('diagnose_date')->nullable();
        $table->text('prescription')->nullable();
        $table->string('documents')->nullable();

    
        $table->string('plan_title')->nullable();
        $table->text('description')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->text('procedures')->nullable();
        $table->text('follow_up')->nullable();
        $table->enum('status', ['pending', 'in_progress', 'done'])->default('pending');

        $table->timestamps();

        $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_records_models');
    }
};
