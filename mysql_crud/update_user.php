<?php
require_once './env.php';
require_once './lib/Database.php';

// lib/Database を利用
use Lib\Database;

$id = $_GET['id'] ?? $_POST['id'] ?? null;
$user = null;
$status = null;
$error_message = null;

// 対象ユーザの情報を取得
if ($id) {
    $user = find($id);
}

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $display_name = $_POST['display_name'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($display_name)) {
        $status = 'error';
        $error_message = '表示名を入力してください。';
    } else {
        if (update($id, $display_name, $password)) {
            $status = 'success';
            // 表示用データを再取得
            $user = find($id);
        } else {
            $status = 'error';
            $error_message = '更新に失敗しました。';
        }
    }
}

/**
 * ユーザ情報をIDで検索する関数
 */
function find($id)
{
    try {
        $pdo = Database::getInstance();
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * ユーザ情報を更新する関数
 */
function update($id, $display_name, $password)
{
    try {
        $pdo = Database::getInstance();

        $params = [
            'id' => $id,
            'display_name' => $display_name
        ];

        if (!empty($password)) {
            // パスワードが入力されている場合
            $hash = password_hash($password, PASSWORD_DEFAULT);
            // TODO: display_name と password を更新する UPDATE 文
            // SET に2カラム、WHERE で id を絞る。値はすべてプレースホルダー
            $sql = "";
            $params['password'] = $hash;
        } else {
            // パスワードが入力されていない場合
            // TODO: display_name だけを更新する UPDATE 文
            // SET は1カラム、WHERE で id を絞る
            $sql = "";
        }
        // SQLを設定して、プリペアードステートメントを生成
        $stmt = $pdo->prepare($sql);
        // TODO: SQL実行して結果を返す
        // $stmt->execute($params);
        return null;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

$title = 'ユーザ情報更新 (UPDATE)';
$lesson_number = 10;
$description = '既存のレコードを書き換える「UPDATE（更新）」の実装です。表示名の変更や、オプションでのパスワード更新ロジックを確認しましょう。';
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

    <main class="max-w-2xl mx-auto px-6 py-12">

        <header class="mb-12">
            <div class="inline-block px-3 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-bold uppercase tracking-wider mb-4">
                Lesson <?= $lesson_number ?>
            </div>
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight"><?= $title ?></h2>
            <p class="text-lg text-slate-600"><?= $description ?></p>
        </header>

        <?php if (!$user && $id): ?>
            <div class="p-8 bg-white rounded-2xl border border-slate-200 text-center">
                <p class="text-slate-400 italic">指定されたユーザが見つかりませんでした (ID: <?= htmlspecialchars($id) ?>)</p>
            </div>
        <?php elseif (!$id): ?>
            <div class="p-8 bg-white rounded-2xl border border-slate-200 text-center">
                <p class="text-slate-400 italic">ユーザ一覧から編集したいユーザを選択してください</p>
                <a href="select_users.php" class="mt-4 inline-block text-sky-600 font-bold hover:underline">一覧へ行く &rarr;</a>
            </div>
        <?php else: ?>

            <!-- Notification -->
            <?php if ($status === 'success'): ?>
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-emerald-800 font-bold">ユーザ情報を更新しました</p>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-rose-800 font-bold"><?= htmlspecialchars($error_message) ?></p>
                </div>
            <?php endif; ?>

            <!-- User Info Card -->
            <div class="mb-8 rounded-2xl p-6 text-slate-900 shadow-xl relative overflow-hidden text-center md:text-left">
                <div class="absolute top-0 right-0 p-4 opacity-10 hidden md:block">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Editing User</p>
                    <h3 class="text-2xl font-black"><?= htmlspecialchars($user['display_name']) ?></h3>
                    <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-4 text-sm text-slate-300">
                        <div class="flex items-center gap-1">
                            <span class="text-slate-500 font-mono">ID:</span>
                            <span class="font-mono"><?= htmlspecialchars($user['id']) ?></span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-slate-500 font-mono">ACCOUNT:</span>
                            <span class="font-bold text-sky-400">@<?= htmlspecialchars($user['account_name']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Form -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <form action="" method="post" class="p-8 space-y-6">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                    <div class="space-y-2">
                        <label for="display_name" class="text-sm font-bold text-slate-700 ml-1">表示名</label>
                        <input type="text" name="display_name" id="display_name" required
                            value="<?= htmlspecialchars($user['display_name']) ?>"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-bold text-slate-700 ml-1">新しいパスワード <span class="text-slate-400 font-normal text-xs">(変更しない場合は空欄)</span></label>
                        <input type="password" name="password" id="password" placeholder="新しいパスワードを入力"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg transition shadow-lg shadow-sky-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.001 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            ユーザ情報を更新する
                        </button>
                    </div>
                </form>
            </section>
        <?php endif; ?>

        <footer class="pt-12 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"パスワードのような機密情報は、未入力時の扱い（変更しない）を適切に設計することが重要です。"</p>
        </footer>
    </main>

</body>

</html>