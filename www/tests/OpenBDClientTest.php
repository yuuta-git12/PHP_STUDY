<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use wings\selfphp\myclass\OpenBDClient;

class OpenBDClientTest extends TestCase
{
    // ---- ヘルパー ----

    /**
     * モックHTTPクライアントを注入したOpenBDClientを生成する
     */
    private function makeClient(array $responses): OpenBDClient
    {
        $mock    = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $http    = new Client(['handler' => $handler]);

        return new OpenBDClient($http);
    }

    /**
     * OpenBD APIの正常レスポンス用フィクスチャを返す
     */
    private function validApiResponse(): string
    {
        return json_encode([[
            'summary' => [
                'isbn'      => '9784297138240',
                'title'     => 'PHPの絵本',
                'volume'    => '',
                'series'    => '',
                'publisher' => '翔泳社',
                'pubdate'   => '20240101',
                'cover'     => 'https://example.com/cover.jpg',
            ],
            'onix' => [
                'DescriptiveDetail' => [
                    'Contributor' => [
                        [
                            'PersonName'    => ['content' => '山田 太郎'],
                            'ContributorRole' => ['A01'],
                        ],
                    ],
                ],
                'CollateralDetail' => [
                    'TextContent' => [
                        [
                            'TextType' => '03',
                            'Text'     => 'PHPの基礎から学べる入門書です。',
                        ],
                    ],
                ],
            ],
        ]]);
    }

    // ---- テスト ----

    /**
     * 有効なISBN-13で書籍情報が取得できること
     */
    public function testFindByIsbnReturnsBookInfo(): void
    {
        $client = $this->makeClient([
            new Response(200, [], $this->validApiResponse()),
        ]);

        $book = $client->findByIsbn('9784297138240');

        $this->assertNotNull($book);
        $this->assertSame('9784297138240', $book['isbn']);
        $this->assertSame('PHPの絵本', $book['title']);
        $this->assertSame('翔泳社', $book['publisher']);
        $this->assertSame('20240101', $book['pubdate']);
        $this->assertSame('https://example.com/cover.jpg', $book['cover']);
    }

    /**
     * 著者情報が正しくパースされること
     */
    public function testFindByIsbnParsesAuthors(): void
    {
        $client = $this->makeClient([
            new Response(200, [], $this->validApiResponse()),
        ]);

        $book = $client->findByIsbn('9784297138240');

        $this->assertNotEmpty($book['authors']);
        $this->assertSame('山田 太郎', $book['authors'][0]['name']);
        $this->assertSame('A01', $book['authors'][0]['role']);
    }

    /**
     * 説明文が正しくパースされること
     */
    public function testFindByIsbnParsesDescription(): void
    {
        $client = $this->makeClient([
            new Response(200, [], $this->validApiResponse()),
        ]);

        $book = $client->findByIsbn('9784297138240');

        $this->assertSame('PHPの基礎から学べる入門書です。', $book['description']);
    }

    /**
     * ISBNにハイフンが含まれていても正しく処理されること
     */
    public function testFindByIsbnStripsHyphens(): void
    {
        $client = $this->makeClient([
            new Response(200, [], $this->validApiResponse()),
        ]);

        // ハイフン付きISBNを渡してもAPIは呼ばれる
        $book = $client->findByIsbn('978-4-297-13824-0');

        $this->assertNotNull($book);
        $this->assertSame('9784297138240', $book['isbn']);
    }

    /**
     * APIが [null] を返した場合（書籍未登録）はnullを返すこと
     */
    public function testFindByIsbnReturnsNullWhenNotFound(): void
    {
        $client = $this->makeClient([
            new Response(200, [], json_encode([null])),
        ]);

        $result = $client->findByIsbn('9780000000000');

        $this->assertNull($result);
    }

    /**
     * APIが空配列を返した場合はnullを返すこと
     */
    public function testFindByIsbnReturnsNullWhenEmptyArray(): void
    {
        $client = $this->makeClient([
            new Response(200, [], json_encode([])),
        ]);

        $result = $client->findByIsbn('9780000000000');

        $this->assertNull($result);
    }

    /**
     * 不正なISBN形式の場合はInvalidArgumentExceptionがスローされること
     */
    public function testFindByIsbnThrowsOnInvalidIsbn(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/ISBNの形式が不正/');

        // Guzzleが実際に呼ばれないのでモックなしでOK
        $client = new OpenBDClient();
        $client->findByIsbn('invalid-isbn');
    }

    /**
     * 短すぎるISBNでもInvalidArgumentExceptionがスローされること
     */
    public function testFindByIsbnThrowsOnTooShortIsbn(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = new OpenBDClient();
        $client->findByIsbn('12345');
    }

    /**
     * API通信エラー時にRuntimeExceptionがスローされること
     */
    public function testFindByIsbnThrowsRuntimeExceptionOnNetworkError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/APIへの通信に失敗しました/');

        $client = $this->makeClient([
            new ConnectException('Connection refused', new Request('GET', 'get')),
        ]);

        $client->findByIsbn('9784297138240');
    }

    /**
     * ISBN-10（10桁）でも検索できること
     */
    public function testFindByIsbn10Digits(): void
    {
        $client = $this->makeClient([
            new Response(200, [], $this->validApiResponse()),
        ]);

        $book = $client->findByIsbn('4297138247');

        $this->assertNotNull($book);
    }
}
