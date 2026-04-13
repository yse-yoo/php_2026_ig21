<?php

/**
 * =========================================================
 * MySQL 接続テスト
 * =========================================================
 *
 * 【目標】PDO を使って MySQL に接続し、接続状態を画面に表示する。
 *
 * 【手順】
 *   1. 未完成のプログラムを記述
 *   2. ブラウザでこのファイルにアクセスして結果を確認
 *   3. 「接続成功」と緑色で表示されれば完成
 *
 * 【ヒント】
 *   - 設定定数は env.php に定義されています（DB_HOST, DB_PORT など）
 *   - PDO の DSN 形式: "mysql:dbname=DB名;host=ホスト;port=ポート;charset=utf8;"
 *   - PDO インスタンス生成: new PDO($dsn, $user, $password)
 * =========================================================
 */

// TODO: require_once を使って './db.php' を読み込む
require_once 'db.php';

// 接続成功かどうかを判定する
$is_connected = isset($pdo) && $pdo instanceof PDO;

// 表示用の変数
$status_color = $is_connected ? 'emerald' : 'rose';
$status_text  = $is_connected ? '接続成功' : '接続失敗';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL接続テスト）| PHP Samples</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/app.css">
</head>

<body class="bg-slate-50 text-slate-800 leading-relaxed antialiased">

    <main class="max-w-4xl mx-auto px-6 py-12">

        <header class="mb-12">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">MySQL 接続テスト</h2>
            <p class="text-lg text-slate-600">PDO を使って MySQL に接続し、接続状態を確認してみましょう。</p>
        </header>

        <!-- 穴埋めチェックリスト -->
        <section class="mb-10">
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                <h3 class="font-bold text-amber-800 mb-3">穴埋めチェックリスト</h3>
                <ol class="list-decimal list-inside space-y-1 text-sm text-amber-700">
                    <li>(1) <code class="bg-amber-100 px-1 rounded">require_once</code> で設定ファイルを読み込む</li>
                    <li>(2) <code class="bg-amber-100 px-1 rounded">$dsn</code> に DSN 文字列を組み立てる</li>
                    <li>(3) <code class="bg-amber-100 px-1 rounded">new PDO(...)</code> でインスタンスを生成する</li>
                    <li>(4) <code class="bg-amber-100 px-1 rounded">setAttribute</code> でエラーモードを設定する</li>
                    <li>(5) <code class="bg-amber-100 px-1 rounded">instanceof</code> で接続成否を判定する</li>
                </ol>
            </div>
        </section>

        <!-- 接続ステータス -->
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
                        <h3 class="text-2xl font-bold text-slate-900 mb-1">データベース接続ステータス</h3>
                        <p class="text-<?= $status_color ?>-600 font-bold flex items-center justify-center md:justify-start gap-2">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-<?= $status_color ?>-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-<?= $status_color ?>-500"></span>
                            </span>
                            <?= $status_text ?>
                        </p>
                        <?php if (!$is_connected && isset($error_message)): ?>
                            <p class="mt-2 text-xs text-rose-500 font-mono"><?= htmlspecialchars($error_message) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- 構成情報 -->
        <section class="mb-12">
            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                構成情報 (env.php)
            </h3>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">設定項目</th>
                            <th class="px-6 py-4">現在の値</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $configs = [
                            'DB_CONNECTION' => $db_connection ?? '(未設定)',
                            'DB_HOST'       => $db_host       ?? '(未設定)',
                            'DB_PORT'       => $db_port       ?? '(未設定)',
                            'DB_DATABASE'   => $db_name       ?? '(未設定)',
                            'DB_USERNAME'   => $db_user       ?? '(未設定)',
                            'DB_PASSWORD'   => '********',
                        ];
                        foreach ($configs as $key => $val): ?>
                            <tr>
                                <td class="px-6 py-4 font-mono font-bold text-slate-500 uppercase"><?= $key ?></td>
                                <td class="px-6 py-4 font-mono text-sky-600"><?= htmlspecialchars($val) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- PDO オブジェクト詳細 -->
        <section class="mb-12">
            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                PDO オブジェクト詳細
            </h3>
            <div class="bg-slate-900 rounded-2xl p-6 shadow-lg overflow-x-auto">
                <pre class="text-emerald-400 font-mono text-xs leading-relaxed"><?php var_dump($pdo); ?></pre>
            </div>
        </section>

        <footer class="pt-12 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"PDOを使うと、データベースの種類に依存しない抽象化されたアクセスが可能になります。"</p>
        </footer>

    </main>
</body>

</html>