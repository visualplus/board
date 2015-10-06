@extends ('board::layouts.default')

@section ('content')
<div class='basic-table show'>
	<h1 class='title'>
		{{ $board_setting->name }}
	</h1>
	
	<table>
	<colgroup>
		<col width='120' />
		<col width='120' />
		<col width='120' />
		<col width='120' />
		<col width='120' />
		<col width='120' />
	</colgroup>
	<thead>
		<tr>
			<th>제목</th>
			<td colspan='5'>{{ $article->title }}</td>
		</tr>
		<tr>
			<th>작성자</th>
			<td>
				@if (isset($article->user))
					{{ $article->user->name }}
				@else
					-
				@endif
			</td>
			<th>작성일</th>
			<td>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
			<th>조회수</th>
			<td>{{ number_format($article->hit) }}</td>
		</tr>
		<tr>
			<th>첨부파일</th>
			<td colspan='5'>
				@foreach ($article->files as $file)
					<!-- 파일 다운로드 경로 등을 넣으세요.. -->
					<a href="#">
						{{ $file->filename }}
					</a>
				@endforeach
			</td>
		</tr>
	</thead>
	</table>
	
	<div class='content'>
		{!! nl2br($article->content) !!}
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
</div>
@stop

@section ('css')
<style>
	@include ('board::basic.css.style')
</style>
@stop
