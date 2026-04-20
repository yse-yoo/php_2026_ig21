<?php
require_once './env.php';
require_once './lib/Database.php';

use Lib\Database;

$users = get();

/**
 * ユーザデータを取得する関数
 */
function get($limit = 50)
{
    // DB接続（シングルトン）
    $pdo = Database::getInstance();

    // SQL（LIMITにプレースホルダー）
    $sql = "SELECT * FROM users LIMIT :limit";

    // プリペアドステートメント作成
    $stmt = $pdo->prepare($sql);

    // プレースホルダーに値をバインド（整数として扱う）
    // $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

    // 実行
    $stmt->execute(['limit' => $limit]);

    // 全件取得
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $users;
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
            <p class="text-lg text-slate-600">データベースから情報を取得して画面に表示する「READ（読み取り）」の基本です。</p>
        </header>

        <!-- 件数表示 -->
        <div class="mb-6 flex justify-between items-end">
            <div>
                <?php if ($users): ?>
                    <p class="text-sm text-slate-500 font-medium">
                        全 <span class="text-sky-600 font-bold"><?= count($users) ?></span> 名のユーザが見つかりました
                    </p>
                <?php else: ?>
                    <p class="text-sm text-slate-500 font-medium">ユーザデータが存在しません</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- テーブル -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-bold">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">アカウント名</th>
                            <th class="px-6 py-4">メール</th>
                            <th class="px-6 py-4">表示名</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    データなし
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b">
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['id']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['account_name']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($user['display_name']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>