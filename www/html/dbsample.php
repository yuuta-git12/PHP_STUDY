<?php
    try{
        $dsn = 'mysql:dbname=phpstudydb';
        $db = new PDO($dsn, 'studyuser', 'studypass');
        echo "接続に成功しました";
    } catch (PDOException $e) {
        echo "接続に失敗しました";
        echo $e->getMessage();
        exit;
    }
?>