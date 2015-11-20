
<?php get_template_part('templates/top','forum'); ?>

<section id="layout">
    <div class="row">

        <?php
        set_layout('pages');

        get_template_part('templates/content', 'page');

        set_layout('pages', false);

        ?>

    </div>
</section>