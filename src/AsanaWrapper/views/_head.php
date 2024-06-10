<?php

namespace kzaz4400\AsanaWrapper\views;

use kzaz4400\AsanaWrapper\config\WebsiteSettings;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= $_title ?? '' ?>&nbsp;|&nbsp;<?= WebsiteSettings::TITLE ?></title>
    <link rel="icon" href="data:image/x-icon;,">
    <link rel="stylesheet" href="https://unpkg.com/sanitize.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"/>
    <link rel="stylesheet" href="css/pico.min.css"/>
</head>

<body>