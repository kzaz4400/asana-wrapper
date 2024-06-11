<?php

namespace kzaz4400\AsanaWrapper\views;

use kzaz4400\AsanaWrapper\config\WebsiteSettings;

$base_url = '/';

require '_head.php';
?>


    <header class="container">
        <nav style="justify-content:center;">
            <h1>
                <a href="<?= empty($base_url) ? '/' : $base_url ?>">
                    <?= $_title ?>
                </a>
            </h1>
        </nav>
    </header>
    <main class="container">
        <div>
            <p><?= $_error ?></p>
        </div>
        <button type="submit" onclick="history.back()">
            <i class="fas fa-backspace"></i>&nbsp;元のページに戻る
        </button>
        <?php
        if ($_debug === true): ?>
            <section class="debug container">
                <article>
                    <h5>debug</h5>
                    <span>request</span>
                    <?= var_dump($this->request) ?>
                    <span>response</span>
                    <?= var_dump($this->response) ?>
                    <span>session</span>
                    <?= var_dump($this->session->getAll()) ?>


                </article>
            </section>
        <?php
        endif; ?>
    </main>

    <?php
require '_footer.php';
