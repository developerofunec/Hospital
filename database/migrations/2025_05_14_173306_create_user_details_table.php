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
    Schema::create('user_details', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->date('date_of_birth');
        $table->string('fin_code')->unique();
        $table->string('email')->unique();
        $table->string('phone_number', 20);
        $table->text('address');
        $table->enum('gender', ['male', 'female', 'other']);
        $table->string('password');
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
        Schema::dropIfExists('user_details');
    }


public function image(){
    Schema::create('userdetails', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->unique();
    $table->string('profile_image')->nullable(); // şəkil yolu
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});

}
};