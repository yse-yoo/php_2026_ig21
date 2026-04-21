# TODO 修正チェックリスト
`regist/add.php`

## 処理の流れ

1. 登録フォームから POST で送信
2. POST 以外なら処理を終了
3. `$_POST` をサニタイズして `$posts` に入れる
4. 入力値復元用に `$posts` をセッションへ保存
5. 既存ユーザと重複していないか確認
6. 重複していればエラーを保存して入力画面へ戻す
7. 重複していなければ users テーブルへ登録
8. 登録したユーザ情報を取得
9. 取得できなければ入力画面へ戻す
10. 取得できればログインユーザとしてセッションへ保存
11. 登録完了画面へ移動

## チェックリスト

- [ ] `$_POST` を `sanitize()` して `$posts` に代入
- [ ] `$_SESSION[APP_KEY]['regist']` に `$posts` を保存
- [ ] `User` モデルの `insert()` を使ってユーザ登録
- [ ] 登録失敗で、`input.php` にリダイレクト
- [ ] 登録成功後、`result.php` にリダイレクト

## 解説

### 1. POST リクエスト以外を止める

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}
```

#### 役割

このファイルは登録フォームから送信されたデータを処理するためのファイルです。直接 URL を開いた GET リクエストでは登録処理を進めないようにします。

#### なぜ必要か

POST 以外でも処理が進むと、`$_POST` が空のままユーザ検索や登録処理に渡されます。登録処理専用のページでは、最初にリクエスト方法を確認するのが基本です。

### 2. POST データをサニタイズする

```php
$posts = sanitize($_POST);
```

#### 役割

フォームから送られてきた値の前後の空白を取り除き、HTML として危険な文字をエスケープします。

#### なぜ必要か

ユーザが入力した値は、そのまま信用しません。表示や保存に使う前に、アプリ側で扱いやすい形へ整えます。

### 3. 入力値をセッションに保存する

```php
$_SESSION[APP_KEY]['regist'] = $posts;
```

#### 役割

登録に失敗して入力画面へ戻ったとき、入力済みの値をフォームに復元するためです。

#### なぜ必要か

`regist/input/index.php` では、次のように `$regist` の値を input の `value` に使っています。

```php
value="<?= $regist['account_name'] ?? '' ?>"
```

そのため、登録処理側で `$_SESSION[APP_KEY]['regist']` に POST データを保存しておく必要があります。

### 4. 登録失敗時は入力画面へ戻す

```php
header('Location: input.php');
exit;
```

#### 役割

ユーザ登録後にユーザ情報を取得できなかった場合、登録完了画面には進めず入力画面へ戻します。

#### ポイント

`header()` でリダイレクトした後も PHP の処理は自動では止まりません。意図しない後続処理を防ぐため、必ず `exit;` を書きます。

### 5. 登録成功時にログインユーザをセッションへ保存する

```php
$_SESSION[APP_KEY]['auth_user'] = $auth_user;
```

#### 役割

登録したユーザを、そのままログイン中のユーザとして扱うためです。

#### なぜ必要か

セッションにログインユーザ情報が入っていれば、別ページでも「誰がログインしているか」を確認できます。

### 6. 登録完了画面へ移動する

```php
header('Location: result.php');
exit;
```

#### 役割

登録とログイン状態の保存が成功したら、登録完了画面へ移動します。

## 完成形

```php
<?php
// 共通ファイル app.php を読み込み
require_once '../app.php';

// Userモデルをインポート
use App\Models\User;

// POSTリクエストでなければ何も表示しない
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// POSTデータ取得 & サニタイズ
$posts = sanitize($_POST);

// セッションの APP_KEY 下の regist にPOSTデータを保存
$_SESSION[APP_KEY]['regist'] = $posts;

// ユーザが存在するかチェック
$user = new User();
$user_exists = $user->findForExists($posts);
if (!empty($user_exists['id'])) {
    // ユーザが既に存在する場合はエラーメッセージをセッションに保存
    $_SESSION[APP_KEY]['errors']['public'] = 'このアカウント名は既に使用されています。';
    header('Location: input.php');
    exit;
}

// ユーザ登録
$user = new User();
$user_id = (int) $user->insert($posts);

if (user_id) {
    // ログインに失敗したとき、ログイン入力画面にリダイレクト
    header('Location: input.php');
    exit;
} else {
    // ログインに成功したとき、セッションにログインユーザ  $auth_user を入れる
    $_SESSION[APP_KEY]['auth_user'] = $auth_user;
    // 結果ページにリダイレクト: ../result/
    header('Location: result.php');
    exit;
}
```

## つまずきやすい点

- POST チェックをしないと、直接アクセスでも登録処理が動こうとしてしまう
- `$posts = [];` のままだと、ユーザ検索や登録に必要な値が入らない
- `$_SESSION[APP_KEY]['regist'] = null;` のままだと、入力画面で値を復元できない
- `$_SESSION[APP_KEY]['auth_user'] = null;` のままだと、登録後にログイン状態にならない
- `header('Location: ...')` のコメントアウトを外し忘れると、画面遷移しない
- `header()` の後に `exit;` を書かないと、意図しない処理が続く可能性がある
