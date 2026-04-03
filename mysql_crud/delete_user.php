<?php
require_once './env.php';
require_once './lib/Database.php';

// lib/Database を利用
use Lib\Database;

// POSTリクエスト以外、またはIDがない場合は一覧へ戻す
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header('Location: select_users.php');
    exit;
}

// TODO: POSTからIDを取得
$id = null;
// TODO: delete関数を実行
$result = delete($id);

// 完了したら一覧画面へリダイレクト
header('Location: select_users.php');
exit;

/**
 * ユーザデータを削除する関数
 */
function delete($id)
{
    try {
        // DB接続
        $pdo = Database::getInstance();
        // TODO: SQL作成: 指定した id で検索してレコードを削除
        $sql = "";
        $stmt = $pdo->prepare($sql);
        // TODO: SQL実行して結果を返す
        // $stmt->execute() に ['id' => $id] を渡す
        return null;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}
