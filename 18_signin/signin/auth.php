<?php
// 共通ファイル app.php を読み込み
require_once '../app.php';

use App\Models\AuthUser;
use App\Models\User;

// POSTリクエストでなければ何も表示しない
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// TODO: セッションにPOSTデータを登録
$_SESSION[APP_KEY]['signin'] = null;

// TODO: 入力されたアカウント名とパスワードを取得
$account_name = "";
$password = "";

// ユーザ認証: new User() で auth() を実行
$user = new User();
// アカウント名とパスワードを渡して、認証処理
$auth_user = $user->auth($account_name, $password);

if (empty($auth_user['id'])) {
    // エラーセッション
    $_SESSION[APP_KEY]['error'] = 'アカウント名またはパスワードが間違っています。';
    // ログイン失敗時はログイン入力画面にリダイレクト
    header('Location: ./input.php');
    exit;
} else {
    // TODO: 認証成功時はセッションにユーザデータを保存: APP_KEY の signin

    // ユーザトップページにリダイレクト: home/
    header('Location: ../home/');
    exit;
}
