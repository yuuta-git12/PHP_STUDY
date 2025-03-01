<?php
require_once './myclass/Area.php';

print '円周率'.Area::PI.'<br />';
// 静的メソッドcircleを呼び出す
print '円の面積:'.Area::circle(10).'cm^2';

?>