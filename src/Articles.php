<?php

namespace Visualplus\Board;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articles extends Model
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
	
	/*
	 * 작성자 relation
	 * 
	 * @return App\User
	 */
	public function user() {
		return $this->belongsTo('App\User');
	}
	
	/*
	 * 연결 파일 relation
	 * 
	 * @return ArticleFiles 모델
	 */
	public function files() {
		return $this->hasMany('Visualplus\Board\ArticleFiles');
	}
	 
	
	/*
	 * 게시글의 소유자인지 확인
	 * 
	 * @param \App\User $user
	 * @return bool
	 */
	public function isOwner(\App\User $user) {
		return ($user != null && $this->user_id == $user->id);
	}
}