<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('posts', function($table) {
            //varchar
            //should add an index, will make searching the database so much faster but its also space intensive so becareful
            $table->string('slug')->unique()->after('body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //from "doctrine/dbal"
        Scheme::table('posts', function($table){
            $table->dropColumn('slug');
        });
    }
}
