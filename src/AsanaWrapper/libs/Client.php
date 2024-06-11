<?php

namespace kzaz4400\AsanaWrapper\libs;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use kzaz4400\AsanaWrapper\errors\ConnectionException;

/**
 * Clients connecting to the ASANA API
 */
class Client
{
    /**
     * @var array
     */
    public array $user;
    /**
     * @var array
     */
    public array $workspace;
    /**
     * @var \GuzzleHttp\Client
     */
    public \GuzzleHttp\Client $guzzle_client;

    /**
     * @var array|string[]
     */
    protected array $options;
    /**
     * @var string
     */
    protected string $access_token;
    /**
     * @var string
     */
    private string $base_url = 'https://app.asana.com/api/1.0/';

    /**
     * @var Client
     */
    private static Client $instance;

    /**
     * @param string $personal_access_token
     * @throws ConnectionException
     */
    private function __construct(string $personal_access_token)
    {
        $this->access_token = $personal_access_token;
        $this->options = [
            'headers'  => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->access_token,
            ],
            'base_uri' => $this->base_url,
        ];
        $this->guzzle_client = new \GuzzleHttp\Client($this->options);
        $this->checkMe();
    }


    /**
     * @param string $personal_access_token
     * @throws ConnectionException
     * @return self
     */
    public static function getInstance(string $personal_access_token): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($personal_access_token);
        }
        return self::$instance;
    }

    /**
     * Check if connection to ASANA API is possible
     * @throws ConnectionException
     * @return void
     */
    private function checkMe(): void
    {
        try {
            $response = $this->guzzle_client->request('GET', 'users/me?opt_pretty=true');
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        $user = [];
        //配列を１次元にする
        array_walk_recursive($array, static function ($value, $key) use (&$user) {
            $user[$key] = $value;
        });
        $this->user = $user;
        $this->workspace = $array['data']['workspaces'][0];
        // var_dump(get_object_vars($this));
    }
}