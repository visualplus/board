@include ('board::layouts.inc.header')
@yield ('css')

<div class='container'>
	@yield ('content')
</div>

@include ('board::layouts.inc.footer')

@yield ('script')
