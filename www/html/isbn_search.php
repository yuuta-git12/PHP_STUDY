<?php
require_once __DIR__ . '/../vendor/autoload.php';

use wings\selfphp\myclass\OpenBDClient;

$book  = null;
$error = null;
$isbn  = '';
$year = '';
$month = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = trim($_POST['isbn'] ?? '');

    if ($isbn === '') {
        $error = 'ISBNを入力してください。';
    } else {
        try {
            $client = new OpenBDClient();
            $book   = $client->findByIsbn($isbn);

            if ($book === null) {
                $error = '書籍が見つかりませんでした。ISBNを確認してください。';
            }else{
                $date = DateTime::createFromFormat('Ym',$book['pubdate']);
                $year = $date->format('Y');
                $month = $date->format('m');
            }

        } catch (\InvalidArgumentException $e) {
            $error = $e->getMessage();
        } catch (\RuntimeException $e) {
            $error = $e->getMessage();
        }
    }
}

/**
 * HTMLエスケープ用ヘルパー
 */
function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISBN書籍検索</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
            color: #333;
        }
        h1 { border-bottom: 2px solid #4a90d9; padding-bottom: 8px; }
        .search-form {
            display: flex;
            gap: 8px;
            margin: 24px 0;
        }
        .search-form input[type="text"] {
            flex: 1;
            padding: 10px 14px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-form button {
            padding: 10px 20px;
            font-size: 1rem;
            background: #4a90d9;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form button:hover { background: #357abd; }
        .error {
            background: #fde8e8;
            border-left: 4px solid #e53e3e;
            padding: 12px 16px;
            border-radius: 4px;
            color: #c53030;
        }
        .book-card {
            display: flex;
            gap: 24px;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 24px;
            margin-top: 16px;
        }
        .book-cover img {
            max-width: 120px;
            border: 1px solid #ddd;
        }
        .book-cover .no-cover {
            width: 120px;
            height: 160px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 0.8rem;
            border-radius: 4px;
        }
        .book-info h2 { margin-top: 0; }
        .book-info table { border-collapse: collapse; width: 100%; }
        .book-info th {
            text-align: left;
            padding: 6px 12px 6px 0;
            color: #666;
            white-space: nowrap;
            vertical-align: top;
        }
        .book-info td { padding: 6px 0; }
        .description {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #ddd;
            line-height: 1.7;
        }
    </style>
</head>
<body>

<h1>ISBN書籍検索</h1>
<p>ISBN-10またはISBN-13を入力して書籍情報を検索します。</p>

<form class="search-form" method="POST" action="">
    <input
        type="text"
        name="isbn"
        value="<?= h($isbn) ?>"
        placeholder="例: 9784297138240"
        maxlength="17"
    >
    <button type="submit">検索</button>
</form>

<?php if ($error !== null): ?>
    <div class="error"><?= h($error) ?></div>
<?php endif; ?>

<?php if ($book !== null): ?>
    <div class="book-card">
        <div class="book-cover">
            <?php if (!empty($book['cover'])): ?>
                <img src="<?= h($book['cover']) ?>" alt="書影">
            <?php else: ?>
                <div class="no-cover">書影なし</div>
            <?php endif; ?>
        </div>

        <div class="book-info">
            <h2><?= h($book['title']) ?><?= $book['volume'] !== '' ? ' ' . h($book['volume']) : '' ?></h2>
            <table>
                <tr>
                    <th>ISBN</th>
                    <td><?= h($book['isbn']) ?></td>
                </tr>
                <tr>
                    <th>出版社</th>
                    <td><?= h($book['publisher']) ?></td>
                </tr>
                <tr>
                    <th>発行日</th>
                    <td><?= h($year) ?>年<?= h($month) ?>月</td>
                </tr>
                <?php if (!empty($book['series'])): ?>
                <tr>
                    <th>シリーズ</th>
                    <td><?= h($book['series']) ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($book['authors'])): ?>
                <tr>
                    <th>著者</th>
                    <td>
                        <?php foreach ($book['authors'] as $author): ?>
                            <?= h($author['name']) ?><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>

            <?php if (!empty($book['description'])): ?>
                <div class="description">
                    <?= nl2br(h($book['description'])) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
