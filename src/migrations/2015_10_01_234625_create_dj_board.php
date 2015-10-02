<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDjBoard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dj_boards', function(BluePrint $table) {
        	$table->engine = 'InnoDB';
			
        	$table->increments('id');
			$table->integer('user_id');
			$table->string('title', '100');
			$table->text('content');
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('user_id')->references('id')->on('users');
        });
		
		Schema::create('dj_board_files', function(BluePrint $table) {
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->integer('bo_id');
			$table->string('filename');
			$table->timestamps();
			
			$table->foreign('bo_id')->references('id')->on('dj_boards');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dj_boards');
		Schema::drop('dj_board_files');
    }
}
