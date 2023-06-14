<?php

namespace TosanSoha\Sama;

use Illuminate\Support\Facades\Http;

class Request
{
    /** @var string */
    private $token;

    /** @var int */
    private $amount;

    /** @var string */
    private $clientId;

    /** @var string */
    private $callbackUrl;

    /** @var string */
    private $mobile;

    public function __construct(string $token, int $amount)
    {
        $this->token = $token;
        $this->amount = $amount;
    }

    public function send(): RequestResponse
    {
        $url = 'https://app.sama.ir/api/stores/services/deposits/guaranteed/';

        $data = [
            'price' => $this->amount,
            'buyer_phone' => $this->mobile,
            'client_id' => $this->clientId,
            'callback_url' => $this->callbackUrl
        ];

        $response = Http::asJson()
                  ->acceptJson()
                  ->withHeaders(['Authorization': 'Api-Key '.$this->token,])
                  ->post($url, $data);

        return new RequestResponse($response->json());
    }

    public function clientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function callbackUrl(string $callbackUrl): self
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    public function mobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }
}
