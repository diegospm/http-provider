<?php

declare(strict_types=1);

namespace HttpProvider\Tests;

use \HttpProvider\Interfaces\ResponseInterface;
use \HttpProvider\HttpProvider;
use \HttpProvider\Clients\Guzzle;
use \HttpProvider\Responses\JsonResponse;
use \PHPUnit\Framework\TestCase;

class HttpProviderTest extends TestCase
{
    private HttpProvider $http;

    private array $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config   = \json_decode(\file_get_contents(__DIR__ . '/github-api.json'), true);
        $this->http     = new HttpProvider(new Guzzle(), new JsonResponse(), $this->config, 'production');
    }

    public function testConfig(): void
    {
        $this->assertInstanceOf(HttpProvider::class, $this->http->config($this->config, 'production'));
    }

    public function testSend(): void
    {
        $this->assertInstanceOf(ResponseInterface::class, $this->http->getUser(['user' => 'diegospm']));
    }
}
