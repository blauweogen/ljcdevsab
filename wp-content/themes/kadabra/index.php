<?php get_template_part('templates/top', 'page'); ?>

<section id="layout">
    <div class="row">

        <?php
        set_layout('archive');

        get_template_part('templates/content');

        set_layout('archive', false);

        ?>

    </div>
</section>