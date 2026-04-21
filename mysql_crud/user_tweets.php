<?php
require_once './env.php';
require_once './lib/Database.php';

// lib/Database を利用
use Lib\Database;

$user_id = $_GET['user_id'] ?? null;
$tweets = [];
$target_user = null;

if ($user_id) {
    $target_user = findUser($user_id);
    if ($target_user) {
        $tweets = getByUserID($user_id);
    }
}

/**
 * ユーザ情報を取得する関数
 */
function findUser($id)
{
    try {
        // DB接続
        $pdo = Database::getInstance();
        // SQL文（プレースホルダ）
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        // SQL実行：:id のパラメータを引数
        $stmt->execute(['id' => $id]);
        // データ取得（連想配列）
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

/**
 * ユーザIDからツイートを取得する関数
 */
function getByUserID($user_id, $limit = 20)
{
    try {
        // DB接続
        $pdo = Database::getInstance();
        // TODO : tweets と users をJOIN したSQL
        $sql = "";
        $sql = "SELECT 
                    tweets.id, 
                    users.display_name, 
                    users.account_name,
                    tweets.message, 
                    tweets.created_at
                FROM tweets
                JOIN users ON tweets.user_id = users.id
                WHERE tweets.user_id = :user_id
                ORDER BY tweets.created_at DESC
                LIMIT :limit";

        // SQL事前準備
        $stmt = $pdo->prepare($sql);
        // SQL実行：:user_id と :limit のパラメータを引数
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        // 実行
        $stmt->execute();
        // データ取得（連想配列）
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

$title = 'ユーザ投稿一覧 (JOIN)';
$lesson_number = 10;
$description = '複数のテーブルを結合してデータを取得する「JOIN」の活用例です。ユーザ情報と投稿（ツイート）情報を紐付けて表示する流れを確認しましょう。';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | PHP Samples</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-slate-50 text-slate-800 leading-relaxed antialiased">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-tight text-slate-900"><?= $title ?></h1>
            <a href="index.php" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">&larr; CRUDメニュー</a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 py-12">

        <header class="mb-12">
            <div class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-4">
                Lesson <?= $lesson_number ?>
            </div>
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight"><?= $title ?></h2>
            <p class="text-lg text-slate-600"><?= $description ?></p>
        </header>

        <!-- Search Section -->
        <section class="mb-12">
            <form action="" method="get" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-mono text-sm">ID:</span>
                    <input type="number" name="user_id" id="user_id" required value="<?= htmlspecialchars($user_id ?? '') ?>"
                        placeholder="ユーザIDを入力 (例: 1)"
                        class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none shadow-sm">
                </div>
                <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    表示
                </button>
            </form>
        </section>

        <?php if ($user_id): ?>
            <?php if ($target_user): ?>
                <!-- User Profile Header -->
                <div class="mb-8 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-inner">
                        <?= mb_substr($target_user['display_name'], 0, 1) ?>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($target_user['display_name']) ?></h3>
                        <p class="text-indigo-600 font-medium text-sm">@<?= htmlspecialchars($target_user['account_name']) ?></p>
                    </div>
                    <div class="ml-auto text-right border-l border-slate-100 pl-6 hidden sm:block">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Posts</p>
                        <p class="text-2xl font-black text-slate-700"><?= count($tweets) ?></p>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="space-y-4">
                    <?php if (empty($tweets)): ?>
                        <div class="py-12 text-center bg-slate-100 rounded-2xl border border-dashed border-slate-300">
                            <p class="text-slate-400 italic font-medium">まだ投稿がありません</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($tweets as $tweet): ?>
                            <article class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:border-indigo-200 transition-colors group">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-tighter bg-slate-50 px-2 py-0.5 rounded">Post #<?= $tweet['id'] ?></span>
                                    <time class="text-[11px] text-slate-400 font-medium"><?= date('Y/m/d H:i', strtotime($tweet['created_at'])) ?></time>
                                </div>
                                <p class="text-slate-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($tweet['message']) ?></p>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="py-12 text-center bg-rose-50 rounded-2xl border border-rose-100">
                    <p class="text-rose-600 font-bold">指定されたユーザ(ID: <?= htmlspecialchars($user_id) ?>)は見つかりませんでした</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="py-20 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <p class="text-slate-400 font-medium">ユーザIDを入力して、タイムラインを表示してください</p>
            </div>
        <?php endif; ?>

        <footer class="pt-12 mt-12 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"JOINを活用することで、関連するテーブルから必要な情報を一度のクエリで効率的に取得できます。"</p>
        </footer>
    </main>

</body>

</html>