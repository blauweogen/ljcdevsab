<section id="header" class="horizontal">
	<div class="header-wrap">
		<div class="row">
			<div class="columns twelve">
				<div class="header-col header-col-left">
					<?php get_template_part('templates/header/block', 'custom_logo_second'); ?>
				</div>

				<div class="header-col header-col-right header-col-right-widgets">
					<?php echo dfd_woocommerce_total_cart(); ?>
					<?php get_template_part('templates/header/block', 'search'); ?>
				</div>

				<div class="header-col header-col-fluid text-center">
					<?php get_template_part('templates/header/block', 'main_menu'); ?>

					<?php get_template_part('templates/header/block', 'responsive-menu'); ?>
				</div>
			</div>
		</div>
	</div>
</section>