<?php

namespace TosanSoha\Sama;

use Illuminate\Http\RedirectResponse;

class RequestResponse
{
    /** @var int */
    private $statusCode;

    /** @var string */
    private $code;

    /** @var string */
    private $detail;

    /** @var array */
    private $extra;

    /** @var string */
    private $paymentReqId;

    /** @var string */
    private $webViewLink;

    public function __construct(int $statusCode, array $result)
    {
        $this->statusCode = $statusCode;

        // dd($result);
        if ($this->success()) {
            $this->paymentReqId = $result['uid'];
            $this->webViewLink = $result['web_view_link'];
        } else {
            if (isset($result['code'])) {
                $this->code = $result['code'];
            } else {
                $this->code = $statusCode;
            }
            $this->detail = $result['detail'];
            if (isset($result['extra'])) {
                $this->extra = $result['extra'];
            }
        }
    }

    public function success(): bool
    {
        return 201 === $this->statusCode
               || 200 === $this->statusCode;
    }

    public function url(): string
    {
        if (!$this->success()) {
            return '';
        }

        return $this->webViewLink;
    }

    public function redirect(): ?RedirectResponse
    {
        $url = $this->url();

        return $url ? redirect($url) : null;
    }

    public function paymentReqId()
    {
        if (!$this->success()) {
            return '';
        }

        return $this->paymentReqId;
    }

    public function error(): Error
    {
        return new Error($this->code, $this->detail, $this->extra);
    }
}
