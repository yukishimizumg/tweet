<?php

require_once __DIR__ . '/config.php';

// 接続処理を行う関数
function connectDb()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

// エスケープ処理を行う関数
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function convertTime($tweet_time){
    $unix = strtotime($tweet_time);
    $now  = time();
    $diff_sec = $now - $unix;

    if ($diff_sec < 60) {
        $time = $diff_sec;
        $unit = "秒前";
        return (int)$time .$unit;
    } elseif ($diff_sec < 3600) {
        $time = $diff_sec/60;
        $unit = "分前";
        return (int)$time .$unit;
    } elseif ($diff_sec < 86400) {
        $time = $diff_sec/3600;
        $unit = "時間前";
        return (int)$time .$unit;
    } elseif ($diff_sec < 2764800) {
        $time = $diff_sec/86400;
        $unit = "日前";
        return (int)$time .$unit;
    } else {
        $time = date("Y年n月j日", $unix);
        return $time;
    }
}