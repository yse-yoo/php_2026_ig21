<?php
$menus = [
    [
        'title' => 'Instance Profile',
        'description' => 'BaseCard のインスタンス内容を確認する。',
        'href' => 'instance.php',
        'badge' => 'Study',
        'colors' => 'from-sky-500 to-cyan-500',
    ],
    [
        'title' => 'Card Battle',
        'description' => 'カードを選んでバトルを開始する。',
        'href' => 'card_list.php',
        'badge' => 'Game',
        'colors' => 'from-rose-500 to-orange-500',
    ],
];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カードゲームメニュー</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/game.css">
</head>

<body class="bg-slate-400 text-slate-100 min-h-screen">
    <main class="max-w-5xl mx-auto px-4 py-10">
        <section class="animate-in fade-in zoom-in duration-700">
            <div class="text-center mb-6">
                <h1 class="text-4xl font-game font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-400 tracking-[0.2em] uppercase mb-4">Card Game Hub</h1>
                <p class="text-slate-200 max-w-2xl mx-auto">学習用のインスタンス確認画面と、カードバトル画面への入口です。</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($menus as $menu): ?>
                    <a href="<?= htmlspecialchars($menu['href'], ENT_QUOTES, 'UTF-8') ?>" class="group tcg-card rounded-2xl p-6 hover:-translate-y-4 hover:scale-[1.02] duration-500 shadow-2xl shadow-black/50">
                        <div class="flex items-start justify-between gap-4 mb-8">
                            <div>
                                <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-game uppercase tracking-[0.2em] bg-slate-900/70 text-slate-200 border border-slate-700">
                                    <?= htmlspecialchars($menu['badge'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <h2 class="mt-4 text-3xl font-game font-black text-white uppercase tracking-wider">
                                    <?= htmlspecialchars($menu['title'], ENT_QUOTES, 'UTF-8') ?>
                                </h2>
                            </div>
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br <?= $menu['colors'] ?> opacity-90 shadow-lg"></div>
                        </div>

                        <p class="text-slate-300 leading-relaxed min-h-[3rem]">
                            <?= htmlspecialchars($menu['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="mt-8 flex items-center justify-between border-t border-slate-700/70 pt-4">
                            <span class="text-sm font-game text-sky-300 uppercase tracking-[0.18em]">Open</span>
                            <span class="text-2xl text-slate-300 group-hover:text-white transition-colors">→</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>

</html>
