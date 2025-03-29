<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile_no')->nullable();
            $table->string('exam')->nullable();
            $table->string('status')->nullable();
            $table->string('userId')->unique()->nullable();  // Added userId
            $table->string('ghcard')->nullable();  // Added ghcard
            $table->enum('gender', ['male', 'female', 'other'])->nullable();  // Added gender with an enum type
            $table->string('network_type')->nullable();  // Added network_type
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
