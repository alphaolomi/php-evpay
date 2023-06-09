<?php

namespace Alphaolomi\Evpay;

class EvPayService
{
    const VERSION = '1.0.0';

    protected array $config = [];

    public function __construct(array $config = [])
    {
        if (!array_key_exists('username', $config)) {
            throw new \InvalidArgumentException('You must provide a username.');
        }
        if (!array_key_exists('environment', $config)) {
            $config['environment'] = 'testing';
        }
        $this->config = $config;
    }
}
