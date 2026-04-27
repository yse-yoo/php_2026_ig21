# TODO 修正チェックリスト
`home/index.php`

## チェックリスト

- [ ] `AuthUser::check()` の戻り値でログインチェックしている
- [ ] 未ログイン時に `empty($auth_user)` で判定している
- [ ] 未ログイン時は `../signin/` にリダイレクトしている
- [ ] リダイレクト後に `exit` している
- [ ] ログイン済みユーザだけが `home/index.php` を表示できる

## 解説

### 1. `AuthUser::check()` でセッションから認証ユーザを取得する

```php
$auth_user = AuthUser::check();
```

#### 役割

現在ログイン中のユーザ情報を、セッションから取得するためです。

#### なぜ必要か

`AuthUser::check()` は `$_SESSION[APP_KEY]` に保存されたユーザ情報を返します。`signin/auth.php` で `AuthUser::set($auth_user)` しているので、その内容を `home/index.php` で受け取って表示できます。

### 2. 未ログインなら `signin/` に戻す

```php
if (empty($auth_user)) {
    header('Location: ../signin/');
    exit;
}
```

#### 役割

ログインしていない状態で `home/index.php` に直接アクセスされた場合、保護された画面を表示しないようにするためです。

#### なぜ必要か

この画面では次のように `$auth_user` の中身をそのまま使っています。

```php
<?= htmlspecialchars($auth_user['account_name']) ?>
```

未ログインで `$auth_user` が空のままだと、画面表示が不正になるだけでなく、本来ログイン後だけに見せるページへ直接入れてしまいます。

### 3. `exit` が必要

```php
header('Location: ../signin/');
exit;
```

#### 役割

リダイレクト命令を送ったあとに、それ以降の HTML 出力を止めるためです。

#### ポイント

`header()` だけだと、後続の処理が続いてしまいます。認証ガードでは `exit` までをセットで書く必要があります。

## 完成形

```php
<?php
// 共通ファイル app.php を読み込み
require_once '../app.php';

use App\Models\AuthUser;

// ログインチェック
$auth_user = AuthUser::check();

// セッション（auth_user) からログインチェック
if (empty($auth_user)) {
    // ログインしていない場合はログイン画面にリダイレクト
    header('Location: ../signin/');
    exit;
}
?>
```
