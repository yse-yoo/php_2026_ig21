<?php
// 共通アプリファイル読み込み
require_once "../app.php";

$regist = [];
if (isset($_SESSION[APP_KEY]['regist'])) {
    // TODO: セッション APP_KEY の regist があれば取得
    $regist = null;
}

$errors = [];
if (isset($_SESSION[APP_KEY]['errors'])) {
    // TODO: セッション APP_KEY の errors があれば取得
    $errors = "";
    // TODO: エラーメッセージはフラッシュメッセージ
    // unset($_SESSION[APP_KEY]['errors']);
}
?>

<!DOCTYPE html>
<html lang="ja">

<!-- components/head.php を読み込み -->
<?php include COMPONENT_DIR . "head.php" ?>

<body class="bg-sky-50 min-h-screen">
    <?php include COMPONENT_DIR . 'nav.php'; ?>

    <main class="flex flex-col justify-center items-center min-h-screen px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">

            <!-- ロゴ・タイトル -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-sky-100 mb-4">
                    <svg width="48" class="w-7 h-7 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-sky-600 tracking-wide">Sign Up</h2>
                <p class="text-sm text-gray-400 mt-1">新しいアカウントを作成してください</p>
            </div>

            <!-- エラーメッセージ -->
            <?php
            $error = $errors['public'] ?? '';
            include COMPONENT_DIR . 'error_message.php';
            ?>

            <!-- Form -->
            <!-- regist/add/ に POSTリクエスト -->
            <form action="regist/add.php" method="post" class="space-y-4">

                <!-- アカウント名 -->
                <div class="relative">
                    <input type="text" name="account_name"
                        value="<?= $regist['account_name'] ?? '' ?>"
                        id="account_name"
                        class="block w-full px-4 pb-2.5 pt-6
                               text-sm text-gray-800
                               bg-white border border-sky-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent
                               peer transition"
                        placeholder=" "
                        required>
                    <label for="account_name"
                        class="absolute left-4 top-4 text-sm text-gray-400
                               transition-all duration-200
                               peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm
                               peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-sky-500
                               peer-[:not(:placeholder-shown)]:top-1.5 peer-[:not(:placeholder-shown)]:text-xs">
                        アカウント名
                    </label>
                </div>

                <!-- 表示名 -->
                <div class="relative">
                    <input type="text" name="display_name"
                        value="<?= $regist['display_name'] ?? '' ?>"
                        id="display_name"
                        class="block w-full px-4 pb-2.5 pt-6
                               text-sm text-gray-800
                               bg-white border border-sky-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent
                               peer transition"
                        placeholder=" "
                        required>
                    <label for="display_name"
                        class="absolute left-4 top-4 text-sm text-gray-400
                               transition-all duration-200
                               peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm
                               peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-sky-500
                               peer-[:not(:placeholder-shown)]:top-1.5 peer-[:not(:placeholder-shown)]:text-xs">
                        表示名
                    </label>
                </div>

                <!-- メールアドレス -->
                <div class="relative">
                    <input type="email" name="email"
                        value="<?= $regist['email'] ?? '' ?>"
                        id="email"
                        class="block w-full px-4 pb-2.5 pt-6
                               text-sm text-gray-800
                               bg-white border border-sky-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent
                               peer transition"
                        placeholder=" "
                        required>
                    <label for="email"
                        class="absolute left-4 top-4 text-sm text-gray-400
                               transition-all duration-200
                               peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm
                               peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-sky-500
                               peer-[:not(:placeholder-shown)]:top-1.5 peer-[:not(:placeholder-shown)]:text-xs">
                        メールアドレス
                    </label>
                </div>

                <!-- パスワード -->
                <div class="relative">
                    <input type="password" name="password"
                        id="password"
                        class="block w-full px-4 pb-2.5 pt-6
                               text-sm text-gray-800
                               bg-white border border-sky-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent
                               peer transition"
                        placeholder=" "
                        required>
                    <label for="password"
                        class="absolute left-4 top-4 text-sm text-gray-400
                               transition-all duration-200
                               peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm
                               peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-sky-500
                               peer-[:not(:placeholder-shown)]:top-1.5 peer-[:not(:placeholder-shown)]:text-xs">
                        パスワード
                    </label>
                </div>

                <!-- 登録ボタン -->
                <button id="submit_button"
                    class="w-full py-3 px-4 bg-sky-500 hover:bg-sky-600 active:bg-sky-700
                           text-white font-semibold rounded-xl
                           transition duration-200
                           disabled:bg-sky-200 disabled:cursor-not-allowed
                           shadow-sm mt-2">
                    アカウントを作成する
                </button>
            </form>

            <!-- Test -->
            <div class="mt-6 space-y-2 text-center">
                <button onclick="inputTestRegistUser()"
                    class="text-sm text-sky-400 hover:text-sky-600 hover:underline transition">
                    Test Input
                </button>
            </div>
        </div>
    </main>
</body>

</html>