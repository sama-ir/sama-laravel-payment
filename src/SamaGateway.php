<?php

namespace TosanSoha\Sama;

class SamaGateway
{
    /** @var string */
    private $token;

    /** @var int */
    private $amount;

    public function token(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function amount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function request(): Request
    {
        $token = $this->token ?: config('sama.token');
        return new Request($token, $this->amount);
    }

    public function verification(): Verification
    {
        $token = $this->token ?: config('sama.token');
        return new Verification($token);
    }
}
