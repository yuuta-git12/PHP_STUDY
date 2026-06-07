# 学習記録のメモ

## 文字コード
- BOM(Byte Order Mark)：Unicode形式テキストの先頭に付与される数バイトのデータ
- BOM利用することで、そのテキストがUnicodeで記述されていること、その種類を識別できる。
- PHPではBOM付きのファイルを正しく処理できない場合がある。拡張子がphpのファイルを保存する場合には、必ずBOMなしのUTF-8として保存する。


## httpd.conf/.htacsess
- Apacheの標準的な設定ファイル

## PHPUnit テストの実行方法

テストはDockerコンテナ内で実行する。

### ディレクトリ構成
```
www/
├── html/src/Algorithm/  ← アルゴリズムの実装ファイル
├── tests/Algorithm/     ← テストファイル（*Test.php）
└── phpunit.xml          ← PHPUnit設定ファイル
```

### コマンド

```bash
# テストを全件実行
docker compose exec app ./vendor/bin/phpunit

# 特定のテストファイルのみ実行
docker compose exec app ./vendor/bin/phpunit tests/Algorithm/Sort/BubbleSortTest.php

# 特定のディレクトリ以下を実行
docker compose exec app ./vendor/bin/phpunit tests/Algorithm/Sort/
```

### 新しいアルゴリズムを追加するときの手順
1. `www/html/src/Algorithm/{カテゴリ}/` にクラスファイルを作成
   - 名前空間: `wings\selfphp\Algorithm\{カテゴリ}`
2. `www/tests/Algorithm/{カテゴリ}/` にテストファイルを作成
   - ファイル名は `{クラス名}Test.php`

---

## Guzzle（HTTPクライアントライブラリ）

### 概要
GuzzleはPHPのHTTPクライアントライブラリ。外部APIやWebリソースへのHTTPリクエストを簡潔に記述できる。

### 基本的な使い方

```php
require '../../vendor/autoload.php';

$cli = new GuzzleHttp\Client([
    'base_uri' => 'https://example.com/',
]);

// GETリクエスト
$res = $cli->request('get', 'path/to/resource');

// レスポンスボディの取得
print($res->getBody());
```

- `base_uri` でベースURLを指定し、リクエスト時に相対パスを渡す
- `$res->getBody()` でレスポンスボディを取得

### Docker環境でのSSL設定

Guzzle経由でHTTPSリクエストを送る際、SSL証明書の検証に `ca-certificates` が必要。  
Dockerfile に以下を追加することで解決：

```dockerfile
RUN apt-get install -y ca-certificates
```

また、php.ini の `openssl.cafile` にXAMPP向けのパスが設定されているとDocker環境では機能しないため削除する。

### 参考ファイル
- `www/html/src/guzzle_study.php`

---

## データベースへの接続
1. ```mariadb -u root -p```を入力
2. パスワードを入力

- データベースの利用は以下のユーザーで実施
  - study_user
  - study_pass
