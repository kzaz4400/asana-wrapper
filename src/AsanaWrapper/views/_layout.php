<?php

namespace kzaz4400\AsanaWrapper\views;

?>

<?= $this->render('_head', ['_title' => $_title]) ?>

<?= $this->render('_global_nav', ['_base_url' => $base_url]) ?>


    <main class="container">
        <?php
        if (!empty($_errors)): ?>
            <?= $this->render('_errors', ['errors' => $_errors]) ?>
        <?php
        endif; ?>
        <?php
        if (!empty($_alerts)): ?>
            <?= $this->render('_alert', ['alerts' => $_alerts]) ?>
        <?php
        endif; ?>
        <div class="grid">

            <?= $_content ?>

        </div>

        <?php
        if ($debug === true): ?>
            <section class="debug container">
                <article>
                    <h5>debug</h5>
                    <span>$_template_path</span>
                    <?= var_dump($_path) ?>
                    <span>view->defaults['request']</span>
                    <?= var_dump($this->defaults['request']) ?>
                    <span>view->defaults['request_uri']</span>
                    <?= var_dump($this->defaults['request_uri']) ?>
                    <span>view->defaults['base_url']</span>
                    <?= var_dump($this->defaults['base_url']) ?>
                    <span>$_variables</span>
                    <?= var_dump($_debug_info) ?>

                </article>
            </section>
        <?php
        endif; ?>

    </main>

<?= $this->render('_footer') ?>