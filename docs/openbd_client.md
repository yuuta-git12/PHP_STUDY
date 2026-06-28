# OpenBDClient — 設計・実装ドキュメント

## 目次

1. [概要](#概要)
2. [ファイル構成](#ファイル構成)
3. [OpenBDClient クラス](#opendbclient-クラス)
   - [クラス設計の方針](#クラス設計の方針)
   - [定数・プロパティ](#定数プロパティ)
   - [コンストラクタ](#コンストラクタ)
   - [メソッド詳細](#メソッド詳細)
   - [戻り値の構造](#戻り値の構造)
   - [例外の仕様](#例外の仕様)
4. [OpenBD API について](#openbd-api-について)
   - [エンドポイント](#エンドポイント)
   - [レスポンス構造](#レスポンス構造)
5. [テストコード](#テストコード)
   - [テスト戦略](#テスト戦略)
   - [ヘルパーメソッド](#ヘルパーメソッド)
   - [テストケース一覧](#テストケース一覧)
   - [各テストの詳細](#各テストの詳細)
6. [テスト実行方法](#テスト実行方法)

---

## 概要

[OpenBD](https://openbd.jp/) は書誌情報・書影を無料で提供する日本の書籍データAPIです。

`OpenBDClient` は ISBN を入力として受け取り、OpenBD API を呼び出して書籍情報を返すクラスです。
HTTP 通信には Composer でインストール済みの [Guzzle](https://docs.guzzlephp.org/) を使用します。

---

## ファイル構成

```
PHP_STUDY/
├── www/
│   ├── html/
│   │   ├── isbn_search.php                 # 入力フォーム＆結果表示画面
│   │   └── src/
│   │       └── myclass/
│   │           └── OpenBDClient.php        # 書籍情報取得クラス（本体）
│   └── tests/
│       └── OpenBDClientTest.php            # PHPUnit テストコード
└── docs/
    └── openbd_client.md                    # このドキュメント
```

---

## OpenBDClient クラス

**ファイル**: `www/html/src/myclass/OpenBDClient.php`  
**名前空間**: `wings\selfphp\myclass`

### クラス設計の方針

| 方針 | 内容 |
|------|------|
| 依存性注入 (DI) | `GuzzleHttp\ClientInterface` をコンストラクタで受け取ることで、テスト時にモックへ差し替え可能にしている |
| 単一責任 | 「ISBNを受け取り書籍情報を返す」という1つの責務に限定している |
| 例外による制御 | 不正入力・通信エラーを例外で呼び出し元に通知し、通常の戻り値は `array|null` のみ |

### 定数・プロパティ

```php
private const API_BASE_URL = 'https://api.openbd.jp/v1/';

private ClientInterface $httpClient;
```

| 名前 | 種別 | 説明 |
|------|------|------|
| `API_BASE_URL` | `private const` | OpenBD API のベース URL。変更が必要な場合はここだけ修正する |
| `$httpClient` | `private` プロパティ | Guzzle クライアントの実体。テスト時はモックを差し込む |

### コンストラクタ

```php
public function __construct(?ClientInterface $httpClient = null)
```

- 引数を省略した場合、タイムアウト 10 秒のデフォルト Guzzle クライアントを生成する
- `ClientInterface` を渡した場合はそのインスタンスをそのまま使用する（テスト用途）

### メソッド詳細

#### `findByIsbn(string $isbn): ?array` — public

ISBN で書籍情報を取得するメインメソッド。

**処理の流れ**

```
入力 ISBN
  │
  ├─ ハイフン・空白を除去（preg_replace）
  │
  ├─ isValidIsbn() でフォーマット検証
  │     └─ 不正 → InvalidArgumentException をスロー
  │
  ├─ Guzzle で GET https://api.openbd.jp/v1/get?isbn={isbn}
  │     └─ 通信エラー → RuntimeException をスロー
  │
  ├─ レスポンス JSON をデコード
  │     ├─ [null] または [] → null を返す
  │     └─ 正常データ → parseBookData() でパース
  │
  └─ 書籍情報の連想配列を返す
```

#### `isValidIsbn(string $isbn): bool` — private

正規表現 `/^\d{10}(\d{3})?$/` で ISBN-10（10桁）または ISBN-13（13桁）であるかを検証する。
ハイフン除去後の文字列を対象とするため、`978-4-297-13824-0` のような入力も正しく検証できる。

#### `parseBookData(array $raw): array` — private

OpenBD APIのレスポンス1件分（`$data[0]`）を受け取り、必要な情報を抽出して返す。

- **`summary`** キー：タイトル・ISBN・出版社・発行日・書影URLなどの簡略情報
- **`onix`** キー：ONIX 形式の詳細情報（著者・説明文など）

ONIX の `TextContent` の中から `TextType === '03'`（内容紹介）を取り出して説明文とする。

### 戻り値の構造

`findByIsbn()` が返す配列のキーと内容：

| キー | 型 | 内容 | 取得元 |
|------|----|------|--------|
| `isbn` | `string` | ISBN-13 | `summary.isbn` |
| `title` | `string` | タイトル | `summary.title` |
| `volume` | `string` | 巻号 | `summary.volume` |
| `series` | `string` | シリーズ名 | `summary.series` |
| `publisher` | `string` | 出版社名 | `summary.publisher` |
| `pubdate` | `string` | 発行日（`YYYYMMDD` 形式） | `summary.pubdate` |
| `cover` | `string` | 書影画像URL | `summary.cover` |
| `authors` | `array` | 著者情報の配列（下記参照） | `onix.DescriptiveDetail.Contributor` |
| `description` | `string` | 内容紹介文 | `onix.CollateralDetail.TextContent[TextType=03]` |

`authors` の各要素：

```php
[
    'name' => '山田 太郎',  // 著者名
    'role' => 'A01',        // ONIX 著者ロールコード（A01=著者, A12=翻訳者 など）
]
```

### 例外の仕様

| 例外クラス | スローされる状況 | 呼び出し元での対応例 |
|------------|-----------------|----------------------|
| `\InvalidArgumentException` | ISBN が 10桁・13桁の数字でない | ユーザーへ入力エラーを表示 |
| `\RuntimeException` | Guzzle の通信エラー（接続失敗・タイムアウト等） | サーバーエラーとして表示 |

---

## OpenBD API について

### エンドポイント

```
GET https://api.openbd.jp/v1/get?isbn={ISBN}
```

- 複数 ISBN をカンマ区切りで指定することも可能（本クラスでは単一のみ対応）
- 認証不要・無料

### レスポンス構造

```json
[
  {
    "summary": {
      "isbn": "9784297138240",
      "title": "PHPの絵本",
      "volume": "",
      "series": "",
      "publisher": "翔泳社",
      "pubdate": "20240101",
      "cover": "https://..."
    },
    "onix": {
      "DescriptiveDetail": {
        "Contributor": [
          {
            "PersonName": { "content": "山田 太郎" },
            "ContributorRole": ["A01"]
          }
        ]
      },
      "CollateralDetail": {
        "TextContent": [
          {
            "TextType": "03",
            "Text": "内容紹介テキスト"
          }
        ]
      }
    }
  }
]
```

ISBN が未登録の場合は `[null]` が返る。

---

## テストコード

**ファイル**: `www/tests/OpenBDClientTest.php`  
**使用フレームワーク**: PHPUnit 12  
**継承**: `PHPUnit\Framework\TestCase`

### テスト戦略

実際の HTTP 通信は行わず、**Guzzle の `MockHandler`** を使ってレスポンスを偽装する。

```
テスト
  │
  ├─ MockHandler に Response オブジェクトをキューとして積む
  ├─ MockHandler → HandlerStack → Client の順に組み立て
  └─ そのクライアントを OpenBDClient のコンストラクタに注入
```

これにより：
- ネットワーク環境に依存しない
- 任意のレスポンス（正常・異常・エラー）を再現できる
- 高速に実行できる

### ヘルパーメソッド

#### `makeClient(array $responses): OpenBDClient`

モック済みの `OpenBDClient` を生成するファクトリメソッド。
各テストで繰り返し使用する Guzzle モックの組み立てをまとめている。

```php
private function makeClient(array $responses): OpenBDClient
{
    $mock    = new MockHandler($responses);   // レスポンスをキューとして登録
    $handler = HandlerStack::create($mock);
    $http    = new Client(['handler' => $handler]);
    return new OpenBDClient($http);           // DI でモックを注入
}
```

#### `validApiResponse(): string`

正常系テスト用のJSONフィクスチャを返す。
実際のOpenBD APIレスポンスと同じ構造を持つ文字列を生成する。

### テストケース一覧

| # | メソッド名 | 検証内容 | 種別 |
|---|-----------|----------|------|
| 1 | `testFindByIsbnReturnsBookInfo` | 正常取得時に各フィールドが正しく返ること | 正常系 |
| 2 | `testFindByIsbnParsesAuthors` | 著者名・ロールコードが正しくパースされること | 正常系 |
| 3 | `testFindByIsbnParsesDescription` | 内容紹介文が正しく取得されること | 正常系 |
| 4 | `testFindByIsbnStripsHyphens` | ハイフン付きISBNが正規化されてAPIが呼ばれること | 正常系 |
| 5 | `testFindByIsbnReturnsNullWhenNotFound` | APIが `[null]` を返す場合に `null` を返すこと | 異常系（書籍なし） |
| 6 | `testFindByIsbnReturnsNullWhenEmptyArray` | APIが `[]` を返す場合に `null` を返すこと | 異常系（書籍なし） |
| 7 | `testFindByIsbnThrowsOnInvalidIsbn` | 不正ISBN形式で `InvalidArgumentException` がスローされること | 異常系（入力不正） |
| 8 | `testFindByIsbnThrowsOnTooShortIsbn` | 桁数不足のISBNで `InvalidArgumentException` がスローされること | 異常系（入力不正） |
| 9 | `testFindByIsbnThrowsRuntimeExceptionOnNetworkError` | 通信エラー時に `RuntimeException` がスローされること | 異常系（通信エラー） |
| 10 | `testFindByIsbn10Digits` | ISBN-10（10桁）でも `null` でない結果が返ること | 正常系 |

### 各テストの詳細

#### 1. `testFindByIsbnReturnsBookInfo` — 基本的な書籍情報取得

```php
$book = $client->findByIsbn('9784297138240');

$this->assertSame('9784297138240', $book['isbn']);
$this->assertSame('PHPの絵本',     $book['title']);
$this->assertSame('翔泳社',        $book['publisher']);
```

`assertSame` を使い、型と値を厳密に比較している。`assertEquals` では文字列と数値の比較が通過してしまうため、文字列フィールドには `assertSame` が適切。

---

#### 2. `testFindByIsbnParsesAuthors` — 著者情報のパース

```php
$this->assertNotEmpty($book['authors']);
$this->assertSame('山田 太郎', $book['authors'][0]['name']);
$this->assertSame('A01',       $book['authors'][0]['role']);
```

`authors` が空でないことを先に確認してから、インデックスアクセスで詳細を検証する。

---

#### 3. `testFindByIsbnParsesDescription` — 説明文のパース

```php
$this->assertSame('PHPの基礎から学べる入門書です。', $book['description']);
```

ONIX の `TextContent` から `TextType === '03'` の要素を抽出していることを確認する。

---

#### 4. `testFindByIsbnStripsHyphens` — ハイフン付きISBNの正規化

```php
$book = $client->findByIsbn('978-4-297-13824-0');  // ハイフン付きで渡す
$this->assertSame('9784297138240', $book['isbn']); // 数字のみで取得できる
```

ユーザーがハイフン付きで入力してもエラーにならないことを確認する。

---

#### 5. `testFindByIsbnReturnsNullWhenNotFound` — 書籍未登録

```php
// OpenBDは未登録ISBNに対して [null] を返す
new Response(200, [], json_encode([null]))

$result = $client->findByIsbn('9780000000000');
$this->assertNull($result);
```

HTTP ステータスは 200 だが、レスポンスボディが `[null]` の場合に `null` を返すことを検証する。

---

#### 6. `testFindByIsbnReturnsNullWhenEmptyArray` — 空配列レスポンス

```php
new Response(200, [], json_encode([]))

$this->assertNull($result);
```

`[null]` と `[]` の両パターンを個別にテストしている。`empty($data)` の条件でカバーしている。

---

#### 7. `testFindByIsbnThrowsOnInvalidIsbn` — 不正形式のISBN

```php
$this->expectException(\InvalidArgumentException::class);
$this->expectExceptionMessageMatches('/ISBNの形式が不正/');

$client->findByIsbn('invalid-isbn');
```

`expectException` を `findByIsbn` の呼び出し前に宣言することで、その呼び出しで例外がスローされることをアサートする。
Guzzle が呼ばれる前（バリデーション段階）で例外が発生するため、モックは不要。

---

#### 8. `testFindByIsbnThrowsOnTooShortIsbn` — 桁数不足のISBN

```php
$this->expectException(\InvalidArgumentException::class);
$client->findByIsbn('12345');  // 5桁のみ
```

テスト7と合わせて、ISBN の形式バリデーションが機能していることを確認する。

---

#### 9. `testFindByIsbnThrowsRuntimeExceptionOnNetworkError` — 通信エラー

```php
$client = $this->makeClient([
    new ConnectException('Connection refused', new Request('GET', 'get')),
]);

$this->expectException(\RuntimeException::class);
$this->expectExceptionMessageMatches('/APIへの通信に失敗しました/');

$client->findByIsbn('9784297138240');
```

`ConnectException` は `GuzzleException` のサブクラス。
クラス内で `GuzzleException` をキャッチして `RuntimeException` に変換していることを確認する。

---

#### 10. `testFindByIsbn10Digits` — ISBN-10 のサポート

```php
$book = $client->findByIsbn('4297138247');  // 10桁
$this->assertNotNull($book);
```

正規表現 `/^\d{10}(\d{3})?$/` が ISBN-10 を受け入れることを確認する。

---

## テスト実行方法

Docker コンテナが起動している状態で以下を実行します。

```bash
# プロジェクトルートで Docker を起動
docker compose up -d

# OpenBDClientTest のみ実行
docker compose exec app bash -c \
  "cd /var/www && ./vendor/bin/phpunit tests/OpenBDClientTest.php"

# 全テストを実行
docker compose exec app bash -c \
  "cd /var/www && ./vendor/bin/phpunit"
```

**期待される出力（全テスト成功時）**

```
PHPUnit 12.x.x

..........                                              10 / 10 (100%)

Time: 00:00.xxx, Memory: xx.xx MB

OK (10 tests, XX assertions)
```
