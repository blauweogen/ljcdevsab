<?php
	$comments_count = wp_count_comments();
?>
<span class="entry-comments">
	<a href="<?php the_permalink(); ?>#comments" rel="author" class="fn">
		<i class="icon-bubble-comment-1"></i><?php comments_number( '0', '1', '%' ); ?> 
	</a>
</span>