<?php

namespace TosanSoha\Sama;

class Error
{
    /** @var string */
    private $code;

    /** @var string */
    private $detail;

    /** @var array */
    private $extras;

    public function __construct(string $code, string $detail, array $extras)
    {
        $this->code = $code;
        $this->detail = $detail;
        $this->extras = $extras;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function detail(): string
    {
        return $this->detail;
    }

    public function extras(): array
    {
        return $this->extras;
    }

    public function message(): string
    {
        if (isset($this->detail) && ! empty($this->detail)) {
            return $this->detail;
        }
        switch ($this->code) {
            case 'not_authenticated' :
                return 'خطای اعتبارسنجی توکن پرداخت، لطفا با تیم پیشتیبانی سما تماس بگیرید.';
            case 'validation_error':
                return 'مقادیر وارد شده معتبر نیست.';
            default:
                return 'خطای پیش بینی نشده‌ای رخ داده است.';
        }
    }
}
