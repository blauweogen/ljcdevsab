<?php function crum_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment; ?>

<li <?php comment_class(); ?>>
    <div class="clearfix">

        <div class="avatar-box"><?php echo get_avatar($comment, $size = '80'); ?></div>

        <div class="ovh">
            <header class="comment-author vcard">
                <div class="left"><span class="author"><?php echo get_comment_author_link(); ?></span>
				<span class="date"><?php printf(__('%1$s, %2$s', 'dfd'), get_comment_date(), get_comment_time()); ?></span>
                <?php if (is_user_logged_in()) : ?>
				<span class="dop-link">
                    <?php edit_comment_link(__('(Edit)', 'dfd'), '', ''); ?>
                </span>
				<?php endif; ?>
				</div>
				<div class="reply">
					<?php echo comment_reply_link(array(
						'depth' => $depth,
						'max_depth' => $args['max_depth'], 
						'reply_text'=>'<i class="icon-reply-mail-2"></i>'
					)); ?>
				    <span class="reply_tooltip">Reply</span>
				</div>
	    
	    </header>

            <section class="comment-content">

                <?php if ($comment->comment_approved == '0') : ?>

                <div class="alert-box">
                    <?php _e('Your comment is awaiting moderation.', 'dfd'); ?>
                </div>

                <?php endif; ?>

                <?php comment_text(); ?>

            </section>
			
			<footer>
			</footer>
        </div>
    </div>

    <?php } ?>

<?php if (post_password_required()) : ?>
    <section id="comments">
        <div class="alert-box alert">
            <?php _e('This post is password protected. Enter the password to view comments.', 'dfd'); ?>
        </div>
    </section><!-- /#comments -->
    <?php endif; ?>


<?php if (have_comments()) : ?>
    <section id="comments">
        <h3 class="entry-title">
			<?php printf(_n('There are 1 comment on this post', 'There are %1$s comments on this post', get_comments_number(), 'dfd'), number_format_i18n(get_comments_number())); ?>
		</h3>

        <ol class="commentlist">
            <?php wp_list_comments(array('callback' => 'crum_comment')); ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>

        <nav class="page-nav">
            <span class="older"><?php previous_comments_link(__('Older', 'dfd')); ?></span>
            <span class="newer"><?php next_comments_link(__('Newer', 'dfd')); ?></span>
        </nav>

        <?php endif; // check for comment navigation ?>

        <?php if (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
        <?php endif; ?>

    </section><!-- /#comments -->

    <?php endif; ?>

<?php if (!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
    <?php endif; ?>


<?php if (comments_open()) : ?>

    <section id="respond">

        <h3 class="widget-title">
			<?php comment_form_title(__('Leave a comment to this post', 'dfd'), __('Leave a Reply to %s', 'dfd')); ?>
			<?php if (!is_user_logged_in()) : ?>
				<span><?php _e('Required fields are marked *', 'dfd'); ?></span>
			<?php endif; ?>
		</h3>

        <p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
        <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>

        <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'dfd'), wp_login_url(get_permalink())); ?></p>

        <?php else : ?>

        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

            <?php if (is_user_logged_in()) : ?>
            <p><?php printf(__('Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', 'dfd'), get_option('siteurl'), $user_identity); ?>
                <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php __('Log out of this account', 'dfd'); ?>"><?php _e('Log out &raquo;', 'dfd'); ?></a>
            </p>
            <?php else : ?>

                <input type="text" placeholder="<?php _e('Name', 'dfd'); if ($req) _e('*', 'dfd'); ?>" class="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?>>

                <input type="email" placeholder="<?php _e('Email (will not be published)', 'dfd'); if ($req) _e('*', 'dfd'); ?>" class="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?>>

                <input type="url" placeholder="<?php _e('Website', 'dfd'); ?>" class="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3">

            <?php endif; ?>

				<textarea rows="8" name="comment" id="comment" tabindex="4" placeholder="<?php _e('Comment', 'dfd'); ?>"></textarea>

            <p>
                <button name="submit" class="button" tabindex="5">
                    <i class="icon-edit-1"></i>
                    <?php _e('Submit Comment', 'dfd'); ?>
                </button>
            </p>

            <?php comment_id_fields(); ?>
            <?php do_action('comment_form', $post->ID); ?>
        </form>
        <?php endif; // If registration required and not logged in ?>
    </section>
<?php endif; // if you delete this the sky will fall on your head ?>