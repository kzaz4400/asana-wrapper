<?php

namespace kzaz4400\AsanaWrapper\views;

?>
<div class="msg--errors">
    <article>
        <?php
        foreach ($_errors as $error): ?>
            <h3><?= $error ?></h3>
        <?php
        endforeach; ?>
    </article>
</div>
