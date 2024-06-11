# asana-wrapper is a lightweight PHP library that manipulates the ASANA API.

![Static Badge](https://img.shields.io/badge/License-MIT-blue)
![GitHub Release](https://img.shields.io/github/v/release/kzaz4400/asana-wrapper)
![GitHub Downloads (all assets, all releases)](https://img.shields.io/github/downloads/kzaz4400/asana-wrapper/total)
![GitHub forks](https://img.shields.io/github/forks/kzaz4400/asana-wrapper)

<!--![PHP](https://img.shields.io/badge/-Php-white.svg?logo=php&style=for-the-badge)
![PHP STORM](https://img.shields.io/badge/--black.svg?logo=phpstorm&style=for-the-badge)
![docker](https://img.shields.io/badge/-Docker-1488C6.svg?logo=docker&style=for-the-badge)
![javascript](https://img.shields.io/badge/-Javascript-white.svg?logo=javascript&style=for-the-badge)
-->

## APIs that can be used

* Project
* Task
* Story
* Webhook

More information on the ASANA API

https://developers.asana.com/reference/rest-api-reference

## Why did we make it?

I had trouble with the official library, and I don't need something so sophisticated.

I wanted something that was quick and easy to use.

## Install

```
composer require kzaz4400/asana-wrapper
```

## Loading

```php
<?php

use kzaz4400\AsanaWrapper\libs\Client;
use kzaz4400\AsanaWrapper\libs\Project;
use kzaz4400\AsanaWrapper\libs\Story;
use kzaz4400\AsanaWrapper\libs\Task;
use kzaz4400\AsanaWrapper\libs\Webhook;

// AsanaWrapper instance
$client = Client::getInstance($_ENV['PAT']);

// Create an instance by injecting a client into the API class you want to use.
$project = new Project($client);
$task = new Task($client);
$webhook = new Webhook($client);
$story = new Story($client);
```

## A Simple Example

```php
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
```

## Webhook

Read more about Webhook

https://developers.asana.com/docs/webhooks-guide

### Example of receiving a webhook

```php
<?php

// If not Post, exit.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('http/1.1 403 Forbidden');
    exit;
}

//When WebHook is registered for the first time, the secret key passed is saved in a file and 200 is returned.
$headers = getallheaders();
if (isset($headers['X-Hook-Secret'])) {
    $sent_headers = 'X-Hook-Secret:' . $headers['X-Hook-Secret'];

    file_put_contents('PATH', $headers['X-Hook-Secret']);
    header('http/1.1 200 OK');
    header($sent_headers);
    exit;
}

// Ends at 204 if there is no Body
$request_body = file_get_contents('php://input');
if (empty($request_body)) {
    header('http/1.1 204 No Content');
    exit;
}

// Create a hash using the stored secret key and the JSON of the request body
$secret = file_get_contents('PATH');
$hash = hash_hmac('sha256', $request_body, $secret);

// Check that the hash created and the signature passed are identical.
if ($hash === $headers['X-Hook-Signature']) {
    // If the same, start processing.

    // processing

    //200 Return HTTP response and exit.
    header('HTTP/1.1 200 OK send bot');
    exit;
}

// 401 if not identical.
header('http/1.1 401 Unauthorized');
exit;
```

## Documentation

### ASANA API

https://developers.asana.com/reference/rest-api-reference

### This Library Documentation

https://kzaz4400.github.io/asana-wrapper/

## Contributing

Pull requests are welcome!! 😊

