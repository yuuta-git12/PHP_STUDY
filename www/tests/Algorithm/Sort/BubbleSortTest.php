<?php

namespace wings\selfphp\Algorithm\Sort;

use PHPUnit\Framework\TestCase;

class BubbleSortTest extends TestCase
{
    private BubbleSort $sorter;

    protected function setUp(): void
    {
        $this->sorter = new BubbleSort();
    }

    public function test_整数の配列をソートできる(): void
    {
        $input    = [5, 3, 8, 1, 4];
        $expected = [1, 3, 4, 5, 8];

        $this->assertSame($expected, $this->sorter->sort($input));
    }

    public function test_既にソート済みの配列はそのまま返る(): void
    {
        $input    = [1, 2, 3, 4, 5];
        $expected = [1, 2, 3, 4, 5];

        $this->assertSame($expected, $this->sorter->sort($input));
    }

    public function test_逆順の配列をソートできる(): void
    {
        $input    = [5, 4, 3, 2, 1];
        $expected = [1, 2, 3, 4, 5];

        $this->assertSame($expected, $this->sorter->sort($input));
    }

    public function test_要素が1つの配列はそのまま返る(): void
    {
        $input    = [42];
        $expected = [42];

        $this->assertSame($expected, $this->sorter->sort($input));
    }

    public function test_空の配列はそのまま返る(): void
    {
        $this->assertSame([], $this->sorter->sort([]));
    }

    public function test_重複する要素を含む配列をソートできる(): void
    {
        $input    = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3];
        $expected = [1, 1, 2, 3, 3, 4, 5, 5, 6, 9];

        $this->assertSame($expected, $this->sorter->sort($input));
    }
}
