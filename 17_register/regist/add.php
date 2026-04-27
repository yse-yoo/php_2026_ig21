<?php
// 共通ファイル app.php を読み込み
require_once '../app.php';

// Userモデルをインポート
use App\Models\User;

// POSTリクエストでなければ何も表示しない
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// POSTデータ取得
$posts = $_POST;
// TODO: サニタイズ
$posts = sanitize($_POST);

// TODO: セッションの APP_KEY 下の regist にPOSTデータを保存
$_SESSION[APP_KEY]['regist'] = $posts;

// ユーザが存在するかチェック
$user = new User();
$user_exists = $user->findForExists($posts);
if (!empty($user_exists['id'])) {
    // ユーザが既に存在する場合はエラーメッセージをセッションに保存
    $_SESSION[APP_KEY]['errors']['public'] = 'このアカウント名は既に使用されています。';
    header('Location: input.php');
    exit;
}

// User クラスのインスタンスを生成
$user = new User();
// TODO: User モデルの insert() を使ってユーザを登録
$user_id = $user->insert($posts);

if (empty($user_id)) {
    // エラーメッセージをセッションに保存
    $_SESSION[APP_KEY]['errors']['public'] = 'ユーザ登録に失敗しました。';
    // ユーザ登録に失敗したとき、ログイン入力画面にリダイレクト
    header('Location: input.php');
    exit;
} else {
    // ユーザ登録に成功したとき

    // 結果ページにリダイレクト: result.php
    header('Location: result.php');
    exit;
}
