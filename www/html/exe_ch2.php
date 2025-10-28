<?php
    $value = (int)0.15535e3;
    var_dump($value);

    $fruits = ["りんご","みかん","カキ"];
    var_dump($fruits);

    $vegetables = ["red" => "トマト", "yellow" => "カボチャ", "green" => "レタス"];
    var_dump($vegetables);

    $str = <<<EOD
    こんにちは、PHP!
    がんばります。<br/>
    ありがとうございます。<br/>
    EOD;
    var_dump($str);

    $num = 0b1_00_11_01;
    var_dump($num);

    $data = ['高江', 'かけ谷', '日尾', '本多', '矢吹'];
    foreach($data as $key => $value) {
        echo $key, ':', $value, '<br>';
    }

    $data = ['高江', 'かけ谷', '日尾', '本多', '矢吹'];
    foreach($data as &$value) {
        echo $value, '<br>';
    }

    for($i = 1; $i < 10; $i++) {
        for($j = 1; $j < 10; $j++) {
            echo $i, 'x', $j, '=', $i * $j, '<br>';
        }
    }

?>