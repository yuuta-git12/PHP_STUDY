<!-- JSONの結果表示テンプレ -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewpoint" content="width-device-width, initial-scale=1.0">
        <title>JSON内容表示テンプレ</title>
    </head>
    <body>
        <?php

        $json_str = '{"id":1, "name":"suzuki", "attribute":{"gender":"male", "phone_number":"xxxxxxxxxxx", "birth":"1991/01/01"}}';
        
        // jsonをオブジェクト形式でデコードした場合
        $json_obj = json_decode($json_str);
        print $json_obj->id;
        echo "<br>";
        print $json_obj->name;
        echo "<br>";
        print $json_obj->attribute->gender;
        echo "<br>";
        print $json_obj->attribute->phone_number;
        echo "<br>";
        print $json_obj->attribute->birth;

        // jsonを配列形式でデコードした場合(JSONがネストされているとエラーになる)
        $json_array = json_decode($json_str,true,4);
        var_dump($json_array);
        echo 'Last error: ', json_last_error_msg(), PHP_EOL, PHP_EOL;
        foreach($json_array as $value){
            echo $value;
            echo "<br>";
        }
        ?>
    </body>
</html>

