<?php

declare(strict_types=1);

namespace HttpProvider;

use HttpProvider\Interfaces\{ClientInterface, ResponseInterface};

class HttpProvider
{
    private ClientInterface $client;

    private ResponseInterface $response;

    private string $base_uri;

    private array $requests = [];

    private array $params = [];

    public function __construct(
        ClientInterface $client,
        ResponseInterface $response,
        array $config = [],
        string $config_uri = ''
    ) {
        $this->client   = $client;
        $this->response = $response;

        if (false === empty($config)) {
            $this->config($config, $config_uri);
        }
    }

    public function config(array $config, string $config_uri): self
    {
        $this->base_uri = $config['base_uri'][$config_uri];
        $this->requests = $config['requests'];

        if (isset($config['params'])) {
            $this->params = $config['params'];
        }

        return $this;
    }

    public function __call(string $request, array $args): ResponseInterface
    {
        $args       = $args[0] ?? [];
        $method     = $this->requests[$request]['method'];
        $endpoint   = $this->requests[$request]['endpoint'];

        $params     = array_merge($this->params, $args);
        $body       = $params['body'] ?? null;

        $formater   = new UriFormatter();
        $url        = $formater->makeUri($this->base_uri, $endpoint, $params);

        return $this->client->attachResponse($this->response)
        ->setUrl($url)
        ->setMethod($method)
        ->setParams($params)
        ->setBody($body)
        ->send();
    }
}
