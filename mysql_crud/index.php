<?php
$menu_items = [
    [
        'category' => '初期セットアップ & 接続',
        'items' => [
            ['name' => 'create_database.php', 'label' => 'DB & テーブル作成', 'note' => 'データベース環境の初期構築'],
            ['name' => 'connect_test.php', 'label' => '基本接続テスト', 'note' => 'PDOによるシンプルな接続確認'],
            ['name' => 'connect_test_for_module.php', 'label' => 'モジュール接続テスト', 'note' => 'Databaseクラス経由での接続確認'],
        ]
    ],
    [
        'category' => 'データ操作 (基本)',
        'items' => [
            ['name' => 'select_users.php', 'label' => 'ユーザ一覧表示', 'note' => 'SELECT：全件・件数限定での取得'],
            ['name' => 'find_user.php', 'label' => 'ユーザ詳細検索', 'note' => 'SELECT/WHERE：特定IDによる1件取得'],
            ['name' => 'insert_user.php', 'label' => '新規ユーザ登録', 'note' => 'INSERT：データの新規挿入とハッシュ化'],
            ['name' => 'update_user.php', 'label' => 'ユーザ情報更新', 'note' => 'UPDATE：既存データの書き換え'],
            ['name' => 'delete_user.php', 'label' => 'ユーザ削除処理', 'note' => 'DELETE：特定レコードの物理削除'],
        ]
    ],
    [
        'category' => '高度な操作',
        'items' => [
            ['name' => 'user_tweets.php', 'label' => 'ユーザ投稿一覧', 'note' => 'JOIN：複数テーブルの結合'],
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL データベース操作 | PHP Samples</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-slate-50 text-slate-800 leading-relaxed antialiased">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-tight text-slate-900">MySQL データベース操作</h1>
            <a href="../index.php" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">&larr; ダッシュボード</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-12">

        <header class="mb-12">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">MySQL データベース操作</h2>
            <p class="text-lg text-slate-600 max-w-3xl">データベースのセットアップから、基本のCRUD操作、テーブル結合までを網羅したサンプルメニューです。</p>
        </header>

        <div class="space-y-12">
            <?php foreach ($menu_items as $section): ?>
                <section>
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                        <?= $section['category'] ?>
                        <span class="flex-1 h-px bg-slate-200"></span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($section['items'] as $item): ?>
                            <a href="<?= $item['name'] ?>" class="group block bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:border-indigo-300 hover:-translate-y-1 transition-all duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="p-2 bg-slate-50 group-hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-6 h-6 text-slate-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-mono font-bold text-slate-300 group-hover:text-indigo-300 uppercase tracking-tighter"><?= $item['name'] ?></span>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 mb-2"><?= $item['label'] ?></h4>
                                <p class="text-sm text-slate-500 line-clamp-2"><?= $item['note'] ?></p>
                                <div class="mt-6 flex items-center text-xs font-bold text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    サンプルを見る
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>

        <footer class="pt-12 mt-20 border-t border-slate-200 text-center">
            <p class="text-slate-500 text-sm italic">"データベース設計と正しい操作は、スケーラブルなWebアプリ開発の要です。"</p>
        </footer>
    </main>

</body>

</html>