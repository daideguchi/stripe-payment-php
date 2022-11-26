<?php
//決済情報を閲覧するための処理・・・ここから先は未実装

require 'vendor/autoload.php';
// 本番環境ではdotenvで変数格納するべき
\Stripe\Stripe::setApiKey('sk_test_51M8GYaDiJnwBN5HNg0ACAxlQOxh723Ri6fuAzwQWSaTuJ7FtpSBSnNVv25MotR4sXkRNQvRxYVb1T5YAUCD2VOJE00SVzMon8b');

header('Content-Type: application/json');

//ここは自分の環境に合わせて変えないとうまくいかない。ローカルで検証するときはDOMAIN1の方を使うと良いかも（適宜調整）
// $YOUR_DOMAIN = 'http://localhost:8443/public/success.html';

// XAMPP用
$YOUR_DOMAIN1 = 'http://localhost/stripe-php-code/public';


try {
  //ユーザーの情報を変数に格納する
  $checkout_session = \Stripe\Checkout\Session::retrieve($_POST['session_id']);
  // $return_url = $YOUR_DOMAIN;
  // XAMPP用
  $return_url = $YOUR_DOMAIN1;

  // ユーザーの認証
  $session = \Stripe\BillingPortal\Session::create([
    'customer' => $checkout_session->customer,
    'return_url' => $return_url,
  ]);
  header("HTTP/1.1 303 See Other");
  header("Location: " . $session->url);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}