<?php
require_once './env.php';
require_once './lib/Database.php';

use Lib\Database;

$limit = 50; // デフォルトの件数
if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
    $limit = (int) $_GET['limit'];
}
$users = get($limit);

/**
 * ユーザデータを取得する関数
 */
function get($limit = 50)
{
    // データベースに接続
    $pdo = Database::getInstance();
    // SQLを用意する
    $sql = "SELECT * FROM users LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    // SQL実行
    $stmt->execute(['limit' => $limit]);
    // データをすべて取り出す
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ一覧取得 (READ) | PHP Samples</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-slate-50 text-slate-800 leading-relaxed antialiased">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-tight text-slate-900">ユーザ一覧取得 (READ)</h1>
            <a href="index.php" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">&larr; データベースメニュー</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-12">

        <header class="mb-12">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">ユーザ一覧取得 (READ)</h2>
            <p class="text-lg text-slate-600">データベースから情報を取得して画面に表示する「READ（読み取り）」の基本です。SELECT文の発行と、fetchAllによるデータ受け取りの流れをマスターしましょう。</p>
        </header>

        <!-- Stats & Action -->
        <div class="mb-6 flex justify-between items-end">
            <div>
                <?php if ($users): ?>
                    <p class="text-sm text-slate-500 font-medium">全 <span class="text-sky-600 font-bold"><?= count($users) ?></span> 名のユーザが見つかりました</p>
                <?php else: ?>
                    <p class="text-sm text-slate-500 font-medium">ユーザデータが存在しません</p>
                <?php endif; ?>
            </div>
            <div class="flex gap-3">
                <a href="insert_user.php" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-lg transition shadow-sm shadow-sky-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    新規ユーザ登録
                </a>
            </div>
        </div>

        <!-- Users Table -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-widest">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">アカウント名</th>
                            <th class="px-6 py-4">メールアドレス</th>
                            <th class="px-6 py-4">表示名</th>
                            <th class="px-6 py-4 text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">ユーザデータが存在しません</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400"><?= htmlspecialchars($user['id']) ?></td>
                                    <td class="px-6 py-4">
                                        <a href="find_user.php?id=<?= $user['id'] ?>" class="font-bold text-slate-700 hover:text-sky-600 hover:underline transition-colors group-hover:text-sky-600">
                                            @<?= htmlspecialchars($user['account_name']) ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md"><?= htmlspecialchars($user['display_name']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="update_user.php?id=<?= $user['id'] ?>" class="p-1 text-slate-400 hover:text-sky-600 transition" title="編集">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="delete_user.php" method="post" class="inline" onsubmit="return confirm('本当にこのユーザを削除しますか？');">
                                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="削除">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h14"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="pt-12 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"大量のデータを扱う場合は、LIMIT句やページネーション（分かち書き）の検討が必要です。"</p>
        </footer>
    </main>

</body>

</html>