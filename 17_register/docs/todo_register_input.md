# TODO 修正チェックリスト
`regist/input/index.php`

## チェックリスト

- [ ] `$_SESSION[APP_KEY]['regist']` の中身を `$regist` に代入
- [ ] 入力値が復元
- [ ] `$_SESSION[APP_KEY]['errors']` の中身を `$errors` に代入
- [ ] エラー取得後に `$_SESSION[APP_KEY]['errors']` を `unset()` 
- [ ] エラー文言が表示
- [ ] アカウント名に「" onClick="alert('ok')"」 を入力してもアラートが表示されない

## 解説

### 1. `regist` をセッションから取り出す

```php
$regist = $_SESSION[APP_KEY]['regist'];
```

#### 役割

登録確認やバリデーションで戻ってきたときに、直前に入力した値をフォームへ再表示するためです。

#### なぜ必要か

この画面では各 input の `value` に次のようなコードがあります。

```php
value="<?= $regist['account_name'] ?? '' ?>"
```

`$regist` にセッションの配列が入っていれば、入力済みの内容をそのまま再表示できます。

### 2. `errors` をセッションから取り出す

```php
$errors = $_SESSION[APP_KEY]['errors'];
```

#### 役割

前の画面や登録処理側で保存したエラーメッセージを、この入力画面で表示するためです。

#### なぜ必要か

後半で次のコードが使われています。

```php
$error = $errors['public'] ?? '';
include COMPONENT_DIR . 'error_message.php';
```

つまり `$errors` は配列である必要があります。`""` のような文字列を入れると、配列前提のコードと噛み合いません。

### 3. `errors` をフラッシュメッセージとして削除する

```php
unset($_SESSION[APP_KEY]['errors']);
```

#### 役割

エラーメッセージを「1 回だけ表示」するためです。

#### ポイント

`errors` を消さないと、画面を再読み込みしただけでも前回のエラーが残り続けます。

## 完成形

```php
<?php
// 共通アプリファイル読み込み
require_once "../../app.php";

$regist = [];
if (isset($_SESSION[APP_KEY]['regist'])) {
    // セッション APP_KEY の regist があれば取得
    $regist = $_SESSION[APP_KEY]['regist'];
}

$errors = [];
if (isset($_SESSION[APP_KEY]['errors'])) {
    // セッション APP_KEY の errors があれば取得
    $errors = $_SESSION[APP_KEY]['errors'];
    // エラーメッセージはフラッシュメッセージ
    unset($_SESSION[APP_KEY]['errors']);
}
?>
```
