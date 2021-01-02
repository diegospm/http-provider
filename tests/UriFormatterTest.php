<?php

declare(strict_types=1);

namespace HttpProvider\Tests;

use \HttpProvider\UriFormatter;
use \PHPUnit\Framework\TestCase;

class UriFormatterTest extends TestCase
{
    private const BASE_URI = 'https://api.github.com';

    private const ASSERT_URI = 'https://api.github.com/users/diegospm?q=any';

    public function testMakeUri(): void
    {
        $formater = new UriFormatter();

        $params = array(
          'q'     => 'any',
          'user'  => 'diegospm',
        );

        $this->assertEquals(self::ASSERT_URI, $formater->makeUri(self::BASE_URI, 'users/:user', $params));
    }
}
