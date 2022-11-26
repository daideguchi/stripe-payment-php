<?php
require 'vendor/autoload.php';

// 本番環境ではdotenvに格納すること
\Stripe\Stripe::setApiKey('sk_test_51M8GYaDiJnwBN5HNg0ACAxlQOxh723Ri6fuAzwQWSaTuJ7FtpSBSnNVv25MotR4sXkRNQvRxYVb1T5YAUCD2VOJE00SVzMon8b');

// エンドポイントのシークレットキーは自分固有のものに置き換えます。
// CLIでテストしている場合は、'stripe listen' を実行すればオッケー。
// APIやダッシュボードで定義されたエンドポイントを使用している場合は、Webhookの設定を確認。
// https://dashboard.stripe.com/webhooks
$endpoint_secret = 'whsec_12345';

$payload = @file_get_contents('php://input');
$event = null;
try {
  $event = \Stripe\Event::constructFrom(
    json_decode($payload, true)
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  echo '⚠️  Webhook error while parsing basic request.';
  http_response_code(400);
  exit();
}
// Handle the event
// 以下、サブスク期間が終わった際の処理と、CRUD処理
switch ($event->type) {
  case 'customer.subscription.trial_will_end':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the trial ending.
    // handleTrialWillEnd($subscription);
    break;
  case 'customer.subscription.created':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being created.
    // handleSubscriptionCreated($subscription);
    break;
  case 'customer.subscription.deleted':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being deleted.
    // handleSubscriptionDeleted($subscription);
    break;
  case 'customer.subscription.updated':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being updated.
    // handleSubscriptionUpdated($subscription);
    break;
  default:
    // Unexpected event type
    echo 'Received unknown event type';
}