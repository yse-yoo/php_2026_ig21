<?php
// TODO: モデルクラス models/BaseCard.php を読み込む
require_once 'models/BaseCard.php';

// // TODO: BaseCard クラスのインスタンスを作成
$card = new BaseCard(
    '水の精霊',
    18,
    25,
    100,
    3,
    '水',
    'AquaCard.png',
    'ハイドロポンプ',
    35
);

// TODO: インスタンス $card のプロパティを配列に格納
$stats = [
    'Name' => $card->name,
    'Level' => $card->level,
    'HP' => $card->hp,
    'MP' => $card->mp,
    'Attack' => $card->attack,
    'Defense' => $card->defense,
    'Element' => $card->element,
    'Experience' => $card->exp,
    'Skill' => $card->specialSkill,
    'Skill Power' => $card->specialSkillPower,
];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>インスタンスの確認</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/game.css">
</head>

<body class="bg-slate-400 text-slate-100 min-h-screen">
    <main class="max-w-4xl mx-auto px-4 py-10">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-game font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-400 tracking-widest uppercase mb-4">Instance Profile</h1>
            <p class="text-slate-300">クラスから生成された「実体（インスタンス）」の状態を確認します。</p>
        </div>

        <div class="flex flex-col md:flex-row gap-8 items-center md:items-stretch justify-center">
            <!-- 左側：カードビジュアル -->
            <div class="w-64 flex-shrink-0">
                <div class="tcg-card rounded-2xl p-2 shadow-2xl">
                    <!-- TODO: views/card.php を読み込む -->
                </div>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="./" class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-md">戻る</a>
                </div>
            </div>

            <!-- 右側：詳細ステータス -->
            <div class="flex-1 bg-slate-900/80 rounded-2xl p-6 border border-slate-700 shadow-xl">
                <div class="flex justify-between items-center mb-6 border-b border-slate-700 pb-4">
                    <h2 class="text-xl font-game font-bold text-sky-400">Object Details</h2>
                    <span class="px-3 py-1 bg-sky-900/50 text-sky-300 border border-sky-700 rounded-full text-[10px] font-game">
                        <?php if (!empty($card)): ?>
                            Class: <?= get_class($card) ?>
                        <?php else: ?>
                            Class: Undefined
                        <?php endif; ?>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($stats as $label => $value): ?>
                        <?php if (!empty($value)): ?>
                            <div class="bg-slate-800/50 p-3 rounded border border-slate-700/50">
                                <p class="text-[9px] font-game text-slate-500 uppercase mb-1 tracking-tighter"><?= $label ?></p>
                                <p class="text-sm font-bold text-slate-200"><?= $value ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>