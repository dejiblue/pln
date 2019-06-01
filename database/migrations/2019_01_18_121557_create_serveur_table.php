<?php

use App\Serveur;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServeurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serveurs', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('accesstoken');
            $table->string('username');
            $table->string('plnactive')->boolean();
            $table->string('sshactive')->boolean();
            $table->string('lstversion')->boolean();
            $table->integer('port')->unsigned();
            $table->integer('sshport')->unsigned();
            $table->integer('user_id')->unsigned()->index();
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
        Schema::dropIfExists('serveurs');
    }
}
