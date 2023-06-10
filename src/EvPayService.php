<?php

declare(strict_types=1);

namespace Alphaolomi\Evpay;

class EvPayService
{
    const VERSION = '1.0.0';

    /**
     * @example
     *
     * ```php
     * $config = [
     *      'username' => 'your-username',
     *      'password' => 'your-password',
     *      'name' => 'E-learning Platform',
     *      'default_currency' => 'TZS',
     *      'default_country' => 'TZA',
     *      'default_product' => 'Monthly Subscription',
     *      'time_zone' => 'Africa/Dar_es_Salaam',
     *      'environment' => 'testing',
     *      'callback' => 'http://your-callback-url',
     *      'guzzle_options' => [
     *          'timeout' => 30,
     *          'base_uri' => 'http://test-dash.evmak.com/sandbox/'
     *      ],
     * ];
     * ```
     */
    protected array $config = [];

    protected string $baseUrl;

    protected array $headers = [];

    protected $client;

    const ENVIRONMENTS = [
        'testing' => 'http://test-dash.evmak.com/sandbox/',
        'production' => 'https://vodaapi.evmak.com/prd/',
    ];

    const SUPPORTED_APIS = [
        'Mpesa',
        'TigoPesa',
        'AirtelMoney',

    ];

    const MPESA = 'Mpesa';

    const TIGO_PESA = 'TigoPesa';

    const AIRTEL_MONEY = 'AirtelMoney';

    public function __construct(array $config = [])
    {
        if (! array_key_exists('username', $config)) {
            throw new \InvalidArgumentException('You must provide a username.');
        }
        if (array_key_exists('base_url', $config)) {
            $this->baseUrl = $config['base_url'];
        }
        if (! array_key_exists('environment', $config)) {
            $config['environment'] = 'testing';
        }

        $this->config = $config;

        $this->headers = array_merge($this->defaultHeaders(), $config['headers'] ?? []);

        $this->client = new \GuzzleHttp\Client(array_merge($this->defaultConfig(), $config['guzzle_options'] ?? []));
    }

    public static function make(array $config = []): self
    {
        return new static($config);
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function defaultConfig(): array
    {
        return [
            'timeout' => 30,
            'base_uri' => $this->resolveBaseUrl(),
        ];
    }

    public function get(string $uri, array $headers = [], array $config = [])
    {
        return $this->client->request('GET', $uri, [], $headers, $config);
    }

    public function post(string $uri, array $body = [], array $headers = [], array $config = [])
    {
        return $this->client->request('POST', $uri, $body, $headers, $config);
    }

    public function paymentRequest(string $uri, array $body = [], array $headers = [], array $config = [])
    {
        $apiTo = $this->ensureValidApi($body['network']);
        $amount = $this->ensureAmountIsValid($body['amount']);
        $mobileNo = $this->ensurePhoneNumberIsValid($body['mobileNo']);

        $payload = [
            'api_source' => $body['api_source'] ?? $this->config['name'],
            'api_to' => $apiTo,
            'amount' => $amount,
            'product' => $body['product'] ?? $this->config['default_product'],
            'callback' => $body['callback'] ?? $this->config['callback'],
            'hash' => $this->makeHash($body['api_source'], $body['api_to']),
            'user' => $body['user'] ?? $this->config['username'],
            'mobileNo' => $mobileNo,
            'reference' => $body['reference'] ?? $this->makeReference(),
        ];

        $response = $this->post($uri, $payload, $headers, $config);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function ensureValidApi(string $apiTo): string|bool
    {
        $apiTo = trim($apiTo);

        if (strlen($apiTo) < 1) {
            return false;
        }

        if (! in_array($apiTo, self::SUPPORTED_APIS)) {
            return false;
        }

        return $apiTo;
    }

    public function ensurePhoneNumberIsValid(string $mobileNo): string|bool
    {
        $mobileNo = trim($mobileNo);

        if (strlen($mobileNo) < 10) {
            return false;
        }

        if (substr($mobileNo, 0, 1) == '0') {
            $mobileNo = '255'.substr($mobileNo, 1);
        }

        if (substr($mobileNo, 0, 1) == '+') {
            $mobileNo = substr($mobileNo, 1);
        }

        if (strlen($mobileNo) != 12) {
            return false;
        }

        if (substr($mobileNo, 0, 3) != '255') {
            return false;
        }

        return $mobileNo;
    }

    public function ensureAmountIsValid(string $amount): float|bool
    {
        $amount = trim($amount);

        if (strlen($amount) < 1) {
            return false;
        }

        if (! is_numeric($amount)) {
            return false;
        }

        return (float) $amount;
    }

    /**
     * Make Hash
     *
     * @param  string  $apiTo
     */
    public function makeHash(string $username, string $password_or_date): string
    {
        // store current timezone
        $timezone = date_default_timezone_get();

        // set timezone to $config['timezone']
        date_default_timezone_set($this->config['timezone'] ?? 'Africa/Dar_es_Salaam');

        $hash = md5($username.'|'.date('d-m-Y'));

        // reset timezone to previous
        date_default_timezone_set($timezone);

        return $hash;
    }

    /**
     * Make Reference
     */
    public function makeReference(): string
    {
        $reference = $this->getReferencePrefix().$this->generateRandomDigits(5);

        return $reference;
    }

    /**
     * Get Reference Prefix
     */
    public function getReferencePrefix(): string
    {
        return $this->config['reference_prefix'] ?? 'EV';
    }

    /**
     * Generate Random Digits
     */
    private function generateRandomDigits(int $length = 5): string
    {
        return $this->generateRandom('integers', $length);
    }

    /**
     * Generate Random
     */
    private function generateRandom(string $type, int $length = 5): string
    {
        $characters = [
            'alpha' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'alphabets' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'digits' => '0123456789',
            'integers' => '0123456789',
        ];

        $charactersLength = strlen($characters[$type]);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
