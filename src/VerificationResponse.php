<?php

namespace TosanSoha\Sama;

class VerificationResponse
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

    /** @var int */
    private $price;

    /** @var int */
    private $fee;

    /** @var boolean */
    private $isPaid;

    /** @var string|null */
    private $paymentId;

    /** @var string|null */
    private $requestId;

    /** @var string|null */
    private $referenceNumber;

    /** @var string|null */
    private $transactionCode;

    public function __construct(int $statusCode, array $result)
    {
        $this->statusCode = $statusCode;

        if ($this->success()) {
            $this->paymentReqId = $result['uid'];
            $this->price = $result['price'];
            $this->fee = $result['fee'];
            $this->isPaid = $result['is_paid'];
            $this->paymentId = $result['payment']['id'];
            $this->requestId = $result['payment']['request_id'];
            $this->referenceNumber = $result['payment']['reference_number'];
            $this->transactionCode = $result['payment']['transaction_code'];
        } else {
            $this->code = $result["status"];
            $this->detail = $result["error"];
            // $this->extras = $result["extras"];
        }
    }

    public function success(): bool
    {
        return $this->statusCode === 201 ||
               $this->statusCode === 200;
    }

    public function paymentReqId(): string
    {
        return $this->paymentReqId;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function fee(): int
    {
        return $this->fee;
    }

    public function isPaid(): bool
    {
        return $this->isPaid;
    }

    public function paymentId(): string
    {
        return $this->paymentId;
    }

    public function requestId(): string
    {
        return $this->requestId;
    }

    public function referenceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function transactionCode(): string
    {
        return $this->transactionCode;
    }

    public function error(): Error
    {
        return new Error($this->code, $this->detail, $this->extras);
    }
}
