<?php

namespace kzaz4400\AsanaWrapper\libs;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use kzaz4400\AsanaWrapper\errors\ConnectionException;
use kzaz4400\AsanaWrapper\errors\NotPropertyExistsException;

/**
 * task
 */
class Task
{
    /**
     * @var string
     */
    private string $project_id;

    /**
     * @return string
     */
    public function getProjectId(): string
    {
        return $this->project_id;
    }

    /**
     * @param string $project_id
     * @return void
     */
    public function setProjectId(string $project_id): void
    {
        $this->project_id = $project_id;
    }

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
     * タスクIDを指定してサブタスク一覧を取得する
     * 取得したい項目は第二引数で指定可能
     * 項目については詳しくはASANAのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getsubtasksfortask
     *
     * @param string   $task_id
     * @param string[] $options
     * @return array|false
     * @throws ConnectionException
     */
    public function getSubTaskByTaskId(string $task_id, array $options = []): false|array
    {
        //default opt_fields
        if (empty($options)) {
            $options = [
                'name',
                'completed',
                'permalink_url',
                'projects.name',
                'assignee.name',
            ];
        }

        $opt_fields = implode(',', $options);
        $request_url = 'tasks/' . $task_id . '/subtasks?opt_fields=' . $opt_fields;

        try {
            $response = $this->connection->get($request_url);
            $array = (array)json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        if (empty($array)) {
            return false;
        }
        return $array['data'];
    }

    /**
     * タスクIDを指定してタスクを単一取得する
     * 取得したい項目は第二引数で指定可能
     * 項目については詳しくはASANAのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getsubtasksfortask
     *
     * @param string   $task_id
     * @param string[] $options
     * @return array|false
     * @throws ConnectionException
     */
    public function getTaskByTaskId(string $task_id, array $options = []): false|array
    {
        //default opt_fields
        if (empty($options)) {
            $options = [
                'name',
                'completed',
                'permalink_url',
                'projects.name',
                'assignee.name',
            ];
        }

        $opt_fields = implode(',', $options);
        $request_url = 'tasks/' . $task_id . '?opt_fields=' . $opt_fields;

        try {
            $response = $this->connection->get($request_url);
            $array = (array)json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        if (empty($array)) {
            return false;
        }
        return $array['data'];
    }

    /**
     * タスク名を指定してタスクを単一取得する
     * 取得したい項目は第二引数で指定可能
     * 項目については詳しくはASANAのドキュメントを参照してください
     * @see  https://developers.asana.com/reference/getsubtasksfortask
     *
     * @uses $project_id
     * 事前にプロジェクトIDをインスタンスにセットすること
     * @param string   $task_name
     * @param string[] $options
     * @return array|false
     * @throws ConnectionException
     * @throws NotPropertyExistsException
     */
    public function getTaskByName(string $task_name, array $options = []): array|false
    {
        if (empty($this->project_id)) {
            throw new NotPropertyExistsException('Project ID not set');
        }

        //default opt_fields
        if (empty($options)) {
            $options = [
                'name',
                'completed',
                'permalink_url',
                'projects.name',
                'assignee.name',
            ];
        }

        $opt_fields = implode(',', $options);
        $request_url = 'projects/' . $this->project_id . '/tasks?opt_fields=' . $opt_fields;

        try {
            $response = $this->connection->get($request_url);
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        //プロジェクト名があるか検索
        $i = array_search($task_name, array_column($array['data'], 'name'), true);
        if ($i !== false) {
            return (array)$array['data'][$i];
        }

        return false;
    }

    /**
     * タスクを作成（タスク渡したい値は引数で指定）
     * 引数の配列の中身はasanaのドキュメントを確認してください
     * @see https://developers.asana.com/reference/createtask
     *
     * @param string[]|array[] $body
     * @return array
     * @throws ConnectionException
     */
    public function createTask(array $body): array
    {
        try {
            $json = json_encode($body, JSON_THROW_ON_ERROR);
            $response = $this->connection->post('tasks', ['body' => $json]);
            return (array)json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }
    }


    /**
     * タスクIDを指定してタスクを更新
     *  引数の配列の中身はasanaのドキュメントを確認してください
     * @see https://developers.asana.com/reference/updatetask
     *
     * @param string           $task_id
     * @param string[]|array[] $body
     * @throws ConnectionException
     * @return array
     */
    public function updateTask(string $task_id, array $body): array
    {
        try {
            $json = json_encode($body, JSON_THROW_ON_ERROR);
            $response = $this->connection->put('tasks/' . $task_id, ['body' => $json]);
            return (array)json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }
    }

    /**
     * タスクIDを指定してタスクを削除
     * 詳細はasanaのドキュメントを確認してください
     * @see https://developers.asana.com/reference/deletetask
     *
     * @param string $task_id
     * @return true
     * @throws ConnectionException
     */
    public function deleteTask(string $task_id): true
    {
        try {
            $response = $this->connection->delete('tasks/' . $task_id);
            if ($response->getStatusCode() !== 200) {
                throw new ConnectionException('Connection error: delete task failed');
            }
            return true;
        } catch (GuzzleException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }
    }

}