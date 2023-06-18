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
                // ->token('abcd') // تعیین توکن در حین اجرا - اختیاری
                ->amount(10000) // مبلغ تراکنش
                ->request()
                ->clientId('0a1dca49-96bb-4318-a7cb-ebf2a6281003') // مقدار شناسه تراکنش در فروشگاه (باید یکتا باشد)
                ->callbackUrl('http://localhost:8000/api/payment_callback') // آدرس برگشت پس از پرداخت
                ->mobile('09123456789') // شماره موبایل مشتری - اختیاری
                ->send();

    if (!$response->success()) {
        return [
            $response->error()->code(),
            $response->error()->detail(),
            $response->error()->message(),
            $response->error()->extra()
        ];
    }

    // ذخیره اطلاعات در دیتابیس
    // ...
    // هدایت مشتری به درگاه پرداخت

    return $response->redirect();

```

### بررسی وضعیت تراکنش | Verify payment status

```php
    $requestId = request()->query('request_id'); // دریافت کوئری استرینگ ارسال شده توسط سما
    $price = request()->query('price'); // دریافت کوئری استرینگ ارسال شده توسط سما
    $resultCode = request()->query('result_code'); // دریافت کوئری استرینگ ارسال شده توسط سما
    $processId = request()->query('process_id'); // دریافت کوئری استرینگ ارسال شده توسط سما

    $savedPriceInDb = 10000;
    $clientId = '0a1dca49-96bb-4318-a7cb-ebf2a6281003';

    if ($resultCode != 0 || $price != $savedPriceInDb) {
        return ["status" => "failed", "message" => "پرداخت ناموفق"];
    }

    // Successful payment, lets verify with Sama Gateway
    $response = sama()
                // ->token('abcd') // تعیین توکن در حین اجرا - اختیاری
                ->verification()
                ->requestId($requestId)
                ->clientId($clientId)
                ->send();

    if (!$response->success() || $response->isPaid() === false) {
        // $response->error()->code(),
        // $response->error()->detail(),
        return ["status" => "failed", "message" => "پرداخت ناموفق"];
    }

    // پرداخت موفقیت آمیز بود

    echo $response->isPaid(); // === true means successful payment
    echo $response->paymentId();
    echo $response->requestId();

    // دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس

    // Save referenceNumber and transactionCode in the database
    echo $response->referenceNumber();
    echo $response->transactionCode();

    return ["status" => "ok", "message" => "پرداخت موفق"];
```
