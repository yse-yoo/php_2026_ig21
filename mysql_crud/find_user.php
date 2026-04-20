<?php
require_once './env.php';
require_once './lib/Database.php';

// lib/Database を利用
use Lib\Database;

$user_id = $_GET['id'] ?? null;
$user = null;

if ($user_id) {
    $user = find($user_id);
}

$user_id_injection = $_GET['id_injection'] ?? null;
if ($user_id_injection) {
    $user = findInjection($user_id_injection);
}

/**
 * ユーザデータを取得する関数
 */
function find($id)
{
    try {
        // DB接続
        $pdo = Database::getInstance();

        // TODO : users テーブルから id が一致する1件を取得する SELECT 文
        // WHERE 条件の値にはプレースホルダー :id を使う
        $sql = "SELECT * FROM users WHERE id = :id";

        // SQL事前準備
        $stmt = $pdo->prepare($sql);

        // TODO : execute() の引数に ['id' => $id] を渡してプレースホルダーをバインドする
        // $stmt->execute(...);

        // TODO : 1件を連想配列で取得して return してください
        //   ヒント: fetch(PDO::FETCH_ASSOC)
        return null;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

/**
 * ユーザデータを取得する関数（SQLインジェクション対策なし）
 */
function findInjection($id)
{
    try {
        // DB接続
        $pdo = Database::getInstance();
        // SQL文（プレースホルダ）
        $sql = "SELECT * FROM users WHERE id = $id;";
        $stmt = $pdo->query($sql);
        // データ取得（連想配列）
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

$title = 'ユーザ詳細検索 (FIND)';
$lesson_number = 10;
$description = '特定のIDを指定して1件のデータを取得する「FIND」の実装です。プリペアードステートメントによるSQLインジェクション対策の重要性を学びましょう。';
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
            <a href="select_users.php" class="text-sm font-semibold text-sky-600 hover:text-sky-700 transition">&larr; ユーザ一覧へ戻る</a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 py-12">

        <header class="mb-12">
            <div class="inline-block px-3 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-bold uppercase tracking-wider mb-4">
                Lesson <?= $lesson_number ?>
            </div>
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight"><?= $title ?></h2>
            <p class="text-lg text-slate-600"><?= $description ?></p>
        </header>

        <!-- Search Form -->
        <section class="mb-12">
            <form action="" method="get" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-mono text-sm">ID:</span>
                    <input type="number" name="id" id="id" required value="<?= htmlspecialchars($user_id ?? '') ?>"
                        placeholder="ユーザIDを入力"
                        class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none shadow-sm font-mono">
                </div>
                <button type="submit" class="px-8 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    検索
                </button>
            </form>
        </section>

        <?php if ($user): ?>
            <!-- Result Card -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 bg-slate-900 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sky-400 text-xs font-bold uppercase tracking-[0.2em] mb-2">User Profile</p>
                            <h3 class="text-3xl font-black"><?= htmlspecialchars($user['display_name']) ?></h3>
                            <p class="text-slate-400 mt-1 font-mono">@<?= htmlspecialchars($user['account_name']) ?></p>
                        </div>
                        <div class="bg-white/10 px-3 py-1 rounded-lg border border-white/10">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">User ID</span>
                            <span class="font-mono font-bold text-lg leading-none"><?= htmlspecialchars($user['id']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Email Address</label>
                            <p class="text-slate-700 font-medium"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Password (Hash)</label>
                            <p class="text-slate-500 font-mono text-xs break-all bg-slate-50 p-2 rounded border border-slate-100"><?= htmlspecialchars($user['password']) ?></p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Created At</label>
                            <p class="text-slate-600 text-sm"><?= htmlspecialchars($user['created_at']) ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Updated At</label>
                            <p class="text-slate-600 text-sm"><?= htmlspecialchars($user['updated_at']) ?></p>
                        </div>
                    </div>

                    <div class="pt-6 flex gap-3">
                        <a href="update_user.php?id=<?= $user['id'] ?>" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-center text-sm">編集する</a>
                        <a href="user_tweets.php?user_id=<?= $user['id'] ?>" class="flex-1 py-3 bg-sky-50 hover:bg-sky-100 text-sky-600 font-bold rounded-xl transition text-center text-sm">投稿を見る</a>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <div class="py-12 text-center bg-rose-50 rounded-2xl border border-rose-100">
                <p class="text-rose-600 font-bold">ユーザは見つかりませんでした</p>
            </div>
        <?php endif; ?>

        <!-- Security Education -->
        <section class="mt-8 px-8 py-4 bg-amber-50 rounded-2xl border border-amber-200">
            <h4 class="text-amber-900 font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                SQLインジェクションの脅威
            </h4>
            <p class="text-sm text-amber-800 leading-relaxed mb-4">
                もしプリペアードステートメントを使わずに、入力をそのままSQLに埋め込んでいた場合、以下のような悪意ある文字列を入力することでデータを盗み見られる可能性があります。
            </p>

            <h5 class="text-sm text-amber-800 font-bold mb-2">悪意ある文字列</h5>
            <div class="bg-white p-2 rounded border border-amber-200 font-mono text-xs text-rose-600 my-2">
                '' OR 1=1;--
            </div>

            <h5 class="text-sm text-amber-800 font-bold mb-2">SQL</h5>
            <div class="bg-white p-2 rounded border border-amber-200 font-mono text-xs text-rose-600 my-2">
                SELECT * FROM users WHERE id = '' OR 1=1;--
            </div>
        </section>

        <section class="my-2">
            <form action="" method="get" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-mono text-sm">ID:</span>
                    <input type="text" name="id_injection" id="id_injection" required value="'' OR 1=1;--"
                        placeholder="ユーザIDを入力"
                        class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none shadow-sm font-mono">
                </div>
                <button type="submit" class="px-8 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    検索
                </button>
            </form>
        </section>

        <footer class="pt-12 mt-12 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"一件取得の処理は、詳細画面やマイページ、ログイン処理などで頻繁に使用されます。"</p>
        </footer>
    </main>

</body>

</html>