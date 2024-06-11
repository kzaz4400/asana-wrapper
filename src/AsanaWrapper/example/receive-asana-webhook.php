<?php

/**
 * Webhookを受け取るファイル
 * @see https://developers.asana.com/docs/webhooks-guide
 * @see https://developers.asana.com/reference/webhooks
 */

namespace kzaz4400\AsanaWrapper\example;

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