<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserGender;
use Illuminate\Support\Carbon;

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
            $table->string('phone');
            $table->tinyInteger('gender')->unsigned()->default(UserGender::Male);
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->date('birthday')->default(Carbon::now());
            $table->string('password');
            $table->string('order_id')->nullable();
            $table->integer('is_admin')->nullable();
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
