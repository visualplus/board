<?php
namespace Visualplus\Board;

use Illuminate\Http\Request;

use Route;
use Auth;
use View;

class AdminController extends \App\Http\Controllers\Controller {
	// 기본 라우트 이름
	protected $baseRouteName = '';
	
	// 관리자 스킨
	protected $skin = 'board::admin';
	
	// 게시판 설정 테이블 모델
	protected $model = '';
	
	// 한 화면에 표시할 리스트 개수
	protected $itemsPerPage = 10;
	
	public function __construct() {
		// 기본 라우트 이름을 저장한다.
		$routeArr = explode('.', Route::currentRouteName());
		array_pop($routeArr);
		
		$this->baseRouteName = implode('.', $routeArr);
		
		View::share('baseRouteName', $this->baseRouteName);
	}
	
	/*
	 * 게시판 리스트
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
    	$model = new $this->model;
		
		$list = $model->orderBy('created_at', 'desc')->paginate($this->itemsPerPage);
		
    	return view($this->skin.'.index')->with(compact('list'));
    }
    
	/*
	 * 게시판 생성 뷰
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function create()
    {
    	return view($this->skin.'.create');
    }

	/*
	 * 게시판 생성
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
    public function store(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'table_name' => 'required',
    		'skin' => 'required',
    	]);
		
		$model = new $this->model;
		
		$model->name 		= $request->get('name');
		$model->table_name 	= $request->get('table_name');
		$model->skin		= $request->get('skin');
		$model->save();
		
		return redirect()->route($this->baseRouteName.'.index');
	}

	/*
	 * 게시판 보기
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function show($id)
    {
    	$model = new $this->model;
		
		$board = $model->findOrFail($id);
		
		return view($this->skin.'.show')->with(compact('board'));
    }

	/*
	 * 게시판 수정 뷰
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function edit($id)
    {
    }

	/*
	 * 게시판 수정
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function update(Request $request, $id)
    {
    }

	/*
	 * 게시판 삭제
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function destroy($id)
    {
    	$model = new $this->model;
		
		$board = $model->findOrFail($id);
		$board->delete();
		
		return redirect()->route($this->baseRouteName.'.index');
    }
}
