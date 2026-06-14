<?php
require_once __DIR__ . '/src/myclass/Person.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Person クラスサンプル</title>
</head>
<body>
<h1>Person クラス サンプル</h1>

<?php
// --- 基本的なオブジェクト生成と show() ---
echo '<h2>1. 基本的なオブジェクト生成</h2>';
$person1 = new Person('太郎', '山田',25);
$person1->show();
$person1->show_age();


// --- プロパティへの直接アクセス ---
echo '<h2>2. プロパティへのアクセス</h2>';
echo "<p>firstName: {$person1->firstName}</p>";
echo "<p>lastName:  {$person1->lastName}</p>";
echo Person::static_show();

// --- 動的メソッドの追加（__set / __call） ---
echo '<h2>3. 動的メソッドの追加</h2>';
$person2 = new Person('花子', '鈴木');

// greet メソッドを動的に登録
$person2->greet = function(string $greeting): void {
    echo "<p>{$greeting}、私は{$this->lastName}{$this->firstName}です！</p>";
};

// 登録したメソッドを呼び出す
$person2->greet('こんにちは');
$person2->greet('はじめまして');

// --- 例外処理：未登録メソッドの呼び出し ---
echo '<h2>4. 未登録メソッド呼び出し時の例外処理</h2>';
$person3 = new Person('次郎', '田中');
try {
    $person3->unknownMethod();
} catch (Exception $e) {
    echo "<p style='color:red;'>例外: {$e->getMessage()}</p>";
}

// --- デストラクタの確認 ---
echo '<h2>5. デストラクタ（スクリプト終了時に実行）</h2>';
echo '<p>スクリプト終了時にデストラクタが呼ばれます。</p>';
?>

</body>
</html>
