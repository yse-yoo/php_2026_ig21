<?php
// TODO: 設定ファイル env.php を読み込む
require_once '';

// 変数設定
$db_connection = DB_CONNECTION;
$db_name       = DB_DATABASE;
$db_host       = DB_HOST;
$db_port       = DB_PORT;
$db_user       = DB_USERNAME;
$db_password   = DB_PASSWORD;

// TODO: DSN（Data Source Name）の設定
// ヒント: "{接続方式}:dbname={DB名};host={ホスト};port={ポート};charset=utf8;"
$dsn = "______:dbname=______;host=______;port=______;charset=utf8;";

// PDOオブジェクトの初期化
$pdo = null;

try {
    // TODO: PDO インスタンスを生成する
    // ヒント: new PDO(DSN文字列, ユーザー名, パスワード)
    $pdo = null;

    // TODO: 以下の2つの属性を設定する（コメントを外す）
    //   1. エラーモードを「例外を投げる」モードにする
    //   2. プリペアドステートメントのエミュレートを無効にする
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
    exit;
}
