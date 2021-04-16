<?php

require_once __DIR__ . '/functions.php';

$id = $_GET['id'];

$dbh = connectDb();
$sql = 'SELECT * FROM tweets WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$tweet = $stmt->fetch();

// $tweetがみつからないときはindex.phpに飛ばす
if (!$tweet) {
    header('Location: index.php');
    exit;
}

$sql_delete = 'DELETE FROM tweets WHERE id = :id';
$stmt_delete = $dbh->prepare($sql_delete);
$stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_delete->execute();

header('Location: index.php');
exit;