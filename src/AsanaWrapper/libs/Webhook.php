<?php

namespace kzaz4400\AsanaWrapper\libs;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use kzaz4400\AsanaWrapper\errors\ConnectionException;

/**
 * WebHook
 */
class Webhook
{

    /**
     * @var Client
     */
    private Client $client;

    
    /**
     * @var \GuzzleHttp\Client
     */
    private \GuzzleHttp\Client $connection;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->connection = $client->guzzle_client;
    }

    /**
     * ワークスペースとリソースを指定して該当するウェブフックを全て取得
     * 引数について詳しくはasanaのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getwebhooks
     *
     * @param string|null $workspace_id
     * @param string|null $resource_id
     * @throws ConnectionException
     * @return array|false
     */
    public function getWebhooks(string $workspace_id = null, string $resource_id = null): false|array
    {
        $query = [
            'workspace' => $this->client->workspace['gid'] ?? $workspace_id,
            'resource'  => $this->client->resource['gid'] ?? $resource_id,
        ];

        $url = 'webhooks?';
        $url .= http_build_query($query);

        try {
            $response = $this->connection->get($url);
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        if (empty($array['data'])) {
            return false;
        }

        return (array)$array['data'];
    }

    /**
     * webhook_idを指定して該当するウェブフックを取得
     * 引数について詳しくはasanaのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getwebhook
     *
     * @param string $webhook_id
     * @throws ConnectionException
     * @return array|false
     */
    public function getWebhook(string $webhook_id): false|array
    {
        try {
            $response = $this->connection->get('webhooks/' . $webhook_id);
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        if (empty($array['data'])) {
            return false;
        }

        return (array)$array['data'];
    }

    /**
     * WebHookのIDを指定して該当するWebhookを更新
     * 引数について詳しくはasanaのドキュメントを参照してください
     * @see https://developers.asana.com/reference/updatewebhook
     *
     * @param string           $webhook_id
     * @param string[]|array[] $filters resource_type resource_subtype action fields[]
     * @return array|false
     * @throws ConnectionException
     */
    public function updateWebhook(string $webhook_id, array $filters,): false|array
    {
        $body = [
            'data' => [
                'filters' => [
                    $filters,
                ],
            ],
        ];
        try {
            $json = json_encode($body, JSON_THROW_ON_ERROR);
            $response = $this->connection->put('webhooks/' . $webhook_id, ['body' => $json]);
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        if (empty($array['data'])) {
            return false;
        }

        return (array)$array['data'];
    }

    /**
     * プロジェクトを指定してウェブフックを登録する
     * 詳しくはasanaのドキュメントを参照してください
     * @see            https://developers.asana.com/reference/createwebhook
     *
     * @param string           $target_url
     * @param string           $project_id
     * @param string[]|array[] $filters resource_type resource_subtype action fields[]
     * @return false|array
     * @throws ConnectionException
     */
    public function registerWebhookAtProject(string $target_url, string $project_id, array $filters): false|array
    {
        $body = [
            'data' => [
                'target'   => $target_url,
                'resource' => $project_id,
                'filters'  => [
                    $filters,
                ],
            ],
        ];

        try {
            $json = json_encode($body, JSON_THROW_ON_ERROR);
            $response = $this->connection->post('webhooks', ['body' => $json]);
            return (array)json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }
    }

    /**
     * webhook_idを指定して該当するウェブフックを削除
     * 引数について詳しくはasanaのドキュメントを参照してください
     * @see https://developers.asana.com/reference/deletewebhook
     *
     * @param string $webhook_id
     * @return bool
     * @throws ConnectionException
     */
    public function deleteWebhook(string $webhook_id): bool
    {
        try {
            $response = $this->connection->delete('webhooks/' . $webhook_id);
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        if (!empty($array['data'])) {
            return false;
        }
        return true;
    }


}