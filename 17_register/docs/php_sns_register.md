# セッションとユーザ登録

`17_register/` の現在のプログラム構成に合わせて、セッション保存、入力値の再利用、ユーザ登録、サニタイズを整理した教材です。

## セッション

入力したデータを入力画面で再利用できるように、セッションに保存します。

#### ファイル構成

```txt
17_register/
├── app/
│   ├── components/
│   │   ├── error_message.php
│   │   ├── head.php
│   │   └── nav.php
│   └── models/
│       ├── AuthUser.php
│       └── User.php
├── lib/
│   ├── Database.php
│   ├── File.php
│   ├── Model.php
│   └── Sanitize.php
├── regist/
│   ├── add.php
│   ├── index.php
│   ├── input.php
│   └── result.php
├── app.php
└── env.php
```

## セッション登録処理

### セッション開始処理

`app.php` が実行されたら、セッションを開始するようにします。

###### app.php

```php
<?php
// 設定ファイルを読み込み
require_once "env.php";

// セッション開始
session_start();
session_regenerate_id(true);

// アプリケーションのルートディレクトリパス
const BASE_DIR = __DIR__;
// app/ ディレクトリパス
const APP_DIR = __DIR__ . "/app/";
// lib/ ディレクトリパス
const LIB_DIR = __DIR__ . "/lib/";
// components/ ディレクトリパス
const COMPONENT_DIR = __DIR__ . "/app/components/";

// ライブラリ読み込み
require_once LIB_DIR . 'Database.php';
require_once LIB_DIR . 'Sanitize.php';
require_once LIB_DIR . 'File.php';
require_once LIB_DIR . 'Model.php';
```

### セッション登録

`regist/add.php` で、POST データをセッションに登録します。`$_SESSION` のキーは `env.php` で設定した `APP_KEY` と `regist` を使います。

###### regist/add.php

```php
<?php
require_once '../app.php';

use App\Models\User;

// POSTリクエストでなければ何も表示しない
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// POSTデータ取得
$posts = $_POST;

// セッションの APP_KEY 下の regist にPOSTデータを保存
$_SESSION[APP_KEY]['regist'] = $posts;
```

### セッション取得

入力画面でセッションデータを取得すると、直前に入力した値をフォームに再表示できます。

###### regist/input.php

```php
<?php
require_once "../app.php";

$regist = [];
if (isset($_SESSION[APP_KEY]['regist'])) {
    $regist = $_SESSION[APP_KEY]['regist'];
}
?>
```

### セッション表示

入力画面のフォームに、セッションに保存した前回入力データを表示します。

###### regist/input.php

```php
<form action="regist/add.php" method="post" class="space-y-4">

    <div class="relative">
        <input type="text" name="account_name"
            value="<?= h($regist['account_name'] ?? '') ?>"
            id="account_name"
            placeholder=" "
            required>
        <label for="account_name">アカウント名</label>
    </div>

    <div class="relative">
        <input type="text" name="display_name"
            value="<?= h($regist['display_name'] ?? '') ?>"
            id="display_name"
            placeholder=" "
            required>
        <label for="display_name">表示名</label>
    </div>

    <div class="relative">
        <input type="email" name="email"
            value="<?= h($regist['email'] ?? '') ?>"
            id="email"
            placeholder=" "
            required>
        <label for="email">メールアドレス</label>
    </div>

    <div class="relative">
        <input type="password" name="password"
            id="password"
            placeholder=" "
            required>
        <label for="password">パスワード</label>
    </div>

    <button id="submit_button">アカウントを作成する</button>
</form>
```

### エラーのセッション取得

`regist/add.php` でエラーが起きたときは、`errors` をセッションに保存して `input.php` へ戻します。`input.php` ではそのエラーを取り出し、表示後に `unset()` します。

###### regist/input.php

```php
<?php
require_once "../app.php";

$regist = [];
if (isset($_SESSION[APP_KEY]['regist'])) {
    $regist = $_SESSION[APP_KEY]['regist'];
}

$errors = [];
if (isset($_SESSION[APP_KEY]['errors'])) {
    $errors = $_SESSION[APP_KEY]['errors'];
    unset($_SESSION[APP_KEY]['errors']);
}

$error = $errors['public'] ?? '';
include COMPONENT_DIR . 'error_message.php';
?>
```

## ユーザ登録処理

