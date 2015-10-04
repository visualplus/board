<?php

namespace Visualplus\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleFiles extends Model
{
	use SoftDeletes;
	
	// 테이블명
	protected $table = "";
	
	/*
	 * 테이블 설정
	 * 
	 * @param string $table
	 */
	public function setTable($table) {
		$this->table = $table;
	}
}
