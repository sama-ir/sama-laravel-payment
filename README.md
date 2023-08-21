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

 You need a token to use Sama web services, you can get this token according to [Sama documentation](https://docs.sama.ir/content/services/guaranteed/webservice/).

 برای استفاده از سرویس های سما نیاز به یک توکن دارید، این توکن را می توانید طبق [مستندات سما](https://docs.sama.ir/content/services/guaranteed/webservice/) دریافت کنید.

Add your token to .env file.

توکن خود را به فایل env اضافه کنید.

```dotenv
SAMA_GATEWAY_TOKEN=abcd
```

You can also set token at runtime.

امکان تعیین توکن در حین اجرا نیز وجود دارد که در ادامه توضیح داده خواهد شد.


## روش استفاده | How to use

برای استفاده از سرویس پرداخت امن سما، لازم است وب سایت فروشگاه یک درخواست ایجاد لینک پرداخت به همراه «آدرس بازگشت فروشگاه» به سما ارسال کند. سما در پاسخ یک آدرس پرداخت ارسال می کند که فروشگاه، کاربر خریدار را به آن هدایت خواهد کرد. بعد از مشخص شدن نتیجه پرداخت، سما کاربر را به «آدرس بازگشت فروشگاه» هدایت خواهد کرد.

### ارسال مشتری به درگاه پرداخت | Send customer to payment gateway

در روال پرداخت با سما، وب سایت فروشگاه ابتدا یک درخواست ایجاد لینک پرداخت به سما ارسال می کند. این درخواست شامل مبلغ تراکنش، یک مقدار شناسه یکتا به ازای هر درخواست پرداخت، «آدرس بازگشت فروشگاه» پس از پرداخت است. در پاسخ فراخوانی وب سرویس یک لینک پرداخت دریافت می شود که فروشگاه کاربر پرداخت کننده را به آن لینک پرداخت هدایت کند. «آدرس بازگشت فروشگاه» آدرسی است که در بخش بررسی وضعیت تراکنش اجرا خواهد شد.

```php
    // In a Controller action or Route handler:

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

پس از مشخص شدن نتیجه پرداخت سما کاربر خریدار را با متد POST به «آدرس بازگشت فروشگاه» هدایت می کند، نمونه کد برای بررسی وضعیت تراکنش مشابه زیر خواهد بود:

```php
    // In a Controller action or Route handler (eg. POST to /api/payment_callback):

    $price = request()->post('price'); // دریافت پارامتر ارسال شده توسط سما
    $requestId = request()->post('request_id'); // دریافت پارامتر ارسال شده توسط سما
    $resultCode = request()->post('result_code'); // دریافت پارامتر ارسال شده توسط سما
    $processId = request()->post('process_id'); // دریافت پارامتر ارسال شده توسط سما

    $savedPriceInDb = 10000;
    $clientId = '0a1dca49-96bb-4318-a7cb-ebf2a6281003';

    if ($resultCode != 0 || $price != $savedPriceInDb) {
        // Payment was not successful or someone tampered with the data
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

    if($savedPriceInDb != $response->$price) {
        // Someone tampered with the data, prices do not match
        return ["status" => "failed", "message" => "پرداخت ناموفق"];
    }

    // وریفای پرداخت موفقیت آمیز بود:

    echo $response->isPaid(); // === true means successful payment
    echo $response->paymentId();
    echo $response->requestId();

    // دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس:

    // Save referenceNumber and transactionCode in the database
    echo $response->referenceNumber();
    echo $response->transactionCode();

    return ["status" => "ok", "message" => "پرداخت موفق"];
```
