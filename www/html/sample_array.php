<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    //配列
    $data = ['山田','掛谷','日尾','本多','矢吹'];
    echo nl2br(print_r($data,true));
    
    print $data[3]."<br/>";
    $data[] = '月華';
    echo nl2br(print_r($data,true));


    // 連想配列
    $members = [
        '山田太郎' => '千葉県府中市東町1-1-1',
        '掛谷翔太' => '広島県福岡市北町2-2-2',
        '日尾有宏' => '群馬県三次市南町3-3-3',
        '本多のぞみ' => '埼玉県豊田市西町4-4-4',
        '矢吹久美子' => '愛知県岡山市本町5-5-5'
    ];

    echo nl2br(print_r($members,true));

    // 多次元配列
    $fruits = [
        [
            ['Sなし','Mなし','Lなし'],
            ['Sりんご','Mりんご','Lりんご'],
            ['S洋ナシ','M洋ナシ','L洋ナシ'],
        ],
        [
            ['Sもも','Mもも','Lもも'],
            ['Sすもも','Mすもも','Lすもも'],
            ['Sプラム','Mプラム','Lプラム'],
        ],
        [
            ['Sみかん','Mみかん','Lみかん'],
            ['S八朔','M八朔','L八朔'],
            ['Sネーブル','Mネーブル','Lネーブル'],
        ]
    ];
    echo nl2br(print_r($fruits,true));

    $data = [-10 => '山田','日尾'];
    echo nl2br(print_r($data,true));
    ?>
    
</body>
</html>