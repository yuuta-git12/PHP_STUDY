<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    print "頑張ろうPHP!<br/>";
    $msg = 'こんにちは世界<br/>';
    print $msg;
    $address = '421-0401,静岡県,榛原町,帆毛田1-15-9';
    $city = explode(',',$address)[2];
    if($city === '榛原町'){
        print '榛原町<br/>';
    };
    print_r(explode(',',$address));

    print '<br/>';

    const TAX = 1.1;
    $price = 1000;
    $sum = $price + TAX;
    print $sum;

    print '<br/>';

    $x = 100;
    # エラーとなる構文
    // const VALUE = $x;
    // const MAIN = dirname(__FILE__).'/test.php';

    # 正しい構文
    define('VALUE', $x);
    define('MAIN',dirname(__FILE__).'/test.php');
    print(VALUE.'<br/>');
    print(MAIN.'<br/>');
    # 実行中のファイル名（絶対パス）
    print(__FILE__.'<br/>');
    # 実行中のファイルが存在するフォルダー
    print(__DIR__.'<br/>');
    # 実行中の行番号
    print(__LINE__.'<br/>');
    # 実行中の関数名
    print(__FUNCTION__.'<br/>');
    # 実行中のクラス名
    print(__CLASS__.'<br/>');
    # 実行中のメソッド名
    print(__METHOD__.'<br/>');
    # 実行中のトレイと名
    print(__TRAIT__.'<br/>');
    # 現在の名前空間
    print(__NAMESPACE__.'<br/>');
    # フォルダーの区切り文字
    print(DIRECTORY_SEPARATOR.'<br/>');
    # パスの区切り文字
    print(PATH_SEPARATOR.'<br/>');
    # 使用しているPHPのバージョン
    print(PHP_VERSION.'<br/>');
    ?>
</body>
</html>