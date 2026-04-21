<?php
// 共通アプリファイル読み込み
require_once "../app.php";

// セッション APP_KEY の regist があれば削除
if (isset($_SESSION[APP_KEY]['regist'])) {
    unset($_SESSION[APP_KEY]['regist']);
}

// セッション APP_KEY の errors があれば削除
if (isset($_SESSION[APP_KEY]['errors'])) {
    unset($_SESSION[APP_KEY]['errors']);
}
?>

<!DOCTYPE html>
<html lang="ja">

<!-- components/head.php を読み込み -->
<?php include COMPONENT_DIR . 'head.php'; ?>

<body class="bg-sky-50 min-h-screen">
    <?php include COMPONENT_DIR . 'nav.php'; ?>

    <main class="flex flex-col justify-center items-center min-h-screen px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">

            <!-- ロゴ・タイトル -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-sky-100 mb-4">
                    <svg class="w-7 h-7 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-sky-600 tracking-wide">登録完了</h2>
                <p class="text-sm text-gray-400 mt-1">
                    アカウント登録が完了しました。
                </p>

                <div class="mt-6 space-y-2 text-center">
                    <a href="./" class="text-sky-500 font-semibold hover:text-sky-700 hover:underline transition">
                        トップページへ
                    </a>
                </div>
            </div>

        </div>
    </main>
</body>

</html>