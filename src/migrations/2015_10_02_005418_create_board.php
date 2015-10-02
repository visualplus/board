<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('boards', function(BluePrint $table) {
        	$table->engine = 'InnoDB';
			
        	$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('title', '100');
			$table->text('content');
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('user_id')->references('id')->on('users');
        });
		
		Schema::create('board_files', function(BluePrint $table) {
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->integer('bo_id')->unsigned();
			$table->string('filename');
			$table->timestamps();
			
			$table->foreign('bo_id')->references('id')->on('boards');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('boards');
		Schema::drop('board_files');
    }
}
