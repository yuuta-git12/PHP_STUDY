# PHP_STUDY

## 説明
- 独習PHP第４版の内容を実行するためのリポジトリ

## 学習用の開発環境
### バージョン情報
- Docker:24.0.5
  - イメージ：php:8.2-apache
  - イメージ：mariadb:11.3
  - イメージ：phpmyadmin:latest
- PHP：8.2
- DB：mariadb:11.3
- Docker Compose:2.20.2

### ディレクトリ構成
```
プロジェクトルート
├── .vscode
│    └── launch.json (vscode エディタのデバッガー設定)
├── www
│    └── html
│        └── index.php
├── docker-compose.yml
└── docker
    ├── app
    │    ├── apache2
    │    │    ├── sites-available
    │    │    │   └── 000-default.conf
    │    │    └ apache2.conf
    │    ├── php.ini
    │    └── Dockerfile
    └── mysql
         ├── initdb (sqlの初期化用)
         ├── storage (データのマウント用)
         ├── Dockerfile
         └── server.cnf
```






- 以下の環境をDockerで構築
  - サーバー：Apache 最新バージョン
  - プログラミング言語：PHP　バージョン8以降
    - インストールする拡張モジュール
      - OPcache(https://www.php.net/manual/ja/intro.opcache.php)
      - intl(https://www.php.net/manual/ja/book.intl.php)
      - GD(https://www.php.net/manual/ja/book.image.php)
      - Exif(https://www.php.net/manual/ja/book.exif.php)
      - Imagick(https://www.php.net/manual/ja/book.imagick.php)
      - mysqli(https://www.php.net/manual/ja/book.mysqli.php)
      - pdo_mysql(https://www.php.net/manual/ja/book.pdo.php)
      - redis()
      - xdebug(https://xdebug.org)
  - データーベース：MariaDB
  - 
## 拡張機能
- Emmmet:入力の効率化用プラグイン html:5でhtmlのページの外枠が一気に入力できる

## 参考
- 開発環境構築の参考サイト
  - [Docker で Apache + PHP コンテナを作る方法](https://lazesoftware.com/ja/blog/230220/)
  - [DockerでApache+PHP+MySQLの環境を構築してみる](https://qiita.com/me-654393/items/1ed212cb33eafe734835)
  - https://zenn.dev/ikuraikura/articles/d166724f2c123d1db312
  - https://zenn.dev/dragonarrow/articles/b3fbdf1718a812
- Apacheの参考サイト
  - [a2enmodとa2dismodコマンドの動作について](https://web.just4fun.biz/?Apache/a2enmodとa2dismodコマンドの動作について)
  - [mod_rewriteとは](https://qiita.com/miyuki_samitani/items/b22e1738b2737c655785)
  - [Apache deflate,exporesについて](https://serverlog.jp/apache2-2/)

https://zenn.dev/dragonarrow/articles/b3fbdf1718a812#手順2%3A-ビルド