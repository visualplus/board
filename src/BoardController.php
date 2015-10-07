<?php
namespace Visualplus\Board;

use Illuminate\Http\Request;

use Route;
use Auth;
use View;
use Cookie;
use Storage;

class BoardController extends \App\Http\Controllers\Controller {
	// 게시판 설정 테이블 모델
	protected $config_model = '';
	
	// 게시글 테이블 모델
	protected $articles_model = 'Visualplus\Board\Articles';
	
	// 게시글 파일 테이블 모델
	protected $article_files_model = 'Visualplus\Board\ArticleFiles';
	
	// 한 화면에 표시할 리스트 개수
	protected $itemsPerPage = 10;
	
	// 파일 업로드 경로
	protected $uploadPath = '../storage/app/board/';
	
	// 기본 라우트 이름
	private $baseRouteName = "";
	
	// 게시판 설정
	private $board_setting;
	
	public function __construct() {
		// 기본 라우트 이름을 저장한다.
		$routeArr = explode('.', Route::currentRouteName());
		array_pop($routeArr);
		
		$this->baseRouteName = implode('.', $routeArr);
		
		// 게시판 설정 로드
		if ($this->config_model == '') abort('500');
		$config_model = new $this->config_model;
		
		if (Route::current() != null) {
			$bo_id = Route::current()->parameters()['bo_id'];
			$this->board_setting = $config_model->findOrFail($bo_id);
			
			// 첨부파일 업로드 경로 변경
			$this->uploadPath .= $bo_id.'/';
			
			View::share('baseRouteName', $this->baseRouteName);
			View::share('bo_id', $bo_id);
			View::share('board_setting', $this->board_setting);
		}
	}

	/* 
	 * 파일명을 일정 규칙에 따라 리턴함
	 * 
	 * @param UploadedFile
	 * @return string
	 */ 
	protected function getFilename($file) {
		$filename = time();
		
		while (Storage::exists(str_replace('../storage/app/', '', $this->uploadPath.$filename.'.'.$file->getClientOriginalExtension()))) {
			$filename ++;
		}
		
		return $filename.'.'.$file->getClientOriginalExtension();
	}
	
	/*
	 * 게시글 리스트
	 * 
	 * @param integer $bo_id
	 * @return \Illuminate\Http\Response
	 */
    public function index($bo_id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);
		
		$list = $articles_model->orderBy('created_at', 'desc')->paginate($this->itemsPerPage);
    	
    	return view($this->board_setting->skin.'.index')->with(compact('list'));
    }
    
	/*
	 * 게시글 생성 뷰
	 * 
	 * @param integer $bo_id
	 * @return \Illuminate\Http\Response
	 */
    public function create($bo_id)
    {
    	return view($this->board_setting->skin.'.create');
    }

	/*
	 * 게시글 생성
	 * 
	 * @param integer $bo_id
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
    public function store(Request $request, $bo_id)
    {
    	$this->validate($request, [
    		'title' => 'required',
    		'content' => 'required',
    	]);
		
		$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);
		
		$articles_model->title = $request->get('title');
		$articles_model->content = $request->get('content');
		if (Auth::check()) {
			$articles_model->user_id = Auth::user()->id;
		} else {
			$articles_model->user_id = 0;
		}
		$articles_model->save();
		
		foreach ($request->file('uploads') as $index => $upload) {
			if ($upload == null) continue;
			
			$filename = $this->getFilename($upload);
			$upload->move($this->uploadPath, $filename);
			
			$article_file = new $this->article_files_model;
			$article_file->setTable($this->board_setting->table_name.'_files');
			$article_file->rank = $index;
			$article_file->articles_id = $articles_model->id;
			$article_file->filename = $filename;
			$article_file->save();
		}
		
		return redirect()->route($this->baseRouteName.'.show', [$bo_id, $articles_model->id]);
	}

	/*
	 * 게시글 보기
	 * 
	 * @param integer $bo_id
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function show(Request $request, $bo_id, $id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);
		
		$article = $articles_model->findOrFail($id);
		$article->setTable($this->board_setting->table_name);
		
		if ($request->cookie($bo_id.$id) != '1') {
			$article->hit ++;
			$article->save();
		}
		
		Cookie::queue(Cookie::make($bo_id.$id, '1'));
		
		return view($this->board_setting->skin.'.show')->with(compact('article'));
    }

	/*
	 * 게시글 수정 뷰
	 * 
	 * @param integer $bo_id
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function edit($bo_id, $id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);
		
		$article = $articles_model->findOrFail($id);
		if (!$article->isOwner(Auth::user())) {
			return redirect()->route($this->baseRouteName.'.index', $bo_id);
		}
		
		return view($this->board_setting->skin.'.create')->with(compact('article'));
    }

	/*
	 * 게시글 수정
	 * 
	 * @param  \Illuminate\Http\Request  $request
	 * @param integer $bo_id
     * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function update(Request $request, $bo_id, $id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);
		
		$article = $articles_model->findOrFail($id);
		$article->setTable($this->board_setting->table_name);
		
		if (!$article->isOwner(Auth::user())) {
			return redirect()->route($this->baseRouteName.'.index', $bo_id);
		}
		
		$this->validate($request, [
			'title' => 'required',
			'content' => 'required',
		]);
		
		$article->title = $request->get('title');
		$article->content = $request->get('content');
		$article->save();
		
		// 첨부파일 업로드
		foreach ($request->file('uploads') as $index => $upload) {
			if ($upload == null) continue;
			
			// 기존 파일 삭제
			if (($file = $article->files->where('rank', $index)->first())) {
				Storage::delete(str_replace('../storage/app/', '', $this->uploadPath.$file->filename));
				$file->setTable($this->board_setting->table_name.'_files');
				$file->delete();
			}
			
			$filename = $this->getFilename($upload);
			$upload->move($this->uploadPath, $filename);
			
			$article_file = new $this->article_files_model;
			$article_file->setTable($this->board_setting->table_name.'_files');
			$article_file->rank = $index;
			$article_file->articles_id = $article->id;
			$article_file->filename = $filename;
			$article_file->save();
		}
		
		return redirect()->route($this->baseRouteName.'.show', [$bo_id, $article->id]);
    }

	/*
	 * 게시글 삭제
	 * 
	 * @param integer $bo_id
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function destroy($bo_id, $id)
    {
    	$articles_model = new $this->articles_model;
		$articles_model->setTable($this->board_setting->table_name);
		
		$article = $articles_model->findOrFail($id);
		$article->setTable($this->board_setting->table_name);
		
		if (!$article->isOwner(Auth::user())) {
			return redirect()->route($this->baseRouteName.'.index', $bo_id);
		}
		
		$article->delete();
		
		return redirect()->route($this->baseRouteName.'.show', $bo_id);
    }
}
