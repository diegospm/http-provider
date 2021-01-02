<?php

declare(strict_types=1);

use \HttpProvider\HttpProvider;
use \HttpProvider\Clients\Guzzle;
use \HttpProvider\Responses\JsonResponse;

class PaymentExample
{
    public function __construct()
    {
        $this->http = new HttpProvider(new Guzzle(), new JsonResponse());
    }

    public function createOrder(string $provider, array $headers, array $data)
    {
        $this->http->config($this->getConfigFromFile($provider), 'production');

        return $this->http->createOrder([
            'json'      => $data, // body for api service
            'headers'   => $headers // headers for api service
        ]);
    }

    private function getConfigFromFile(string $filename)
    {
        return \json_decode(\file_get_contents(__DIR__ . "/{$filename}.json"), true);
    }
}
