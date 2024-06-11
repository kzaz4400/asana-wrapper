<?php

namespace kzaz4400\AsanaWrapper\libs;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use kzaz4400\AsanaWrapper\errors\ConnectionException;

/**
 * project
 */
class Project
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
     * プロジェクト名を指定して該当するプロジェクトの配列を返す
     * 取得したい項目は第二引数で指定可能
     * 項目については詳しくはASANAのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getprojects
     *
     * @param string   $project_name
     * @param string[] $options
     * @return array|false
     * @throws ConnectionException
     */
    public function getProjectByName(string $project_name, array $options = []): false|array
    {
        //default opt_fields
        if (empty($options)) {
            $options = [
                'name',
                // 'completed',
                'permalink_url',
            ];
        }

        $opt_fields = implode(',', $options);
        $request_url = 'projects?opt_fields=' . $opt_fields;

        try {
            $response = $this->connection->get($request_url);
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        //プロジェクト名があるか検索
        $i = array_search($project_name, array_column($array['data'], 'name'), true);
        if ($i !== false) {
            return (array)$array['data'][$i];
        }
        return false;
    }


    /**
     * プロジェクトIDを指定して該当するプロジェクトの配列を返す
     * 取得したい項目は第二引数で指定可能
     * 項目については詳しくはASANAのドキュメントを参照してください
     * @see https://developers.asana.com/reference/getproject
     *
     * @param string   $project_id
     * @param string[] $options
     * @return array
     * @throws ConnectionException
     */
    public function getProjectById(string $project_id, array $options = []): array
    {
        //default opt_fields
        if (empty($options)) {
            $options = [
                'name',
                // 'completed',
                'permalink_url',
            ];
        }

        $opt_fields = implode(',', $options);
        $request_url = 'projects/' . $project_id . '?opt_fields=' . $opt_fields;

        try {
            $response = $this->connection->get($request_url);
            $array = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            throw new ConnectionException('Connection error: ' . $e->getMessage());
        }

        return (array)$array['data'];
    }

}