<?php
// 共通ファイル app.php を読み込み
require_once '../app.php';

use App\Models\AuthUser;

// ログインチェック
$auth_user = AuthUser::check();

// TODO: セッション（auth_user) からログインチェック
// if (empty($auth_user)) {
//     // ログインしていない場合はログイン画面にリダイレクト
//     header('Location: ../signin/');
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="ja">

<!-- コンポーネント: components/head.php -->
<?php include COMPONENT_DIR . 'head.php'; ?>

<body class="bg-sky-50 min-h-screen">
    <?php include COMPONENT_DIR . 'nav.php'; ?>

    <main class="flex flex-col items-center min-h-[calc(100vh-64px)] px-4 pt-16">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-md p-8 text-center">

            <?php if ($auth_user['profile_image']): ?>
                <div class="mb-2">
                    <img id="user-image" src="<?= $auth_user['profile_image'] ?>"
                        class="w-28 h-28 object-cover rounded-full mx-auto ring-4 ring-sky-100">
                </div>
            <?php endif ?>

            <h2 class="text-xl font-bold text-gray-800 mt-4">
                <?= htmlspecialchars($auth_user['account_name']) ?>
            </h2>
            <p class="text-sm text-gray-400 mt-1"><?= htmlspecialchars($auth_user['email']) ?></p>

            <div class="mt-6 pt-6 border-t border-sky-50">
                <span class="inline-block px-3 py-1 text-xs font-semibold text-sky-600 bg-sky-50 rounded-full">
                    ログイン中
                </span>
            </div>

        </div>
    </main>

</body>

</html>