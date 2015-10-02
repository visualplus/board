<?php
namespace Visualplus\Djboard;

use Illuminate\Http\Request;

use Route;

trait BoardSystem {
	private $_model;
	
	public function __construct() {
		if (isset($this->model) && $this->model != '') 
			$this->_model = new $this->model;
	}
	
	private function parseRouteName() {
		$stack = explode('.', Route::getCurrentRoute()->getName());
		array_pop($stack);
		return implode('.', $stack);
	}
	
	/*
	 * 게시판 리스트
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
		$list = $this->_model->orderBy('created_at', 'desc')->paginate(10);
		
		$routeName = $this->parseRouteName();
		
		return view($this->skin.'.index')->with(compact('list', 'routeName'));
    }
    
	/*
	 * 게시글 생성 뷰
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function create()
    {
    	$routeName = $this->parseRouteName();
		
    	return view($this->skin.'.create')->with(compact('routeName'));
    }

	/*
	 * 게시글 생성
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
    public function store(Request $request)
    {
    	$this->validate($request, [
    		'title' => 'required',
    		'content' => 'required',
    	]);
		
		$routeName = $this->parseRouteName();
		
		$article = new $this->model;
		
		$article->title = $request->get('title');
		$article->content = $request->get('content');
		$article->save();
		
		return redirect()->route($routeName.'.show', [$article->id]);
	}

	/*
	 * 게시글 보기
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function show($id)
    {
    	$article = $this->_model->findOrFail($id);
		$routeName = $this->parseRouteName();
		
		return view($this->skin.'.show')->with(compact('article', 'routeName'));
    }

	/*
	 * 게시글 수정 뷰
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function edit($id)
    {
    	$article = $this->_model->findOrFail($id);
		$routeName = $this->parseRouteName();
		
		return view($this->skin.'.create')->with(compact('article', 'routeName'));
    }

	/*
	 * 게시글 수정
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function update(Request $request, $id)
    {
    	$this->validate($request, [
    		'title' => 'required',
    		'content' => 'required',
    	]);
		
    	$article = $this->_model->findOrFail($id);
		$routeName = $this->parseRouteName();
		
		return redirect()->route($routeName.'.show', [$id]);
    }

	/*
	 * 게시글 삭제
	 * 
	 * @param  int  $id
     * @return \Illuminate\Http\Response
	 */
    public function destroy($id)
    {
    	
    }
}
