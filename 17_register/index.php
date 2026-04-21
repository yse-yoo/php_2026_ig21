<?php
// 共通ファイル app.php を読み込み
require_once 'app.php';

use App\Models\AuthUser;

$auth_user = AuthUser::check();
?>

<!DOCTYPE html>
<html lang="ja">

<!-- コンポーネント: components/head.php -->
<?php include COMPONENT_DIR . 'head.php'; ?>

<body class="bg-sky-50 min-h-screen">

    <?php include COMPONENT_DIR . 'nav.php'; ?>

    <main class="flex flex-col justify-center items-center min-h-[calc(100vh-64px)] px-4 text-center">

        <div class="max-w-xl">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-sky-100 mb-6">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>

            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Welcome to PHP Form!
            </h1>
            <p class="text-gray-500 mb-8">
                PHPによる会員登録サンプルアプリケーション
            </p>

            <div class="flex gap-3 justify-center">
                <a href="regist/"
                    class="px-6 py-3 text-sm font-semibold text-white bg-sky-500 rounded-xl shadow-sm hover:bg-sky-600 transition">
                    アカウント登録
                </a>
            </div>
        </div>

    </main>
</body>

</html>