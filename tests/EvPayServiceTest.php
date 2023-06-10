<?php

declare(strict_types=1);

namespace Alphaolomi\Evpay\Tests;

use Alphaolomi\Evpay\EvPayService;
use PHPUnit\Framework\TestCase;

final class EvPayServiceTest extends TestCase
{
    public function testCanBeCreatedFromValidCredential(): void
    {

        $service = EvPayService::make([
            'username' => 'alphaolomi',
            'environment' => 'testing',
        ]);

        $this->assertInstanceOf(
            EvPayService::class,
            $service
        );
    }
}
