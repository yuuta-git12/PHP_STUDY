<?php 
require_once __DIR__ . '/Person.php';

class Japanese extends Person{
    function hello(){
        echo 'こんにちは私は日本人です';
        return $this;
    }
}


?>