/**
 * PHPUnitのassertEqualsとassertSameの動作比較用コード
 * assertEqualsは == と同じ緩い比較
 * assertSameは === と同じ型と値をチェックする厳格な比較
 */
<?php

use PHPUnit\Framework\TestCase;

class User {
    public function __construct(public string $name)
    {
        
    }
}

class AssertionTest extends TestCase
{
    public function testAssert()
    {
        $expected = 2024;
        $actual = '2024';
        $this->assertEquals($expected,$actual);//型は異なるが値が同じなのでアサーション成功
        $this->assertSame($expected,$actual); //型が異なるのでアサーション失敗
    }

    public function testZeroFalse()
    {
        $expected = 0;
        $actual = false;
        $this->assertEquals($expected,$actual);//0==falseのためアサーション成功
        $this->assertSame($expected,$actual); //0===falseのためアサーション失敗
    }

    public function testArary() 
    {
        $expected = [
            'name'=>'asumikam',
            'like'=>'peach'
        ];
        $actual = [
            'like'=>'peach',
            'name'=>'asumikam'
        ];
        
        $this->assertEquals($expected,$actual);//アサーション成功(キーと値が合っているかのみチェックしている)
        $this->assertSame($expected,$actual); //アサーション失敗(キーと値は合っているが、配列の順番が異なるため)
        
    }


    public function testObject()
    {
        $asumikam1 = new User('asumikam');
        $asumikam2 = new User('asumikam');

        $this->assertEquals($asumikam1,$asumikam2);//アサーション成功(変数の値が同じ為)
        $this->assertSame($asumikam1,$asumikam2); //アサーション失敗(変数の値は同じだが、異なるオブジェクトのため)
    }
}

