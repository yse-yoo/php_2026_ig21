<?php
// 共通アプリファイル読み込み
require_once "../app.php";

// TODO: 前回の入力値を復元: signin
$form = null;

// TODO: エラーメッセージを復元: error
$error = null;
// TODO: フラッシュメッセージとして削除
?>

<!DOCTYPE html>
<html lang="ja">

<?php include COMPONENT_DIR . 'head.php' ?>

<body class="bg-sky-50 min-h-screen">
    <?php include COMPONENT_DIR . 'nav.php'; ?>

    <main class="flex flex-col justify-center items-center min-h-[calc(100vh-64px)] px-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">

            <!-- ロゴ・タイトル -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-sky-100 mb-4">
                    <svg class="w-7 h-7 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-sky-600 tracking-wide">Sign in</h2>
                <p class="text-sm text-gray-400 mt-1">アカウントにログインしてください</p>
            </div>

            <!-- エラーメッセージ -->
            <?php include COMPONENT_DIR . 'error_message.php'; ?>

            <form action="signin/auth.php" method="post" class="space-y-5">

                <!-- メールアドレス -->
                <div class="relative">
                    <input type="text" name="account_name" id="account_name"
                        class="block w-full px-4 pb-2.5 pt-6 text-sm text-gray-800
                               bg-white border border-sky-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent
                               peer transition"
                        value="<?= $form['account_name'] ?? '' ?>"
                        placeholder=" " required>
                    <label for="account_name"
                        class="absolute left-4 top-4 text-sm text-gray-400
                               transition-all duration-200
                               peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm
                               peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-sky-500
                               peer-[:not(:placeholder-shown)]:top-1.5 peer-[:not(:placeholder-shown)]:text-xs">
                        アカウント名
                    </label>
                </div>

                <!-- パスワード -->
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="block w-full px-4 pb-2.5 pt-6 text-sm text-gray-800
                               bg-white border border-sky-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent
                               peer transition"
                        placeholder=" " required>
                    <label for="password"
                        class="absolute left-4 top-4 text-sm text-gray-400
                               transition-all duration-200
                               peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm
                               peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-sky-500
                               peer-[:not(:placeholder-shown)]:top-1.5 peer-[:not(:placeholder-shown)]:text-xs">
                        パスワード
                    </label>
                </div>

                <!-- ログインボタン -->
                <button id="submit_button"
                    class="w-full py-3 px-4 bg-sky-500 hover:bg-sky-600 active:bg-sky-700
                           text-white font-semibold rounded-xl
                           transition duration-200
                           disabled:bg-sky-200 disabled:cursor-not-allowed
                           shadow-sm">
                    Sign in
                </button>
            </form>

            <!-- テスト入力 -->
            <div class="mt-5 text-center">
                <button onclick="inputTestLoginUser()"
                    class="text-sm text-sky-400 hover:text-sky-600 hover:underline transition">
                    Test Input
                </button>
            </div>

        </div>
    </main>
</body>

</html>