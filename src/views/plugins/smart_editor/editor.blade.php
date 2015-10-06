{!! Form::textarea($name, $value, [
	'id' => $name,
	'style' => 'display: none;',
]) !!}

@section ('script')
{!! Html::script('visualplus/assets/smart_editor/js/HuskyEZCreator.js') !!}
@parent
<script>
var oEditors = [];

nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "{{ $name }}",
	sSkinURI: "/visualplus/plugins/smart-editor-skin",
	sCSSBaseURI: "/visualplus/assets/smart_editor/css",
	htParams : {
		bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
		//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
		fOnBeforeUnload : function(){
			//alert("완료!");
		}
	}, //boolean
	fOnAppLoad : function(){
		//예제 코드
		//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
	},
	fCreator: "createSEditor2"
});

// 폼 submit 하기 전 에디터의 내용을 textarea에 넣음. 
$('#{{ $name }}').closest('form').on('submit', function() {
	oEditors.getById["{{ $name }}"].exec("UPDATE_CONTENTS_FIELD", []); // 에디터의 내용이 textarea에 적용됩니다.
});
</script>
@append