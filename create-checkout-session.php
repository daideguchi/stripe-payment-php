<?php
//セキュリティ面のための操作
require 'vendor/autoload.php';
// 本番環境ではdotenvに格納すること
\Stripe\Stripe::setApiKey('sk_test_51M8GYaDiJnwBN5HNg0ACAxlQOxh723Ri6fuAzwQWSaTuJ7FtpSBSnNVv25MotR4sXkRNQvRxYVb1T5YAUCD2VOJE00SVzMon8b');

header('Content-Type: application/json');

//ここは自分の環境に合わせて変えないとうまくいかない。ローカルで検証するときはDOMAIN1の方を使うと良いかも（適宜調整）
// $YOUR_DOMAIN = 'http://localhost:8443/public';
$YOUR_DOMAIN1 = 'http://localhost/stripe-php-code/public';



//ユーザーがカードの情報を入力して、ボタンを押したらtry以下が走る【カード情報入力画面はstripe公式のページ】
try {

  //$pricesは商品の情報
  $prices = \Stripe\Price::all([
    // フォームデータのPOSTボディからlookup_keyを取得する。
    'lookup_keys' => [$_POST['lookup_key']],
    'expand' => ['data.product']
  ]);

  // var_dump($prices);
  // exit();



  //ーーーーーここでAPIを作っているーーーーーーーーー///////////
  //$checkout_session、支払いの情報が入る。下段のLocation関数のところを参照すると分かるが、
  //この変数には、支払い画面のURL（外部の企業サイト）も格納されており、決済会社側で色々処理が周り、値を返している
  $checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
      'price' => $prices->data[0]->id,
      'quantity' => 1, //数量・今回はサブスクなので、１
    ]],
    'mode' => 'subscription', 

    // 決済に成功すればここに飛ばす
    // 'success_url' => $YOUR_DOMAIN . '/success.html?session_id={CHECKOUT_SESSION_ID}',
    'success_url' => $YOUR_DOMAIN1 . '/success.html?session_id={CHECKOUT_SESSION_ID}',
    //  header("Location: public/success.html")
    // 決済に失敗すればここに飛ばす
    'cancel_url' => $YOUR_DOMAIN1 . '/cancel.html',
  ]);
 //ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

  //これでうまくいかなければ、上記のページ遷移処理を一旦削除し、if文で「success_url」が取れていれば→success.htmlに飛ばす。とかでも良いかもしれない

  // var_dump($checkout_session);
  // exit();

  header("HTTP/1.1 303 See Other");

  //ここから外部の
  header("Location: " . $checkout_session->url);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}