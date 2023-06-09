<?php

use Alphaolomi\Evpay\EvPayService;

it('can instantiate EvPayService', function () {
    $ev = new EvPayService(['username'=> 'username']);
    expect($ev)->toBeTruthy()->and($ev)->toBeInstanceOf(EvPayService::class);
});
