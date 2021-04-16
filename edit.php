<?php

require_once __DIR__ . '/functions.php';

$id = $_GET['id'];

$dbh = connectDb();
$sql = 'SELECT * FROM tweets WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$tweet = $stmt->fetch(PDO::FETCH_ASSOC);

// 存在しないidを指定された場合はindex.phpに飛ばす
if (!$tweet) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];
    $errors = [];

  // バリデーション
    if ($content == '') {
        $errors['content'] = '入力がされていません。';
    }

    if ($content === $tweet['content']) {
        $errors['uncanged'] = '内容が変更されていません。';
    }

  // バリデーション突破後
    if (!$errors) {
        // tweet内容も変更と同時に投稿時間も更新する
        $sql = 'UPDATE tweets SET content = :content, created_at = CURRENT_TIMESTAMP WHERE id = :id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>

<?php include_once __DIR__ . '/_head.html' ?>

</head>

<body>
    <h1>tweetの編集</h1>
    <p><a href="index.php">戻る</a></p>
    <?php if ($errors): ?>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li>
                    <?= h($error) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form action="" method="post">
        <p>
            <label for="content">ツイート内容</label><br>
            <textarea name="content" cols="30" rows="5"><?= h($tweet['content']) ?></textarea>
        </p>
        <p><input type="submit" value="編集する"></p>
    </form>
</body>

</html>