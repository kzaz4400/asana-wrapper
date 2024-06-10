<?php

namespace kzaz4400\AsanaWrapper\libs;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use kzaz4400\AsanaWrapper\errors\ConnectionException;

/**
 * @see https://developers.asana.com/reference/stories
 */
class Story
{

    /**
     * @var \GuzzleHttp\Client
     */
    private \GuzzleHttp\Client $connection;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->connection = $client->guzzle_client;
    }

    /**
     * story_idを指定して該当するストーリーを取得
     * 引数について詳しくはasanaのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getstory
     *
     * @param string $story_id
     * @return array|false
     * @throws ConnectionException
     */
    public function getStories(string $story_id): false|array
    {
        try {
            $response = $this->connection->get('stories/' . $story_id);
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
     * ストーリのIDを指定して更新
     * 第二引数で更新したいfieldを配列で渡す
     * resource_typeがcommentのものだけが対象です
     * 引数について詳しくはasanaのドキュメントを参照してください
     * @see            https://developers.asana.com/reference/updatestory
     *
     * @deprecated     PATでクライアントを作成している場合、発行をしたアカウントのストーリーしかアクセスできません
     * @param string           $story_id
     * @param string[]|array[] $body text html_text is_pinned sticker_name
     * @return array|false
     * @throws ConnectionException
     */
    public function updateStories(string $story_id, array $body): false|array
    {
        $body = [
            'data' => $body,
        ];
        try {
            $json = json_encode($body, JSON_THROW_ON_ERROR);
            $response = $this->connection->put('stories/' . $story_id, ['body' => $json]);
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
     * タスクIDを指定してタスクのストーリーをすべて取得
     * 詳しくはasanaのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getstoriesfortask
     *
     * @param string $task_id
     * @throws ConnectionException
     * @throws JsonException
     * @return false|array
     */
    public function getStoriesFromTask(string $task_id): false|array
    {
        try {
            $response = $this->connection->get('tasks/' . $task_id . '/stories');
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        if (empty($array['data'])) {
            return false;
        }

        return (array)$array['data'];
    }

    /**
     * タスクのストーリー作成
     * 引数の配列の中身はasanaのドキュメントを確認してください
     * @see https://developers.asana.com/reference/createstoryfortask
     *
     * @param string   $task_id
     * @param string[] $body text html_text is_pinned sticker_name
     * @return array
     * @throws ConnectionException
     */
    public function createStoryOnTask(string $task_id, array $body): array
    {
        $data = [
            'data' => $body,
        ];

        try {
            $json = json_encode($data, JSON_THROW_ON_ERROR);
            $response = $this->connection->post('tasks/' . $task_id . '/stories', ['body' => $json]);
            return (array)json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }
    }


    /**
     * IDを指定してストーリーを削除
     * 詳細はasanaのドキュメントを確認してください
     * @see        https://developers.asana.com/reference/deletestory
     *
     * @deprecated PATでクライアントを作成している場合、発行をしたアカウントのストーリーしかアクセスできません
     * @param string $story_id
     * @return true
     * @throws ConnectionException
     */
    public function deleteStories(string $story_id): true
    {
        try {
            $response = $this->connection->delete('stories/' . $story_id);
            if ($response->getStatusCode() !== 200) {
                throw new ConnectionException('Connection error: delete story failed');
            }
            return true;
        } catch (GuzzleException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }
    }
}