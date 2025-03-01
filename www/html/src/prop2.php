<?php
require_once './myclass/Person.php';

$p = new Person('太郎','山田');

$p->show();

// 動的メソッドの追加
$p->bye = function(): void{
    print "{$this->lastName}{$this->firstName}さん。さようなら";
};
$p->bye();

?>
