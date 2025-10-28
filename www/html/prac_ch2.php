<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $title = 'サーバサイド技術の学び舎-WINGS';
    $data1 = "サポートサイト\t「{$title}」へ<br/>";
    // シングルクォートでは変数展開(式展開)されない　エスケープシーケンスも認識されない
    $data2 = 'サポートサイト\t「{$title}」へ<br/>';
    print $data1;
    print $data2;
    
    $str = 'PHP(PHP:Hypertext Preprocessor)';
    $msg = <<<EOD
    {$str}は、サーバサイドで動作する簡易なスクリプト言語です。
    まずは、本書でじっくり基礎が試しましょう。<br/>
    "Let's start,everyone!!"
    EOD;
    print $msg;

    $n1;
    $n2 = null;

    print($n1);
    print($n2);
    ?>
</body>
</html>