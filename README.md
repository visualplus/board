# board

라라벨으로 게시판을 편하게 만들어주는 패키지입니다.

## 설치방법
```
composer require visualplus/board
```

## 설정방법
```
config/app.php에 있는 providers에 Visualplus/Board/ServiceProvider::class 추가
```
기본 스킨을 연결해주고 기본 db 테이블을 만들어주는 migration 파일을 복사하기 위한 단계입니다.
( 기본 스킨 및 migration 파일이 필요없다면 안하셔도 됩니다. )

ServiceProvider를 추가 후 

```
php artisan vendor:publish
```
명령어를 치면 database/migrations 안에 create_board.php 파일이 생깁니다.
이 파일은 게시판 설정 테이블, 게시글 테이블, 게시글 연결 파일 테이블을 생성을 도와줍니다.

create_board.php를 보면 게시판을 동작시키기 위한 기본 테이블의 구조가 정의되어있습니다.

모든 게시판은 게시글 저장할 테이블 1개, 파일을 저장할 테이블 1개가 꼭 필요하며 관리자 페이지에서 
테이블명을 지정해주셔야 합니다. ( 파일 저장할 테이블은 게시글 테이블명 + '_files'로 통일합니다. )


## 관리자 페이지 설정

컨트롤러를 하나 생성 후 Visualplus\Board\AdminController를 상속합니다.

```
php artisan make:controller BoardAdminController

class BoardAdminController extends \Visualplus\Board\AdminController
{
	...
}
```

## 게시판 페이지 설정
컨트롤러를 하나 생성 후 Visualplus\Board\BoardController를 상속합니다.

```
php artisan make:controller FreeBoardController

class FreeBoardController extends \Visualplus\Board\BoardController
{
	...
}
```

## 옵션 변경
BoardController는 몇가지 옵션을 제공합니다.

### 스킨.

```
protected $skin = 'board::basic';
```

초기 게시판 스킨은 기본 스킨이며 별로 이쁘지 않습니다.
그러므로 각자 스타일에 맞게 커스터마이징을 하셔야 합니다.
게시판의 스킨은 

1. index.blade.php -> 게시글 리스트
2. create.blade.php -> 게시글 생성, 게시글 수정
3. show.blade.php -> 게시글 보기

위 3가지 파일이 필요하며. 스킨을 만드신 후 접근가능한 view 위치를 지정해주시면 됩니다.
예를들어 'board::'는 패키지 내의 'views' 디렉토리와 매핑되어있으며, views안에 basic이라는 디렉토리가 있습니다.
그러므로 'board::basic'은 'vendor/visualplus/board/src/views/basic'을 참조하며 이 디렉토리 안에 위에 나열한 3가지 파일이 
모두 있어야 합니다.

### 한 화면에 표시할 리스트 개수.
```
protected $itemsPerPage = 10;
```

기본 10개의 게시글을 한 페이지에 뿌려줍니다.
위 값을 알맞게 변경하여 사용하세요.


### 파일 업로드 경로
```
protected $uploadPath = '../storage/app/board/';
```

게시글 작성시 첨부한 파일은 기본으로 storage/app/board 디렉토리에 저장됩니다.
파일명은 현재 timestamp + 올린 파일 확장자 입니다.

위 값을 변경하여 첨부파일이 저장될 위치를 수정하세요.