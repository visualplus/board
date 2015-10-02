@extends ('board::layouts.default')

@section ('content')
<table class='table table-striped'>
<colgroup>
	<col width='50' />
	<col width='' />
	<col width='120' />
	<col width='200' />
	<col width='100' />
	<col width='80' />
</colgroup>
<thead>
	<tr>
		<th class='text-center'>#</th>
		<th class='text-center'>게시판 이름</th>
		<th class='text-center'>DB 테이블</th>
		<th class='text-center'>카테고리</th>
		<th class='text-center'>생성 일시</th>
		<th class='text-center'>명령</th>
	</tr>
</thead>
<tbody>
	@foreach ($list as $index => $board)
		<tr>
			<td class='text-center'>
				{{ number_format($list->total() - $list->perPage() * ($list->currentPage() - 1) - $index) }}
			</td>
			<td class='text-center'>
				{{ $board->name }}
			</td>
			<td class='text-center'>
				{{ $board->table_name }}
			</td>
			<td class='text-center'>
				{{ $board->category }}
			</td>
			<td class='text-center'>
				{{ date('Y-m-d', strtotime($board->created_at)) }}
			</td>
			<td class='text-center'>
				{!! Form::open([
					'method' => 'delete',
					'route' => [$baseRouteName.'.destroy', $board->id]
				]) !!}
					{!! Form::submit('삭제', [
						'class' => 'btn btn-danger btn-xs',
					]) !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach
</tbody>
</table>

<div class='navigation'>
	{!! $list->render() !!}
</div>

<div class='btn-area text-right'>
	{!! Html::link(route($baseRouteName.'.create'), '게시판 생성', [
		'role' => 'button',
		'class' => 'btn btn-primary btn-sm',
	]) !!}
</div>
@stop

@section ('script')

@stop
