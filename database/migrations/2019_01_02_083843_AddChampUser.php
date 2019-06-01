<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChampUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('forname')->nullable();
            $table->string('confirmation_token')->nullable();
            $table->string('wppassword')->nullable();
            $table->string('wpusername')->nullable();
            $table->string('cppassword')->nullable();
            $table->string('cpemail')->nullable();
            $table->string('internetbskey')->nullable();
            $table->string('internetbspass')->nullable();
            $table->boolean('internetbs')->nullable() ;
            $table->boolean('note')->nullable() ;
            $table->boolean('script')->nullable() ;
            $table->string('scriptfile')->nullable() ;
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
