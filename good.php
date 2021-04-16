<?php

require_once __DIR__ . '/functions.php';

$dbh = connectDb();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // index.phpから該当のレコードidを使用
    $id = $_GET['id'];

    // フォームに入力されたデータの受け取り
    $good = $_GET['good'];

    // データを更新する処理
    $sql = 'UPDATE tweets SET good = :good WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':good', $good, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // 更新されたら元のURLへ戻る処理
    $uri = $_SERVER['HTTP_REFERER'];
    header("Location: " . $uri);
    exit;
}