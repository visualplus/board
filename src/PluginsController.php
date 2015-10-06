<?php
namespace Visualplus\Board;

use Illuminate\Http\Request;

use Validator;
use Storage;
use Response;

class PluginsController extends \App\Http\Controllers\Controller {
	protected $uploadPath = "../storage/app/editor/";
	
	public function getSmartEditorSkin() {
		return view('board::plugins.smart_editor.skin');
	}
	
	public function getInputArea($mode = '') {
		if ($mode == 'ie8') {
			return view('board::plugins.smart_editor.inputarea_ie8');
		} else {
			return view('board::plugins.smart_editor.inputarea');
		}
	}
	
	public function getPhotoUploader() {
		return view('board::plugins.smart_editor.photo_uploader');
	}
	
	public function postFileUpload(Request $request) {
		$url = $request->get('callback').'?callback_func='.$request->get('callback_func');
		
		if ($request->hasFile('Filedata')) {
			$validator = Validator::make($request->all(), [
				'Filedata' => 'image',
			]);
			
			// 이미지 이외의 파일 업로드함.
			if ($validator->fails()) {
				$url .= '&errstr=이미지파일 아님';
			}
			
			$file = $request->file('Filedata');
			$filename = time();
			
			while (Storage::exists('editor/'.$filename)) {
				$filename ++;
			}
			
			$file->move($this->uploadPath, $filename);
			
			$url .= '&bNewLine=true';
			$url .= '&sFilename='.$filename;
			$url .= '&sFileURL=/visualplus/plugins/image/'.$filename;
		} else {
			$url .= '&errstr=파일 업로드 안함';
		}
		
		return redirect($url);
	}

	public function getCallback() {
		return view('board::plugins.smart_editor.callback');
	}
	
	public function getImage($filename) {
		$filename = 'editor/'.$filename;
		
		if (Storage::exists($filename)) {
			$file = Storage::get($filename);
			$type = Storage::mimeType($filename);
			
			return Response::make($file, 200)->header("Content-Type", $type);						
		}
	}
	
	public function missingMethod($parameters = []) {
	}
}