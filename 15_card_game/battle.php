<?php
// GameServiceクラスを読み込む
require_once __DIR__ . '/services/GameService.php';

// セッションを開始する
// session_start();

// CSRFトークンを生成する
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ゲームサービスのインスタンスを作成する
$game = new GameService();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($cardId = $_GET['card_id'] ?? '')) {
    // TODO: GETで受け取った card_id を使ってプレイヤーを初期化: setupPlayer(カードID)
}

// TODO: プレイヤーカードが未選択なら card_list.php へリダイレクトする
if (!$game->player) {
    // header('Location: card_list.php');
    // exit;
}

// TODO: 敵カードを初期化する
// $game->setupEnemy();

// POST時にCSRFトークンを確認して、action を実行する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (is_string($postedToken) && is_string($sessionToken) && hash_equals($sessionToken, $postedToken)) {
        // アクションを実行: handleAction(アクション)
        $game->handleAction($_POST['action'] ?? '');
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        // CSRFトークンが無効な場合の処理: メッセージをセットする
        $game->message = '不正なリクエストです。画面を開き直して操作してください。';
    }
}

// ビュー用変数
// TODO: Player カードをビュー用変数に代入する
$player = $game->player;
// TODO: Enemy カードをビュー用変数に代入する
$enemy = $game->enemy;
// TODO: メッセージをビュー用変数に代入する
$message = $game->message ?? 'TODO を完成させると、ここにバトルログが表示されます。';
// TODO: 勝利判定の結果を代入する
$isWin = $game->isWin() ?? false;
// TODO: ゲームオーバー判定の結果を代入する
$isGameOver = $game->isGameOver() ?? false;
// 終了
$isFinished = $isWin || $isGameOver;
// TODO: セッションからCSRFトークンを取得する
$csrfToken = $_SESSION['csrf_token'] ?? '';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アクション実行</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/game.css">
</head>

<body class="bg-slate-400 text-slate-100 min-h-screen">
    <main class="max-w-5xl mx-auto px-4 py-4">
        <div class="text-center mb-2">
            <h1 class="text-4xl font-game font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-400 tracking-widest uppercase mb-4">Battle Simulation</h1>
        </div>

        <!-- アクションログ -->
        <div class="max-w-2xl mx-auto bg-slate-900/80 rounded-xl p-4 my-2 border border-slate-700 shadow-inner min-h-[4rem] flex items-center justify-center">
            <p class="text-base font-bold text-center text-slate-200 leading-relaxed"><?= nl2br($message) ?></p>
        </div>

        <?php if ($isFinished): ?>
            <div class="text-center my-4">
                <p class="inline-block px-4 py-2 rounded-full font-game font-bold <?= $isWin ? 'bg-emerald-600/90 text-white' : 'bg-rose-600/90 text-white' ?>">
                    <?= $isWin ? 'YOU WIN' : 'YOU LOSE' ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- 操作パネル -->
        <form method="POST" class="flex flex-wrap justify-center gap-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" name="action" value="attack" class="px-8 py-3 bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 rounded-lg font-game font-bold shadow-lg shadow-rose-900/40 transition-all active:scale-95 border-b-4 border-rose-900 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:from-rose-600 disabled:hover:to-rose-700" <?= (!$player || !$enemy || $isFinished) ? 'disabled' : '' ?>>
                ATTACK
            </button>
            <button type="submit" name="action" value="special" class="px-8 py-3 bg-gradient-to-r from-sky-600 to-sky-700 hover:from-sky-500 hover:to-sky-600 rounded-lg font-game font-bold shadow-lg shadow-sky-900/40 transition-all active:scale-95 border-b-4 border-sky-900 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:from-sky-600 disabled:hover:to-sky-700" <?= (!$player || !$enemy || $isFinished) ? 'disabled' : '' ?>>
                SPECIAL SKILL
            </button>
            <button type="submit" name="action" value="level_up" class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 rounded-lg font-game font-bold shadow-lg shadow-orange-900/40 transition-all active:scale-95 border-b-4 border-orange-900 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:from-amber-500 disabled:hover:to-orange-600" <?= (!$player || !$enemy || $isFinished) ? 'disabled' : '' ?>>
                LEVEL UP
            </button>
            <a href="card_list.php" class="px-8 py-3 bg-slate-700 hover:bg-slate-600 rounded-lg font-game font-bold shadow-lg shadow-slate-900/40 transition-all active:scale-95 border-b-4 border-slate-900 text-center">
                CARD LIST
            </a>
        </form>

        <!-- カード -->
        <div class="flex flex-col md:flex-row justify-center items-start gap-6 my-2">
            <!-- プレイヤーカード -->
            <div class="tcg-card player-card rounded-2xl p-4 w-full max-w-xs shadow-xl">
                <h2 class="text-sm font-game font-bold mb-3 text-sky-400 border-b border-sky-400/30 pb-1">Player Unit</h2>
                <?php if ($player): ?>
                    <div class="mt-3 bg-slate-900 rounded-full h-3 overflow-hidden border border-slate-700">
                        <div class="hp-bar bg-sky-500 h-full" style="width: <?= ($player->hp / $player->maxHp) * 100 ?>%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] font-game mt-1 px-1 text-slate-400">
                        <span>HP: <?= $player->hp ?> / <?= $player->maxHp ?></span>
                        <span>EXP: <?= $player->exp ?></span>
                    </div>
                    <?php
                    $card = $player;
                    include 'views/card.php';
                    ?>
                <?php else: ?>
                    <p class="py-20 text-center text-slate-400 text-sm">TODO を完成させるとプレイヤーカードが表示されます。</p>
                <?php endif; ?>
            </div>

            <!-- ログエリア（デスクトップでは中央、モバイルでは間） -->
            <div class="hidden md:flex flex-col justify-center items-center w-32 self-stretch">
                <div class="h-full w-px bg-gradient-to-b from-transparent via-slate-700 to-transparent"></div>
                <div class="py-4 text-slate-500 font-game text-xs uppercase tracking-widest [writing-mode:vertical-lr]">Battle Log</div>
                <div class="h-full w-px bg-gradient-to-b from-transparent via-slate-700 to-transparent"></div>
            </div>

            <!-- エネミーカード -->
            <div class="tcg-card enemy-card rounded-2xl p-4 w-full max-w-xs shadow-xl">
                <h2 class="text-sm font-game font-bold mb-3 text-rose-400 border-b border-rose-400/30 pb-1">Enemy Unit</h2>
                <?php if ($enemy): ?>
                    <div class="mt-3 bg-slate-900 rounded-full h-3 overflow-hidden border border-slate-700">
                        <div class="hp-bar bg-rose-500 h-full" style="width: <?= ($enemy->hp / $enemy->maxHp) * 100 ?>%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] font-game mt-1 px-1 text-slate-400">
                        <span>HP: <?= $enemy->hp ?> / <?= $enemy->maxHp ?></span>
                    </div>
                    <?php
                    $card = $enemy;
                    include 'views/card.php';
                    ?>
                <?php else: ?>
                    <p class="py-20 text-center text-slate-400 text-sm">TODO を完成させるとエネミーカードが表示されます。</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>