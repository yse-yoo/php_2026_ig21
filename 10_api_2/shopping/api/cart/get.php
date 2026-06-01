<?php
// セッションの開始
session_start();
// JSON形式でレスポンスを返すためのヘッダー
header('Content-Type: application/json');
// JSONデータをレスポンスする
echo json_encode([
    'status' => 'success',
    'cart' => $_SESSION['cart'] ?? [],
    'cartCount' => count($_SESSION['cart'] ?? [])
]);
