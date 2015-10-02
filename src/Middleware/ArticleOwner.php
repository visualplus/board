<?php

namespace Visualplus\Board\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class ArticleOwner
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * 각 모델들의 소유자인지 확인하는 미들웨어.
     * 리뷰 삭제, 업데이트 등의 행동을 할 때 자신의 소유인것만 허용하도록..
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $articleModel)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }
        
        // article id 값을 가져온다.
        $id = $request->route()->parameter($articleType);
		
		$model = new $articleModel;
		if (!$model->isOwner($this->auth->user())) {
			return back();
		}
		
		return $next($request);
    }
}
