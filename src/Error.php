<?php

namespace TosanSoha\Sama;

class Error
{
    /** @var string|null */
    private $code;

    /** @var string|null */
    private $detail;

    /** @var array|null */
    private $extra;

    public function __construct(string $code, string|null $detail, array $extra=null)
    {
        $this->code = $code;
        $this->detail = $detail;
        $this->extra = $extra;
    }

    public function code(): string|null
    {
        return $this->code;
    }

    public function detail(): string|null
    {
        return $this->detail;
    }

    public function extra(): array|null
    {
        return $this->extra;
    }

    public function message(): string
    {
        if (! self::isSnakeCase($this->detail) && isset($this->detail) && ! empty($this->detail)) {
            return $this->detail;
        }
        switch ($this->code) {
            case 'not_authenticated' :
                return 'خطای اعتبارسنجی توکن پرداخت، لطفا با تیم پیشتیبانی سما تماس بگیرید.';
            case 'validation_error':
                return 'مقادیر وارد شده معتبر نیست.';
            case '40016':
                return 'مقدار شماره سفارش (client id) ارسال شده تکراری است.';
            default:
                return 'خطای پیش بینی نشده‌ای رخ داده است.';
        }
    }

    public static function isSnakeCase($str) {
        return preg_match('/^[a-z]+(_[a-z]+)*$/', $str);
    }
}
