<?php
require_once './env.php';
require_once './lib/Database.php';

// lib/Database を利用
use Lib\Database;

$user_id = null;
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: フォームの送信値を受け取る
    // $_POST['キー名'] ?? '' で各値を取得
    // キー: account_name, email, display_name, password
    $posts = [
        'account_name' => null,
        'email'        => null,
        'display_name' => null,
        'password'     => null,
    ];

    if (empty($posts['account_name']) || empty($posts['email']) || empty($posts['password'])) {
        $error_message = "必須項目が入力されていません。";
    } else {
        $result = insert($posts);
        if (is_numeric($result)) {
            $user_id = $result;
        } else {
            $error_message = $result;
        }
    }
}

/**
 * ユーザデータを登録する関数
 */
function insert($posts)
{
    try {
        // TODO: パスワードをハッシュ化
        // password_hash(元のパスワード, PASSWORD_DEFAULT)
        $posts['password'] = null;

        // DB接続
        $pdo = Database::getInstance();

        // TODO: INSERT文
        $sql = "";
        // $sql = "INSERT INTO users (account_name, email, display_name, password) VALUES (:account_name, :email, :display_name, :password)";

        // SQLを設定して、プリペアードステートメントを生成
        $stmt = $pdo->prepare($sql);

        // SQL実行
        $result = $stmt->execute($posts);

        if ($result) {
            // 登録した users.id を取得して返却
            return $pdo->lastInsertId();
        }
        return "登録処理に失敗しました。";
    } catch (PDOException $e) {
        // 一意制約違反などのエラーハンドリング
        if ($e->getCode() == 23000) {
            return "このアカウント名またはメールアドレスは既に登録されています。";
        }
        return "データベースエラー: " . $e->getMessage();
    }
}

$title = '新規ユーザ登録 (CREATE)';
$lesson_number = 10;
$description = 'フォームから入力されたデータをデータベースに保存する「CREATE（作成）」の実装です。パスワードのハッシュ化や、プリペアードステートメントによる安全な登録方法を学びましょう。';
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

        <!-- Notification -->
        <?php if ($user_id): ?>
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3 animate-in fade-in slide-in-from-top-4">
                <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-emerald-800 font-bold">ユーザの登録が完了しました</p>
                    <p class="text-emerald-600 text-sm mt-1">発行されたID: <span class="font-mono font-bold"><?= $user_id ?></span></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3 animate-in fade-in slide-in-from-top-4">
                <svg class="w-6 h-6 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-rose-800 font-bold"><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <form action="" method="post" class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="account_name" class="text-sm font-bold text-slate-700 ml-1">アカウント名 <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-mono">@</span>
                            <input type="text" name="account_name" id="account_name" required placeholder="username"
                                class="w-full pl-8 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="display_name" class="text-sm font-bold text-slate-700 ml-1">表示名 <span class="text-slate-400 font-normal text-xs">(未入力時はアカウント名)</span></label>
                        <input type="text" name="display_name" id="display_name" placeholder="山田 太郎"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-slate-700 ml-1">メールアドレス <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" required placeholder="your@email.com"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none">
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-sm font-bold text-slate-700 ml-1">パスワード <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg transition shadow-lg shadow-sky-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        この内容で登録する
                    </button>
                </div>
            </form>
        </section>

        <footer class="pt-12 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"パスワードは `password_hash` を使用して安全に保存され、データベース管理者でも元の文字列を知ることはできません。"</p>
        </footer>
    </main>

</body>

</html>