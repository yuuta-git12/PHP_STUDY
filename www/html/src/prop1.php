<?php
require_once './myclass/Person1.php';

$p1 = new Person('太郎','山田');

$p2= new Person('花子','鈴木');
// インスタンス$p2だけにプロパティを追加する（PHP8.2以降はこの動的プロパティは非推奨)
$p2->age = 52;

print "<p>僕の名前は{$p1->lastName}{$p1->firstName}です</p>";
print "<p>私の名前は{$p2->lastName}{$p2->firstName} {$p2->age}歳です</p>";
?>