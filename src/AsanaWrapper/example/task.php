<?php

namespace kzaz4400\AsanaWrapper\example;

use kzaz4400\AsanaWrapper\errors\ConnectionException;
use kzaz4400\AsanaWrapper\errors\NotPropertyExistsException;
use kzaz4400\AsanaWrapper\libs\Client;
use kzaz4400\AsanaWrapper\libs\Project;
use kzaz4400\AsanaWrapper\libs\Task;

require_once '../../../vendor/autoload.php';

try {
    // AsanaWrapper
    $client = Client::getInstance(<ENV_PAT>);
    $task = new Task($client);
    $project = new Project($client);


    /**
     * Retrieve and update tasks by name
     * */
    // Retrieve a project by specifying the project name
    $res = $project->getProjectByName('<Project_name>');

    // Set the retrieved project ID to the task instance.
    $task->setProjectId($res['gid']);

    // Get a task by specifying the task name
    $res = $task->getTaskByName('<task_name>');

    // Update by specifying a task from the retrieved task ID.
    $body = [
        'data' => [
            'name' => '<modify task name>',
        ],
    ];
    $res = $task->updateTask($res['gid'], $body);
    var_dump($res);


    /**
     * Create task
     * */
    // Retrieve a project by specifying the project name
    $res = $project->getProjectByName('<Project_name>');

    // Set the retrieved project ID to the task instance.
    $task->setProjectId($res['gid']);
    $body = [
        'data' => [
            'name'     => '<task name>',
            'projects' => [empty($task->getProjectId()) ? '<project_id>' : $task->getProjectId()],
        ],
    ];
    $res = $task->createTask($body);
    var_dump($res);


    /**
     * Get by specifying a task name, get a list of subtasks, update subtasks by specifying their IDs
     * */
    // Retrieve a project by specifying the project name
    $res = $project->getProjectByName('<Project_name>');

    // Set the retrieved project ID to the task instance.
    $task->setProjectId($res['gid']);

    // Retrieve tasks by task name
    $res = $task->getTaskByName('<task name>');

    // Get subtasks by specifying the task ID
    $res = $task->getSubTaskByTaskId($res['gid']);

    // Subtask update
    $body = [
        'data' => [
            'completed' => 'true',
        ],
    ];
    $res = $task->updateTask($res['data'][0]['gid'], $body);
    var_dump($res);
} catch (ConnectionException|NotPropertyExistsException $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
    exit;
}