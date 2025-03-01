<?php
class Person{
    
    // 動的メソッド　追加のメソッド群を格納する配列
    private array $methods = [];

    // コンストラクターの省略構文 PHP8.0以降から使用可
    public function __construct(
        public string $firstName,
        public string $lastName
    ){}

    // __CLASS__：定義済み定数の一つ、現在のクラス名を表す
    public function __destruct(){
        print '<p>'.__CLASS__.'オブジェクトが破棄されました。</p>';
    }

    public function show() :void{
        print "<p>僕の名前は{$this->lastName}{$this->firstName}です</p>";
    }

    // 指定のメソッドを登録
    //self::classは現在のクラスを表す
    public function __set(string $name, Closure $method): void{
        $this->methods[$name] = $method->bindTo($this, self::class);
    }
    // 動的に追加されたメソッドを実行
    public function __call(string $name, array $args): mixed{
        // methodsプロパティに未登録のメソッドはエラー
        if(!array_key_exists($name,$this->methods)){
            throw new Exception("{$name} method is not existed");
        }
        return $this->methods[$name](...$args);
    }
};
?>