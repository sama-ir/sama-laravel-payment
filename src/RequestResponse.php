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
    private $extras;

    /** @var string */
    private $paymentReqId;

    /** @var string */
    private $webViewLink;

    public function __construct(array $result, int $statusCode)
    {
        $this->statusCode = $statusCode;

        if ($this->success()) {
            $this->paymentReqId = $result["uid"];
            $this->webViewLink = $result["web_view_link"];
        } else {
            $this->code = $result["code"];
            $this->detail = $result["detail"];
            $this->extras = $result["extras"];
        }
    }

    public function success(): bool
    {
        return $this->statusCode === 201 ||
               $this->statusCode === 200;
    }

    public function url(): string
    {
        if (! $this->success()) {
            return '';
        }

        return $this->webViewLink;
    }

    public function redirect(): ?RedirectResponse
    {
        $url = $this->url();

        return $url ? redirect($url) : null;
    }

    public function error(): Error
    {
        return new Error($this->code, $this->detail, $this->extras);
    }
}
