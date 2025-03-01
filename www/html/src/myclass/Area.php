<?php
class Area {
    
    public const PI = 3.14;
    
    // 円の面積を求めるメソッド
    public static function circle(float $radius): float{
        return pow($radius,2) * self::PI;
    }
}
?>