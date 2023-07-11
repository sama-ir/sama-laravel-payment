<?php

namespace TosanSoha\Sama;

use Illuminate\Support\Facades\Http;

class Verification
{
    /** @var string */
    private $token;

    /** @var string */
    private $requestId;

    /** @var string */
    private $clientId;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function send(): VerificationResponse
    {
        $url = 'https://app.sama.ir/api/stores/services/deposits/guaranteed/payment/verify/';

        $data = [
            'request_id' => $this->requestId,
            'client_id' => $this->clientId
        ];

        $response = Http::asJson()
                    ->acceptJson()
                    ->withHeaders(['Authorization'=> 'Api-Key '.$this->token,])
                    ->post($url, $data);

        return new VerificationResponse($response->status(), $response->json());
    }

    public function clientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function requestId(string $requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }
}
