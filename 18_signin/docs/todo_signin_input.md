# TODO 修正チェックリスト
`signin/input.php`

## チェックリスト

- [ ] `$_SESSION[APP_KEY]['signin']` の中身を `$form` に代入
- [ ] 入力値が復元
- [ ] `$_SESSION[APP_KEY]['error']` の中身を `$error` に代入
- [ ] エラー取得後に `$_SESSION[APP_KEY]['error']` を `unset()`
- [ ] エラー文言が表示

## 解説

### 1. `signin` をセッションから取り出す

```php
$form = $_SESSION[APP_KEY]['signin'];
```

#### 役割

ログイン失敗で入力画面に戻ってきたときに、直前に入力したアカウント名をフォームへ再表示するためです。

#### なぜ必要か

この画面では input の `value` に次のようなコードがあります。

```php
value="<?= h($form['account_name'] ?? '') ?>"
```

`$form` にセッションの配列が入っていれば、入力済みの内容をそのまま再表示できます。

### 2. `error` をセッションから取り出す

```php
$error = $_SESSION[APP_KEY]['error'];
```

#### 役割

認証処理側で保存したエラーメッセージを、この入力画面で表示するためです。

#### なぜ必要か

この画面では次のコードが使われています。

```php
include COMPONENT_DIR . 'error_message.php';
```

`error_message.php` は `$error` が入っているときだけメッセージを表示するので、事前に `$error` に文字列を入れておく必要があります。

### 3. `error` をフラッシュメッセージとして削除する

```php
unset($_SESSION[APP_KEY]['error']);
```

#### 役割

エラーメッセージを「1 回だけ表示」するためです。

#### ポイント

`error` を消さないと、画面を再読み込みしただけでも前回のエラーが残り続けます。

### 4. 入力値は `h()` でエスケープする

```php
value="<?= h($form['account_name'] ?? '') ?>"
```

#### 役割

ユーザー入力を HTML として解釈させず、安全に文字列として表示するためです。

#### なぜ必要か

例えばアカウント名に `"` を含む文字列をそのまま `value` に出力すると、属性を壊して JavaScript を差し込める可能性があります。`h()` を通せば特殊文字がエスケープされるので、`" onClick="alert('ok')"` を入力してもアラートは実行されません。

## 完成形

```php
<?php
// 共通アプリファイル読み込み
require_once "../app.php";

$form = [];
if (isset($_SESSION[APP_KEY]['signin'])) {
    // セッション APP_KEY の signin があれば取得
    $form = $_SESSION[APP_KEY]['signin'];
}

$error = null;
if (isset($_SESSION[APP_KEY]['error'])) {
    // セッション APP_KEY の error があれば取得
    $error = $_SESSION[APP_KEY]['error'];
    // エラーメッセージはフラッシュメッセージ
    unset($_SESSION[APP_KEY]['error']);
}
?>
```
