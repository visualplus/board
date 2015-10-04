@extends ('board::layouts.default')

@section ('content')
{!! Form::open([
	'route' => $baseRouteName.'.store',
	'class' => 'form-horizontal',
]) !!}
<div class='form-group'>
	<label for='name' class='col-sm-2 control-label'>게시판 이름</label>
	<div class='col-sm-10'>
		{!! Form::text('name', old('name'), [
			'class' => 'form-control',
			'id' => 'name',
			'placeholder' => '게시판 이름',
		]) !!}
	</div>
</div>
<div class='form-group'>
	<label for='table_name' class='col-sm-2 control-label'>DB 테이블</label>
	<div class='col-sm-10'>
		{!! Form::text('table_name', old('table_name'), [
			'class' => 'form-control',
			'id' => 'table_name',
			'placeholder' => 'DB 테이블',
		]) !!}
	</div>
</div>
<div class='form-group'>
	<div class='col-sm-12 text-right'>
		{!! Form::submit('작성완료', [
			'class' => 'btn btn-primary btn-sm',
		]) !!}
		{!! Html::link(route($baseRouteName.'.index'), '목록', [
			'class' => 'btn btn-default btn-sm',
		]) !!}
	</div>
</div>
{!! Form::close() !!}
@stop