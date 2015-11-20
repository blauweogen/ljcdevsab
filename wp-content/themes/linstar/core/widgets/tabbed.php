<?php


class widget_tabs extends WP_Widget {
	function widget_tabs() {
		$widget_ops = array( 'description' => 'Most Popular, Recent, Comments, Tags' , 'id_base' => 'tabbed'  );
		$this->WP_Widget( 'tabbed','Tabbed  ', $widget_ops );
	}
	function widget( $args, $instance ) {
		
		global $king;
		
		extract($args);
		
		print( $before_widget );
	
	?>
		<div id="tabs">
		
			<ul class="tabs">
				<li class="active"><a href="#tab1"><?php _e( 'Popular' , 'king' ) ?></a></li>
				<li><a href="#tab2"><?php _e( 'Recent' , 'king' ) ?></a></li>
				<li><a href="#tab3"><?php _e( 'Tags' , 'king' ) ?></a></li>
			</ul>

			<div id="tab1" class="tab_container" style="display: block;">
				<ul class="recent_posts_list">
					<?php $king->popular_posts() ?>	
				</ul>
			</div>
			<div id="tab2" class="tab_container">
				<ul class="recent_posts_list">
					<?php $king->last_posts()?>	
				</ul>
			</div>
			<div id="tab3" class="tab_container tagcloud">
				<ul class="tags">
				<?php 
					$tags = get_tags(array('largest' => 8,'number' => 25,'orderby'=> 'count', 'order' => 'DESC' ));
					foreach( $tags as $tag ){
				?>
										
					<li>
						<a href="<?php echo get_tag_link($tag->term_id); ?>">
							<?php echo esc_attr( $tag->name ); ?> (<?php echo esc_attr( $tag->count ); ?>)
						</a>
					</li>
					
				<?php	
					}
				?>
					</ul>
			</div>

		</div><!-- .widget /-->
<?php
	
		print( $after_widget );
	
	}
	
	function form( $instance ) {
	?>
	
		<p>
		<br>
		<span class="warning"></span>
		<label>There are no options for this widget. </label>
		<br>
		</p>
		
	<?php	
	}
}

add_action('widgets_init', create_function('', 'return register_widget("widget_tabs");'));


?>
