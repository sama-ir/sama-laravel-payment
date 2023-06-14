<?php

use TosanSoha\Sama\SamaGateway;

if (! function_exists('sama')) {
    function sama(): SamaGateway
    {
        return new SamaGateway();
    }
}
