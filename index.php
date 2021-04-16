<?php

// 関数ファイルの読み込み
require_once __DIR__ . '/functions.php';

// データベース接続
$dbh = connectDb();

// SQLの準備
// SQL後半の'order by updated_at desc'というのは「更新日時が新しい順」という意味
$sql = 'SELECT * FROM tweets ORDER BY created_at DESC';
// プリペアードステートメントの準備
$stmt = $dbh->prepare($sql);
// プリペアードステートメントの実行
$stmt->execute();
// $tweetsに連想配列の形式で記事データを格納する
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 新規タスク
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // フォームに入力されたデータ
    $content = $_POST['content'];
    $errors = [];

    if ($content == '') {
        $errors['content'] = 'ツイート内容を入力してください。';
    }

    // バリデーションを突破したあとの処理 "もし空っぽだったら↓"
    if (!$errors) {
        // データを追加する
        $sql = 'INSERT INTO tweets (content) VALUES (:content)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();
        // index.phpに戻る
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <div id="home" class="wrapper">
        <header class="page-header">
            <h1><a href="index.php"><img class="logo" src="images/logo.png" alt="Tweet ホーム"></a></h1>
            <nav>
                <ul class="main-nav">
                    <li><a href=""><img src="images/home.png" alt="ホーム"><span class="nav-home">ホーム</span></a></li>
                    <li><a href=""><img src="images/search.png" alt="検索"><span class="nav-item">検索</span></a></li>
                    <li><a href=""><img src="images/list.png" alt="リスト"><span class="nav-item">リスト</span></a></li>
                </ul>
            </nav>
        </header>
        <div class="main">
            <div class="bg">
                <h2>Tweet</h2>
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
                    <div class="form-group">
                        <textarea name="content" placeholder="いまどうしてる？"></textarea>
                        <input type="submit" value="ツイート" class="btn tweet-btn">
                    </div>
                </form>
            </div>
            <!-- 投稿の下に一覧を表示 -->
            <!-- もし$tweetsにデータが設定されていたら (if)-->
            <?php if (count($tweets)): ?>
                <!-- foreachで出力 -->
                <?php foreach ($tweets as $tweet): ?>
                    <div class="tweet-content">
                        <div class="tweet-desc">
                            <div class="tweet-icon">
                                <img src="images/logo_.png" alt="tweetアプリ">
                                <span class="tweet-time"><?= convertTime(h($tweet['created_at'])) ?></span>
                            </div>
                            <div class="tweet-text">
                                <a href="show.php?id=<?= h($tweet['id']) ?>"></a>
                                <p><?= h($tweet['content']) ?></p>
                            </div>
                        </div>
                        <div class="good">
                            <!-- お気に入り☆★ -->
                            <?php if ($tweet['good'] === '0'): ?>
                                <a href="good.php?id=<?= h($tweet['id']) . "&good=1" ?>" class="good-icon-notchecked">☆</a>
                            <?php else: ?>
                                <a href="good.php?id=<?= h($tweet['id']) . "&good=0" ?>" class="good-icon-checked">★</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <!-- もし$tweetsにデータが設定されていなかったら(else) -->
            <?php else: ?>
                <p>投稿されたtweetはありません</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
