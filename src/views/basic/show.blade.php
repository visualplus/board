@extends ('board::layouts.default')

@section ('content')
<div class='row'>
	<div class='col-sm-12'>
		<div class='well well-sm'>
			{{ $article->title }}
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-4 col-sm-offset-8'>
		<ul class='breadcrumb'>
			<li>
				작성자 : {{ $article->user->name }}
			</li>
			<li>
				작성일 : {{ date('Y-m-d', strtotime($article->created_at)) }}
			</li>
		</ul>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div class='well well-sm'>
			{!! nl2br($article->content) !!}
		</div>
	</div>
</div>

<div class='btn-area text-right'>
	{!! Form::open([
		'route' => [$baseRouteName.'.destroy', $bo_id, $article->id],
		'method' => 'delete',
	]) !!}
		@if ($article->isOwner(Auth::user()))
			{!! Html::link(route($baseRouteName.'.edit', [$bo_id, $article->id]), '수정', [
				'role' => 'button',
				'class' => 'btn btn-primary btn-sm',
			]) !!}
			{!! Form::submit('삭제', [
				'class' => 'btn btn-danger btn-sm',
			]) !!}
		@endif
		{!! Html::link(route($baseRouteName.'.index', $bo_id), '목록', [
			'class' => 'btn btn-default btn-sm',
		]) !!}
	{!! Form::close() !!}
</div>
@stop
