<?php

get_template_part('templates/top', 'page'); ?>

<section id="layout">
    <div class="row">

        <?php

        set_layout('single', true);


        while (have_posts()) : the_post(); ?>

            <article <?php post_class(); ?>>

				<h2 class="entry-title"><?php the_title(); ?></h2>

				<?php
				if (strcmp(DfdThemeSettings::get('thumb_inner_disp'), '1') === 0) {
					if (has_post_thumbnail()) {
						$thumb = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url($thumb, 'full'); //get img URL
						if (DfdThemeSettings::get('post_thumbnails_width') && DfdThemeSettings::get('post_thumbnails_height')) {
							$article_image = aq_resize($img_url, DfdThemeSettings::get('post_thumbnails_width'), DfdThemeSettings::get('post_thumbnails_height'), true);
						} else {
							$article_image = aq_resize($img_url, 1200, 500, true);
						}
						?>
						<div class="post-media clearfix">
							<div class="entry-thumb">
								<img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
							</div>
						</div>
					<?php
					}
				}
				?>

				<div class="entry-content">
                                                
					<?php     
                                        
                                        if(!get_post_format()) {
                                            get_template_part($post->ID, 'standard');
                                            the_content();
                                        } elseif (has_post_format('video')) {
                                            get_template_part('templates/post', 'video');
                                            the_content();
					} elseif (has_post_format('gallery')) {
                                            get_template_part('templates/post', 'gallery');
                                            the_content();
					} elseif (has_post_format('quote')) {
                                            get_template_part('templates/post', 'quote');
					} elseif (has_post_format('audio')) {
                                            get_template_part('templates/post', 'audio');
                                            the_content();
                                        }
				 ?>

				</div>

				<?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'dfd'), 'after' => '</p></nav>')); ?>
				
				<?php 
					get_template_part('templates/entry-meta-post')
				?>

            </article>

        <?php endwhile; ?>
		
		

        <?php	if (strcmp(DfdThemeSettings::get("autor_box_disp"),'1') === 0) {
			get_template_part('templates/author-box');
        }

        comments_template();


        set_layout('single', false);

        ?>

    </div>
</section>

