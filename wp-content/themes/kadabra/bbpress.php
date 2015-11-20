
<?php get_template_part('templates/top','forum'); ?>

<section id="layout">
    <div class="row">
		<div class="nine columns">
        <?php
        set_layout('pages');

        get_template_part('templates/content', 'page');

        set_layout('pages', false);

        ?>
		</div>
		</div> <?php //@TODO: непонятный баг с лишним открытым дивом !!! ?>
		<div class="three columns">
			<?php dynamic_sidebar('sidebar-bbres-right');?>
		</div>
    </div>
</section>