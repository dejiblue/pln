<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSite extends Migration
{
    
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            
            $table->increments('id');
            
            $table->string('url');
            $table->string('ip');
            $table->string('cpusername');
            $table->string('wpusername')->nullable();
            $table->string('wppassword')->nullable();
            
            $table->integer('serveur_id')->unsigned()->index();
            $table->integer('categorie_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('sites');
    }

}
