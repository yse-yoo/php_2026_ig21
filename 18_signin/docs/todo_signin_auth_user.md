# TODO 修正チェックリスト
`signin/auth.php`
`app/models/User.php`

## チェックリスト

- [ ] `$_SESSION[APP_KEY]['signin']` に `$_POST` を保存
- [ ] `$_POST` から `account_name` と `password` を取得
- [ ] `User::auth()` に入力値を渡して認証している
- [ ] `User::auth()` でアカウント名検索用の SQL を作成
- [ ] `User::auth()` で `password_verify()` によるパスワード検証をしている
- [ ] 認証成功時だけユーザデータを返す
- [ ] 認証失敗時は `$_SESSION[APP_KEY]['error']` にエラーメッセージを保存
- [ ] 認証成功時は `AuthUser::set($auth_user)` でログインセッションを保存
- [ ] 失敗時は `input.php`、成功時は `home/` にリダイレクトする

## 解説

### 1. `signin/auth.php` で POST データをセッションに保存する

```php
$_SESSION[APP_KEY]['signin'] = $_POST;
```

#### 役割

ログインに失敗したとき、直前に入力したアカウント名を入力画面へ戻すためです。

#### なぜ必要か

`signin/input.php` では、セッションの `signin` を `$form` として読み出し、次のように input の `value` へ使います。

```php
value="<?= h($form['account_name'] ?? '') ?>"
```

ここに `$_POST` を保存しておけば、失敗後にアカウント名を再表示できます。

### 2. `signin/auth.php` で `account_name` と `password` を取得する

```php
$account_name = $_POST['account_name'];
$password = $_POST['password'];
```

#### 役割

入力画面から送られたログイン情報を、認証処理へ渡すためです。

#### なぜ必要か

この 2 つの値がないと、`User::auth()` でユーザ検索とパスワード照合ができません。

### 3. `app/models/User.php` でアカウント名検索用の SQL を作る

```php
$sql = "SELECT * FROM users WHERE account_name = :account_name";
```

#### 役割

入力されたアカウント名に一致するユーザを、DB から 1 件取得するためです。

#### なぜ必要か

パスワード検証は、DB に保存されているハッシュ値と比較して行います。まず対象ユーザを検索する必要があります。

### 4. `password_verify()` でパスワードを照合する

```php
if ($user && password_verify($password, $user['password'])) {
    return $user;
}
```

#### 役割

入力された平文パスワードが、DB に保存されたハッシュと一致するか確認するためです。

#### なぜ必要か

`insert()` では登録時に `password_hash()` を使って保存しています。そのため、ログイン時は平文同士の比較ではなく `password_verify()` を使う必要があります。

### 5. 認証成功時は `AuthUser::set()` でログイン状態を保存する

```php
AuthUser::set($auth_user);
```

#### 役割

認証済みユーザをセッションへ保存し、以後の画面でログイン済みとして扱えるようにするためです。

#### なぜ必要か

認証に成功してもセッションへ保存しなければ、`home/` などの画面でログイン状態を判定できません。

## 完成形

### `signin/auth.php`

```php
<?php
// 共通ファイル app.php を読み込み
require_once '../app.php';

use App\Models\AuthUser;
use App\Models\User;

// POSTリクエストでなければ何も表示しない
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// セッションにPOSTデータを登録
$_SESSION[APP_KEY]['signin'] = $_POST;

// 入力されたアカウント名とパスワードを取得
$account_name = $_POST['account_name'];
$password = $_POST['password'];

// ユーザ認証: new User() で auth() を実行
$user = new User();
$auth_user = $user->auth($account_name, $password);

if (empty($auth_user['id'])) {
    // エラーセッション
    $_SESSION[APP_KEY]['error'] = 'アカウント名またはパスワードが間違っています。';
    // ログイン失敗時はログイン入力画面にリダイレクト
    header('Location: ./input.php');
    exit;
} else {
    // 認証成功時はセッションにユーザデータを保存
    AuthUser::set($auth_user);

    // ユーザトップページにリダイレクト: home/
    header('Location: ../home/');
    exit;
}
```

### `app/models/User.php` の `auth()`

```php
public function auth($account_name, $password)
{
    // DB接続
    $pdo = self::pdo();
    // SQL作成: アカウント名でユーザを検索
    $sql = "SELECT * FROM users WHERE account_name = :account_name";
    try {
        // SQL用意
        $stmt = $pdo->prepare($sql);
        // SQL実行
        $stmt->execute(['account_name' => $account_name]);
        // 結果取得
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // パスワード検証して、$user を返す
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
    return;
}
```
