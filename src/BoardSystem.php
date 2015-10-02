<?php
namespace Visualplus\Board;

use Illuminate\Http\Request;

use Route;
use Auth;

trait BoardSystem {
	/*
	 * 원본 라우팅 이름을 가져온다.
	 * 
	 * @return string
	 */
	private function parseRouteName() {
		$stack = explode('.', Route::getCurrentRoute()->getName());
		array_pop($stack);
		
		return implode('.', $stack);
	}
	
	/*
	 * 게시글 작성자인지 확인함 해당 모델은 isOwner(App\User $user) 함수를 가지고 있어야함.
	 * 
	 * @param model $article
	 * @return bool
	 */
	private function checkOwner($article) {
		return $article->isOwner(Auth::user());
	}
	
	/*
	 * 게시판 리스트
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
    	$model = new $this->model;
		
		$list = $model->orderBy('created_at', 'desc')->paginate(10);
		
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
		
		$article->user_id = Auth::user()->id;
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
    	$model = new $this->model;
		
    	$article = $model->findOrFail($id);
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
    	$model = new $this->model;
		$routeName = $this->parseRouteName();
		
    	$article = $model->findOrFail($id);
		
		// 해당 게시글의 소유자가 아님
		if (!$this->checkOwner($article)) {
			return redirect()->route($routeName.'.show', [$id]);
		}
		
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
		
		$model = new $this->model;
		$routeName = $this->parseRouteName();
		
    	$article = $model->findOrFail($id);
		
		// 해당 게시글의 소유자일 경우에만 업데이트
		if ($this->checkOwner($article)) {
			$article->title = $request->get('title');
			$article->content = $request->get('content');
			
			$article->save();
		}
		
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
    	$model = new $this->model;
		
		$routeName = $this->parseRouteName();
    	$article = $model->findOrFail($id);
		
		// 해당 게시글의 소유자일 경우에만 삭제
		if ($this->checkOwner($article)) {
			$article->delete();
		}
		
		return redirect()->route($routeName.'.index');
    }
}
