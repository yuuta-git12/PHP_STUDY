<?php

namespace wings\selfphp\Algorithm\Sort;

/**
 * バブルソート
 *
 * 隣り合う要素を比較して交換を繰り返すソートアルゴリズム。
 * 時間計算量: O(n^2)
 * 空間計算量: O(1)
 */
class BubbleSort
{
    /**
     * 配列を昇順にソートする
     *
     * @param array $array ソート対象の配列
     * @return array ソート済みの配列
     */
    public function sort(array $array): array
    {
        $n = count($array);

        for ($i = 0; $i < $n - 1; $i++) {
            $swapped = false;

            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$j] > $array[$j + 1]) {
                    // 隣り合う要素を交換
                    [$array[$j], $array[$j + 1]] = [$array[$j + 1], $array[$j]];
                    $swapped = true;
                }
            }

            // 一度も交換が発生しなければ既にソート済み
            if (!$swapped) {
                break;
            }
        }

        return $array;
    }

    /**
     * 標準入力から数値を受け取ってソートし、結果を出力する
     *
     * 入力形式:
     *   1行目: 要素数 n
     *   2行目: スペース区切りの数値列
     *
     * 実行例:
     *   echo "5\n3 1 4 1 5" | php BubbleSort.php
     */
    public function run(): void
    {
        fgets(STDIN); // 要素数（使用しない）
        $input = trim(fgets(STDIN));
        $array = array_map('intval', explode(' ', $input));

        $sorted = $this->sort($array);

        echo implode(' ', $sorted) . PHP_EOL;
    }
}

// CLIから直接実行された場合のみ動作
if (PHP_SAPI === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    require_once __DIR__ . '/../../../../vendor/autoload.php';
    (new BubbleSort())->run();
}
