<?php

namespace kzaz4400\AsanaWrapper\views;

?>

<div class="msg--alerts">
    <?php
    foreach ($_alerts as $alert): ?>
        <p><?= $alert ?></p>
    <?php
    endforeach; ?>
</div>
