<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $x = 10;
    $y = ++$x;
    var_dump($x);
    var_dump($y);

    $i = 10;
    $j = $i++;
    var_dump($i);
    var_dump($j);

    var_dump(010 == 8);
    var_dump("13xyz" == 13);
    $x = 111;
    $flag = ($x === 1?0:-1);
    print($flag); 
     ?>
</body>
</html>