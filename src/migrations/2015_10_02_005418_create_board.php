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
    	// 게시판 설정값 담을 테이블
		Schema::create('board_configs', function(BluePrint $table) {
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->string('name');
			$table->string('table_name');
			$table->timestamps();
		});
		
		// 게시글 담을 테이블
		// 새로운 게시판을 생성할시 이 아래 정의한 테이블들만 새로 추가하면 됩니다.
        Schema::create('articles', function(BluePrint $table) {
        	$table->increments('id');
			
			$table->integer('user_id')->unsigned();
			$table->integer('cate_id')->unsigned();
			$table->string('title', '100');
			$table->text('content');
			$table->integer('hit')->unsigned();
			$table->timestamps();
			
			$table->softDeletes();
        });
		
		// 게시글에 연결된 파일 담을 테이블
		Schema::create('articles_files', function(BluePrint $table) {
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->integer('articles_id')->unsigned();
			$table->string('filename');
			$table->integer('rank')->unsigned();
			$table->timestamps();
			
			$table->foreign('articles_id')->references('id')->on('articles');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('articles_files', function($table) {
			$table->dropForeign('articles_files_articles_id_foreign');
		});
		
    	Schema::drop('board_configs');
		Schema::drop('articles');
		Schema::drop('articles_files');
    }
}
