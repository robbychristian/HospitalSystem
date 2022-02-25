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
            $table->string('id_fb')->nullable();
            $table->string('name')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('about')->nullable();
            $table->string('clinicAddress')->nullable();
            $table->string('joinDate')->nullable();
            $table->boolean('isVerified')->default(false);
            $table->boolean('gender')->nullable();
            $table->string('specialization')->nullable();
            $table->string('degree')->nullable();
            $table->integer('consultFee')->nullable();
            $table->integer('teleconsultFee')->default(0);
            $table->string('photoUrl')->nullable();
            $table->integer('totalPrescribe')->default(0);
            $table->integer('totalEarnings')->default(0);
            $table->boolean('provideTeleService')->default(true);
            $table->boolean('isAdmin')->default(false);
            $table->string('password');
            $table->rememberToken();
            // $table->timestamps();
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