### セッション取得

登録処理では、まず送信データを取得してセッションに保存します。

###### regist/add.php

```php
<?php
require_once '../app.php';

use App\Models\User;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$posts = $_POST;
$_SESSION[APP_KEY]['regist'] = $posts;
```

### 重複チェック

`User` モデルの `findForExists()` を使って、同じアカウント名またはメールアドレスがすでに存在するか確認します。

###### regist/add.php

```php
$user = new User();
$user_exists = $user->findForExists($posts);

if (!empty($user_exists['id'])) {
    $_SESSION[APP_KEY]['errors']['public'] = 'このアカウント名は既に使用されています。';
    header('Location: input.php');
    exit;
}
```

###### app/models/User.php

```php
public function findForExists($posts)
{
    if (empty($posts)) return;
    try {
        $account_name = $posts['account_name'];
        $email = $posts['email'];

        $pdo = self::pdo();
        $sql = "SELECT * FROM users WHERE account_name = :account_name OR email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'account_name' => $account_name,
            'email' => $email
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}
```

### ユーザデータ登録

`users` テーブルにデータ登録する SQL は `User` モデルの `insert()` に切り出しています。登録成功後は `auth_user` セッションを保存し、`result.php` にリダイレクトします。

###### regist/add.php

```php
<?php
require_once '../app.php';

use App\Models\User;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$posts = $_POST;
$_SESSION[APP_KEY]['regist'] = $posts;

$user = new User();
$user_id = $user->insert($posts);

if (empty($user_id)) {
    $_SESSION[APP_KEY]['errors']['public'] = 'ユーザ登録に失敗しました。';
    header('Location: input.php');
    exit;
} else {
    $auth_user = $user->find($user_id);
    $_SESSION[APP_KEY]['auth_user'] = $auth_user;

    header('Location: result.php');
    exit;
}
```

###### app/models/User.php

```php
public function insert($data)
{
    if (empty($data)) return;
    try {
        // パスワードのハッシュ化
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // DB接続
        $pdo = Database::getInstance();

        // SQL作成
        $sql = "INSERT INTO users (account_name, email, display_name, password)
                VALUES (:account_name, :email, :display_name, :password)";

        // SQL準備
        $stmt = $pdo->prepare($sql);

        // SQL実行
        $result = $stmt->execute($data);

        if ($result) {
            return $pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
    return;
}
```

### 登録完了後のセッション削除

登録開始用の `regist/index.php` と完了画面の `regist/result.php` では、不要になった `regist` セッションを削除します。

###### regist/index.php

```php
<?php
require_once "../app.php";

if (isset($_SESSION[APP_KEY]['regist'])) {
    unset($_SESSION[APP_KEY]['regist']);
}

header('Location: ./input.php');
```

## セキュリティ処理

### SQLインジェクション対策

SQL は文字列連結で組み立てず、`prepare()` と `execute()` を使ってプレースホルダに値を渡します。

###### app/models/User.php

```php
$sql = "SELECT * FROM users WHERE account_name = :account_name OR email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'account_name' => $account_name,
    'email' => $email
]);
```

このようにすると、フォームから送られた値をそのまま SQL 文の一部として解釈させずに実行できます。

### サニタイズ

#### サニタイズとは

フォーム送信で渡された文字列を、安全に扱える形へ変換することをサニタイズといいます。

#### `lib/Sanitize.php`

`17_register` では `h()` と `sanitize()` を用意しています。

###### lib/Sanitize.php

```php
<?php

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return trim(h($data, ENT_QUOTES, 'UTF-8'));
}
```

### POST データのサニタイズ

`regist/add.php` では、`$_POST` をそのまま使うのではなく、`sanitize()` を通してから使う形にできます。

###### regist/add.php

```php
<?php
require_once "../app.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit;
}

// サニタイズ
$posts = sanitize($_POST);

// セッションに保存
$_SESSION[APP_KEY]['regist'] = $posts;
```

### 出力時のエスケープ

フォームへ値を戻すときも、そのまま出力せず `h()` を使います。

```php
value="<?= h($regist['account_name'] ?? '') ?>"
```

これにより、たとえば次のような文字列を入力しても HTML 属性が壊れません。

```txt
" onClick="alert('ok')"
```

`"` や `'` が HTML エンティティに変換されるため、JavaScript が実行されず、文字列として安全に表示されます。
