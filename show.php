<?php

require_once __DIR__ . '/functions.php';

// index.phpから該当のレコードidを使用
$id = $_GET['id'];

$dbh = connectDb();
$sql = 'SELECT * FROM tweets WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$tweet = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>tweet</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1><?= h($tweet['content']) ?></h1>
    <a href="index.php">戻る</a>
    <ul class="tweet-list">
        <li>
            <!-- 選択idを表示 -->
            [#<?= h($tweet['id']) ?>]
            <?= h($tweet['content']) ?><br>
            投稿日時: <?= h($tweet['created_at']) ?>
            <?php if ($tweet['good'] === '0'): ?>
                <a href="good.php?id=<?= h($tweet['id']) . "&good=1" ?>" class="good-link"><?= '☆' ?></a>
            <?php else: ?>
                <a href="good.php?id=<?= h($tweet['id']) . "&good=0" ?>" class="bad-link"><?= '★' ?></a>
            <?php endif; ?>
            <a href="edit.php?id=<?= h($tweet['id']) ?>">[編集]</a>
            <a href="delete.php?id=<?= h($tweet['id']) ?>">[削除]</a>
            <hr>
        </li>
    </ul>
</body>

</html>