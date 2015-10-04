@extends ('board::layouts.default')

@section ('content')
<table class='table table-striped'>
<colgroup>
	<col width='50' />
	<col width='' />
	<col width='120' />
	<col width='120' />
	<col width='80' />
</colgroup>
<thead>
	<tr>
		<th class='text-center'>#</th>
		<th class='text-center'>제목</th>
		<th class='text-center'>작성자</th>
		<th class='text-center'>작성일</th>
		<th class='text-center'>조회수</th>
	</tr>
</thead>
<tbody>
	@foreach ($list as $index => $article)
		<tr>
			<td class='text-center'>{{ number_format($list->total() - $list->perPage() * ($list->currentPage() - 1) - $index) }}</td>
			<td>{!! Html::link(route($baseRouteName.'.show', [$bo_id, $article->id]), $article->title) !!}</td>
			<td class='text-center'>{{ $article->user->name }}</td>
			<td class='text-center'>{{ date('Y-m-d', strtotime($article->created_at)) }}</td>
			<td class='text-center'>{{ number_format($article->hit) }}</td>
		</tr>
	@endforeach
</tbody>
</table>

<div class='navigation'>
	{!! $list->render() !!}
</div>

<div class='btn-area text-right'>
	{!! Html::link(route($baseRouteName.'.create', [$bo_id]), '글쓰기', [
		'role' => 'button',
		'class' => 'btn btn-sm btn-default',
	]) !!}
</div>
@stop
