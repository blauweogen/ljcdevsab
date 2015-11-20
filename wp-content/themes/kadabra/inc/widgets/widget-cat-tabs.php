<?php

require_once(dirname(__FILE__).'/widget.php');

class crum_cat_tabs_widget extends SB_WP_Widget {
	
	protected $widget_base_id = 'crum_cat_tabs';
	protected $widget_name = 'Widget: Cat tabs';
	
	protected $options;
	
	function __construct() {
		
		$this->widget_params = array(
			'description' => __('Add tabs widget with the posts of chosen category', 'dfd')
		);
		
		$this->options = array(
			array(
				'title', 'text', '', 
				'label' => __('Title', 'dfd'), 
				'input'=>'text', 
				'filters'=>'widget_title', 
				'on_update'=>'esc_attr',
			),

			// First column

			array(
				'first_col_title', 'text', '', 
				'label' => __('First col title', 'dfd'), 
				'input'=>'text', 
				'on_update'=>'esc_attr',
			),

			array(
				'first_col_categories', 'text', '', 
				'label' => __('First col categories (slugs)', 'dfd'), 
				'input'=>'text', 
				'on_update'=>'esc_attr',
			),

			array(
				'first_col_num', 'int', 5, 
				'label' => __('First col posts number', 'dfd'), 
				'input'=>'text', 
				'on_update'=>'esc_attr',
			),

			// Second column

			array(
				'second_col_title', 'text', '', 
				'label' => __('Second col title', 'dfd'), 
				'input'=>'text', 
				'on_update'=>'esc_attr',
			),

			array(
				'second_col_categories', 'text', '', 
				'label' => __('Second col categories (slugs)', 'dfd'), 
				'input'=>'text', 
				'on_update'=>'esc_attr',
			),

			array(
				'second_col_num', 'int', 5, 
				'label' => __('Second col posts number', 'dfd'), 
				'input'=>'text',
				'on_update'=>'esc_attr',
			),

			// Third column

			array(
				'third_col_title', 'text', '', 
				'label' => __('Third col title', 'dfd'), 
				'input'=>'text', 
				'on_update'=>'esc_attr',
			),

			array(
				'third_col_categories', 'text', '', 
				'label' => __('Third col categories (slugs)', 'dfd'),
				'input'=>'text', 
				'on_update'=>'esc_attr',
			),

			array(
				'third_col_num', 'int', 5, 
				'label' => __('Third col posts number', 'dfd'), 
				'input'=>'text',
				'on_update'=>'esc_attr',
			),
		);

		parent::__construct();
	}
	
	function widget($args, $instance) {
		extract( $args );
		$this->setInstances($instance, 'filter');
		
		$title = $this->getInstance('title');
		
		$uniqid = uniqid();
		
		$first_col_title = $this->getInstance('first_col_title');
		$first_col_categories = $this->getInstance('first_col_categories');
		$first_col_num = $this->getInstance('first_col_num');
		
		$second_col_title = $this->getInstance('second_col_title');
		$second_col_categories = $this->getInstance('second_col_categories');
		$second_col_num = $this->getInstance('second_col_num');
		
		$third_col_title = $this->getInstance('third_col_title');
		$third_col_categories = $this->getInstance('third_col_categories');
		$third_col_num = $this->getInstance('third_col_num');
		
		echo $before_widget;
		
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
		}
		
		?>

		<dl class="tabs contained horisontal">
			<dt></dt>
			<dd class="active"><a href="#first-p-tab-<?php echo $uniqid; ?>"><?php echo $first_col_title; ?></a></dd>
			<dt></dt>
			<dd><a href="#second-p-tab-<?php echo $uniqid; ?>"><?php echo $second_col_title; ?></a></dd>
			<dt></dt>
			<dd><a href="#third-p-tab-<?php echo $uniqid; ?>"><?php echo $third_col_title; ?></a></dd>
        </dl>

		<ul class="tabs-content contained folio-wrap clearfix cl">
            <li id="first-p-tab-<?php echo $uniqid; ?>Tab" class="active">
                <?php $this->tab_content($first_col_categories, $first_col_num); ?>
            </li>
            <li id="second-p-tab-<?php echo $uniqid; ?>Tab">
                <?php $this->tab_content($second_col_categories, $second_col_num); ?>
            </li>
            <li id="third-p-tab-<?php echo $uniqid; ?>Tab">
                <?php $this->tab_content($third_col_categories, $third_col_num); ?>
            </li>
        </ul>
		
		<?php
		echo $after_widget;
	}
	
	protected function tab_content($cat='', $post_count=5) {
		$query = new WP_Query(array(
			'category_name' => $cat,
			'posts_per_page' => $post_count,
		));
		
		if ($query->have_posts()) {
			while($query->have_posts()) {
				$query->the_post();
				
				?>

				<article class="hentry mini-news mini-news-background clearfix">
					<?php

					if (has_post_thumbnail()) {
						$thumb = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url($thumb, 'thumb'); //get img URL
						$article_image = aq_resize($img_url, 108, 108, true);
						?>

						<div class="entry-thumb">
							<img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
							<?php get_template_part('templates/entry-meta/hover-link-small'); ?>
						</div>

					<?php } else {  ?>
						<span class="icon-format"></span>
					<?php } ?>
					<div class="box-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
					<?php get_template_part('templates/entry-meta', 'widget'); ?>
				</article>
				<?php
			}
		}
		
		wp_reset_postdata();
	}
	
}