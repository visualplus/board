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