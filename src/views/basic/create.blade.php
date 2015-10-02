@extends ('board::layouts.default')

@section ('content')
@if (isset($article))
	{!! Form::open([
		'route' => [$baseRouteName.'.update', $bo_id, $article->id],
		'class' => 'form-horizontal',
		'method' => 'put'
	]) !!}
@else
	{!! Form::open([
		'route' => [$baseRouteName.'.store', $bo_id],
		'class' => 'form-horizontal',
	]) !!}
@endif
<div class='form-group'>
	<label for='title' class='col-sm-2 control-label'>제목</label>
	<div class='col-sm-10'>
		{!! Form::text('title', isset($article) ? $article->title : old('title'), [
			'class' => 'form-control',
			'id' => 'title',
			'placeholder' => '제목',
		]) !!}
	</div>
</div>
<div class='form-group'>
	<label for='content' class='col-sm-2 control-label'>내용</label>
	<div class='col-sm-10'>
		{!! Form::textarea('content', isset($article) ? $article->content : old('content'), [
			'class' => 'form-control',
			'id' => 'content',
			'placeholder' => '내용',
		]) !!}
	</div>
</div>
<div class='form-group'>
	<div class='col-sm-12 text-right'>
		{!! Form::submit('작성완료', [
			'class' => 'btn btn-primary btn-sm',
		]) !!}
		{!! Html::link(route($baseRouteName.'.index', [$bo_id]), '목록', [
			'class' => 'btn btn-default btn-sm',
		]) !!}
	</div>
</div>
{!! Form::close() !!}
@stop