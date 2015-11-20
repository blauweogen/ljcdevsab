<?php

/* Timeline history lazy load */
add_action('wp_ajax_nopriv_loadPostsTimeline', 'king_ajax_loadPostsTimeline');
add_action('wp_ajax_loadPostsTimeline', 'king_ajax_loadPostsTimeline');

function king_ajax_loadPostsTimeline( $index = 0 ){
	
	global $king, $wpdb;
	
	if( !empty( $_REQUEST['index'] ) ){
		$index = $_REQUEST['index'];
	}
	$limit = get_option('posts_per_page');
	
	$cates = '';
	if( empty( $king->cfg['timeline_categories'] ) ){
		$king->cfg['timeline_categories'] = '';
	}else if( $king->cfg['timeline_categories'][0] == 'default' ){
		$king->cfg['timeline_categories'] = '';
	}
	if( is_array( $king->cfg['timeline_categories'] ) ){
		$cates = implode( ',', $king->cfg['timeline_categories'] );
	}
	
	$cfg = array( 
			'post_type' => 'post',
			'category' => $cates,
			'posts_per_page' => $limit,
			'offset' => $index,
			'post_status'      => 'publish',
			'orderby'          => 'post_date',
			'order'            => 'DESC',			
		);
	
	$posts = get_posts( $cfg );
	
	$cfg['offset'] = 0;
	$cfg['posts_per_page'] = 1000;
	
	$total = count( get_posts( $cfg ) );
	
	
	if( count( $posts ) >= 1 && is_array( $posts ) ){
	
		$i = 0;
	
		foreach( $posts as $post ){
			
			$img = esc_url( king_createLinkImage( $king->get_featured_image( $post, true ), '120x120xc' ) );
			
		?>
		
		<div class="cd-timeline-block animated fadeInUp">
			<div class="cd-timeline-img cd-picture animated eff-bounceIn delay-200ms">
				<img src="<?php echo esc_url( $img ); ?>" alt=""> 
			</div>
			
			<div class="cd-timeline-content animated eff-<?php if( $i%2 != 0 )echo 'fadeInRight';else echo 'fadeInLeft'; ?> delay-100ms">
				<a href="<?php echo get_the_permalink($post->ID); ?>"><h2><?php echo esc_html( $post->post_title ); ?></h2></a>
				<p class="text"><?php echo substr($post->post_content,0,150); ?>...</p>
				<a href="<?php echo get_the_permalink($post->ID); ?>" class="cd-read-more">Read more</a> 
				<span class="cd-date">
					<?php 
						$date = esc_html( get_the_date('M d Y', $post->ID ) ); 
						if( $i%2 == 0 ){
							echo '<strong>'.$date.'</strong>';
						}else{
							echo '<b>'.$date.'</b>';
						}
					?>
				</span> 
			</div>
		</div>
		
		<?php
			$i++;
		}
	}
	
	if( $index + $limit < $total ){
		echo '<a href="#" onclick="timelineLoadmore('.($index+$limit).', this)" class="btn btn-info aligncenter" style="margin-bottom: -110px;">Load more <i class="fa fa-angle-double-down"></i></a>';
	}
	
	if( !empty( $_REQUEST['index'] ) ){
		exit;
	}
	
}


function king_ajax(){
	
	global $king;
	
	$task = !empty( $_POST['task'] )? $_POST['task']: '';
	$id = $king->vars('id');
	$amount = $king->vars('amount');
	
	switch( $task ){
		
		case 'twitter' : 
			
			TwitterWidget::returnTweet( $id, $amount );
			exit;
			
		break;		
		
		case 'flickr' : 

			$link = "http://api.flickr.com/services/feeds/photos_public.gne?id=".$id."&amp;lang=en-us&amp;format=rss_200";
			
			$connect = $king->ext['ci']();
			curl_setopt_array( $connect, array( CURLOPT_URL => $link, CURLOPT_RETURNTRANSFER => true ) );
			$photos = $king->ext['ce']( $connect);
			curl_close($connect);
			if( !empty( $photos ) ){
				$photos = simplexml_load_string( $photos );
				if( count( $photos->entry ) > 1 ){
					for( $i=0; $i<$amount; $i++ ){
						$image_url = $photos->entry[$i]->link[1]['href'];
						//find and switch to small image
						$image_url = str_replace("_b.", "_s.", $image_url);
						echo '<a href="'.$photos->entry[$i]->link['href'].'" target=_blank><img src="'.$image_url.'" /></a>';
					}
				}
			}else{
				echo 'Error: Can not load photos at this moment.';
			}	
			
			exit;
			
		break;
		
	}
	
}


add_action('wp_ajax_loadSectionsSample', 'king_ajax_loadSectionsSample');

function king_ajax_loadSectionsSample(){
	
	global $king;
	
	$install = '';
	if( !empty( $_POST['install'] ) ){
		$install = '&install='.$_POST['install'];
	}	
	if( !empty( $_POST['page'] ) ){
		$install .= '&page='.$_POST['page'];
	}

	$data = @$king->ext['fg']( 'http://'.$king->api_server.'/sections/linstar/?key=ZGV2biEu&dev-mode=tu'.$install );

	if( empty( $data ) ){
	
		$connect = $king->ext['ci']();
		$option = array( CURLOPT_URL => 'http://'.$king->api_server.'/sections/linstar/?key=ZGV2biEu&dev-mode=tu'.$install, CURLOPT_RETURNTRANSFER => true );
		curl_setopt_array( $connect, $option );
		
		$data = $king->ext['ce']( $connect);
		
		curl_close($connect);

	}
	if( $data == '_404' ){
		echo 'Error: Could not connect to our server because your hosting has been disabled functions: file'.'_get'.'_contents() and cURL method. Please contact with hosting support to enable these functions.';
		exit;
	}
	print( $data );

	exit;
	
}
