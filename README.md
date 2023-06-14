# درگاه پرداخت سما | Sama Payment Gateway

Sama Gateway library for Laravel based on Sama API

کتابخانه درگاه پرداخت سما برای لاراول براساس ای‌پی‌آی سما

## روش نصب - Installation

Use composer to install this package

برای نصب و استفاده از این پکیج می توانید از کامپوزر استفاده کنید

```bash
composer require sama-ir/sama-laravel-payment
```

## تنظیمات - Configuration

Add your token to .env file

توکن خود را اضافه کنید

```dotenv
SAMA_GATEWAY_TOEKN=abcd
```

You can also set token at runtime.

امکان تعیین توکن در حین اجرا نیز وجود دارد که در ادامه توضیح داده خواهد شد.


## روش استفاده | How to use

### ارسال مشتری به درگاه پرداخت | Send customer to payment gateway

```php
$response = sama()
    ->token('abcd') // تعیین توکن در حین اجرا - اختیاری
    ->amount(100) // مبلغ تراکنش
    ->request()
    ->clientId('5a1dca49-96bb-4318-a7cb-ebf2a6281e8e') // مقدار شناسه تراکنش در فروشگاه (باید یکتا باشد)
    ->callbackUrl('https://mystore.ir/payment_callback') // آدرس برگشت پس از پرداخت
    ->mobile('09123456789') // شماره موبایل مشتری - اختیاری
    ->send();

if (!$response->success()) {
    return $response->error()->message();
}

// ذخیره اطلاعات در دیتابیس
// هدایت مشتری به درگاه پرداخت

return $response->redirect();

```

### بررسی وضعیت تراکنش | Verify payment status

```php
$requestId = request()->query('request_id'); // دریافت کوئری استرینگ ارسال شده توسط سما
$price = request()->query('price'); // دریافت کوئری استرینگ ارسال شده توسط سما
$resultCode = request()->query('result_code'); // دریافت کوئری استرینگ ارسال شده توسط سما
$message = request()->query('message'); // دریافت کوئری استرینگ ارسال شده توسط سما

$clientId = '5a1dca49-96bb-4318-a7cb-ebf2a6281e8e';

if ($resultCode == 0 && $price == $savedPriceInDb) { // Successful payment, lets verify with Sama Gateway
    $response = sama()
        ->token('abcd') // تعیین توکن در حین اجرا - اختیاری
        ->verification()
        ->requestId($requestId)
        ->clientId($clientId)
        ->send();
}

if (!$response->success() || $response->isPaid === false) {
    return $response->error()->message(); // or loop through $response->error->extras to get detailed error messages
}

// پرداخت موفقیت آمیز بود

echo $response->isPaid // === true means successful payment


echo $response->paymentId();
echo $response->requestId();

// دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس

echo $response->referenceNumber();
echo $response->transactionCode();
```
