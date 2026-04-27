<?php
require_once "../app.php";

if (isset($_SESSION[APP_KEY]['signin'])) {
    unset($_SESSION[APP_KEY]['signin']);
}
// リダイレクト
header('Location: input.php');
