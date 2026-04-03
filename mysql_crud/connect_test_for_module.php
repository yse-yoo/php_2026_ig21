<?php
// connect_test_for_module.php
// env.php ファイルの読み込み
require_once './env.php';
// Database.php ファイルの読み込み
require_once './lib/Database.php';

use Lib\Database;

// PDOインスタンスを取得
try {
    // TODO: Database::getInstance() を呼び出して PDO インスタンスを取得する
    $pdo = null;
    $is_connected = $pdo instanceof PDO;
} catch (Exception $e) {
    $is_connected = false;
    $error_message = $e->getMessage();
}

$status_color = $is_connected ? 'emerald' : 'rose';
$status_text = $is_connected ? '接続成功 (シングルトン)' : '接続失敗';

$title = 'Databaseモジュール 接続テスト';
$lesson_number = 10;
$description = '自作の Database クラス（シングルトンパターン）を使用して、MySQL への接続を確認します。モジュール化された設計での動作をチェックしましょう。';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | PHP Samples</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/app.css">
</head>

<body class="bg-slate-50 text-slate-800 leading-relaxed antialiased">

    <?php include 'components/nav.php'; ?>

    <main class="max-w-4xl mx-auto px-6 py-12">

        <header class="mb-12">
            <div class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-4">
                Lesson <?= $lesson_number ?>
            </div>
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight"><?= $title ?></h2>
            <p class="text-lg text-slate-600"><?= $description ?></p>
        </header>

        <!-- Status Card -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-8 flex flex-col md:flex-row items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-<?= $status_color ?>-100 flex items-center justify-center flex-shrink-0">
                        <?php if ($is_connected): ?>
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        <?php else: ?>
                            <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-2xl font-bold text-slate-900 mb-1">モジュール経由の接続ステータス</h3>
                        <p class="text-<?= $status_color ?>-600 font-bold flex items-center justify-center md:justify-start gap-2">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-<?= $status_color ?>-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-<?= $status_color ?>-500"></span>
                            </span>
                            <?= $status_text ?>
                        </p>
                        <?php if (isset($error_message)): ?>
                            <p class="mt-2 text-sm text-rose-500 font-mono"><?= htmlspecialchars($error_message) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Connection Details -->
        <section class="mb-12">
            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                構成情報 (env.php 定数)
            </h3>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">定数名</th>
                            <th class="px-6 py-4">現在の値</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $configs = [
                            'DB_CONNECTION' => DB_CONNECTION,
                            'DB_HOST'       => DB_HOST,
                            'DB_PORT'       => DB_PORT,
                            'DB_DATABASE'   => DB_DATABASE,
                            'DB_USERNAME'   => DB_USERNAME,
                            'DB_PASSWORD'   => '********'
                        ];
                        foreach ($configs as $key => $val): ?>
                            <tr>
                                <td class="px-6 py-4 font-mono font-bold text-slate-500 uppercase"><?= $key ?></td>
                                <td class="px-6 py-4 font-mono text-indigo-600"><?= htmlspecialchars($val) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- PDO Object Info -->
        <section class="mb-12">
            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                PDO オブジェクト詳細 (Database::getInstance)
            </h3>
            <div class="bg-slate-900 rounded-2xl p-6 shadow-lg overflow-x-auto">
                <pre class="text-emerald-400 font-mono text-xs leading-relaxed"><?php var_dump($pdo); ?></pre>
            </div>
        </section>

        <footer class="pt-12 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"Databaseクラスとしてラップすることで、接続情報の管理やエラーハンドリングを一箇所に集約できます。"</p>
        </footer>
    </main>

</body>

</html>