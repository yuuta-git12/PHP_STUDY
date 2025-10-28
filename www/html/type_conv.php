<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <pre>
    <?php
    var_dump((int)1530.95);
    var_dump((int)-1530.95);
    var_dump((int)true);
    var_dump((string)true);
    var_dump((int)false);
    var_dump((string)false);
    var_dump((array)108);

    var_dump((int)'0b11');
    var_dump((int)0b11);
    var_dump(bindec('0b11'));

    var_dump((int)0777);
    var_dump((int)'0777');
    var_dump(octdec('0777'));

    var_dump((int)0xFF);
    var_dump((int)'0xFF');
    var_dump(hexdec('0xFF'));

    var_dump((int)155.36);
    $fruit = ["りんご","みかん","かき"];
    var_dump($fruit);
    $vegetables = ["red" => "トマト","yellow" => "カボチャ","green" => "レタス"];
    var_dump($vegetables);
    $str = <<<EOD
    これはテストです
    いいですか
    EOD;
    print $str;
    $num = 0b1_00_11_01;
    print $num;
    ?>
</body>
</html>