<?php

namespace wings\selfphp\myclass;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;

/**
 * OpenBD APIを使って書籍情報を取得するクラス
 * @see https://openbd.jp/
 */
class OpenBDClient
{
    private const API_BASE_URL = 'https://api.openbd.jp/v1/';

    private ClientInterface $httpClient;

    public function __construct(?ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => self::API_BASE_URL,
            'timeout'  => 10.0,
        ]);
    }

    /**
     * ISBNで書籍情報を取得する
     *
     * @param string $isbn ISBN-10またはISBN-13
     * @return array|null 書籍情報の連想配列。見つからない場合はnull
     * @throws \InvalidArgumentException ISBNの形式が不正な場合
     * @throws \RuntimeException API通信エラーの場合
     */
    public function findByIsbn(string $isbn): ?array
    {
        $isbn = preg_replace('/[-\s]/', '', $isbn);

        if (!$this->isValidIsbn($isbn)) {
            throw new \InvalidArgumentException("ISBNの形式が不正です: {$isbn}");
        }

        try {
            $response = $this->httpClient->request('GET', 'get', [
                'query' => ['isbn' => $isbn],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // OpenBDは見つからない場合 [null] を返す
            if (empty($data) || $data[0] === null) {
                return null;
            }

            return $this->parseBookData($data[0]);

        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw new \RuntimeException("APIへの通信に失敗しました: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * ISBN-10またはISBN-13の形式チェック
     */
    private function isValidIsbn(string $isbn): bool
    {
        return preg_match('/^\d{10}(\d{3})?$/', $isbn) === 1;
    }

    /**
     * OpenBDのレスポンスから必要な書籍情報を抽出する
     */
    private function parseBookData(array $raw): array
    {
        $summary = $raw['summary'] ?? [];
        $onix    = $raw['onix'] ?? [];

        // 説明文はOnixのCollateralDetailから取得
        $description = '';
        $textContents = $onix['CollateralDetail']['TextContent'] ?? [];
        foreach ($textContents as $content) {
            if (($content['TextType'] ?? '') === '03') {
                $description = $content['Text'] ?? '';
                break;
            }
        }

        // 著者情報
        $authors = [];
        $contributors = $onix['DescriptiveDetail']['Contributor'] ?? [];
        foreach ($contributors as $contributor) {
            $name = $contributor['PersonName']['content'] ?? ($contributor['PersonNameInverted'] ?? '');
            $role = $contributor['ContributorRole'][0] ?? '';
            if ($name !== '') {
                $authors[] = ['name' => $name, 'role' => $role];
            }
        }

        return [
            'isbn'        => $summary['isbn'] ?? '',
            'title'       => $summary['title'] ?? '',
            'volume'      => $summary['volume'] ?? '',
            'series'      => $summary['series'] ?? '',
            'publisher'   => $summary['publisher'] ?? '',
            'pubdate'     => $summary['pubdate'] ?? '',
            'cover'       => $summary['cover'] ?? '',
            'authors'     => $authors,
            'description' => $description,
        ];
    }
}
