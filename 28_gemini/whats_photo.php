<?php
require_once 'env.php';
require_once 'services/GeminiService.php';

$uploadedImagePath = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 画像がアップロード
    $uploadedImagePath = uploadImage();

    // GeminiAPIに画像を送信
    if ($uploadedImagePath && file_exists($uploadedImagePath)) {
        $gemini = new GeminiService();
        $results = $gemini->image($uploadedImagePath);
    }
}

/**
 * 画像をアップロードする関数
 * @return string|null アップロードした画像のパス
 */
function uploadImage()
{
    // $_FILES['image']が存在し、エラーがないことを確認
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return;
    }
    // アップロード画像の保存先を設定
    $uploadDir = 'uploads/';
    // 画像保存先ディレクトリがなければ作成
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // TODO: アップロードファイル名を取得: $_FILES: image, name
    $fileName = "";
    // 拡張子を取得
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    // アップロードファイル名: ユニークな名前を生成
    $fileName = uniqid() . ".{$extension}";
    // アップロード画像の保存先
    $uploadedImagePath = "uploads/{$fileName}";
    // TODO: アップロードされたファイルパスを取得: image, tmp_name
    $filePath = "";
    // アップロード画像を保存
    move_uploaded_file($filePath, $uploadedImagePath);

    return $uploadedImagePath;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What's Image</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 p-6">
    <main class="mx-auto max-w-4xl space-y-6">
        <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-sky-600">Gemini API</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">What's Photo</h1>
            </div>
            <nav class="flex gap-3 text-sm font-semibold">
                <a href="index.php" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:border-sky-300 hover:text-sky-700">メニュー</a>
                <a href="chat.php" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:border-sky-300 hover:text-sky-700">Chat</a>
            </nav>
        </header>

        <section class="rounded-lg bg-white p-6 shadow">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-900">画像をアップロード</h2>
                <p class="mt-1 text-sm text-slate-500">画像ファイルを選択して、Gemini で内容を解析します。</p>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="space-y-4" data-loading-message="画像を解析しています。しばらくお待ちください。">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">画像ファイル</span>
                    <input type="file" name="image" accept="image/*" required class="block w-full rounded-lg border border-slate-300 bg-white p-3 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-sky-50 file:px-4 file:py-2 file:font-semibold file:text-sky-700 hover:file:bg-sky-100 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                </label>
                <input type="hidden" name="MAX_FILE_SIZE" value="30000">
                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-sky-600 px-5 py-2.5 font-semibold text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-200">
                        解析する
                    </button>
                </div>
            </form>
        </section>

        <?php if (isset($results['error'])): ?>
            <section class="rounded-lg border border-red-200 bg-red-50 p-5 text-red-700">
                <?= htmlspecialchars($results['error']) ?>
            </section>
        <?php endif; ?>

        <?php if (isset($results['text'])): ?>
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-slate-900">解析結果</h2>

                <div class="mt-5 grid gap-6 lg:grid-cols-[minmax(0,320px)_1fr]">
                    <?php if (!empty($uploadedImagePath) && file_exists($uploadedImagePath)): ?>
                        <figure class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <img src="<?= htmlspecialchars($uploadedImagePath) ?>" alt="アップロード画像" class="aspect-square w-full rounded-md object-cover">
                        </figure>
                    <?php endif; ?>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-5 leading-8 text-slate-800">
                        <?= $results['text'] ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php include 'components/loading_modal.php'; ?>
</body>

</html>
