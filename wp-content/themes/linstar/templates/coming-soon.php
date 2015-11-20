<?php
/**
 * (c) www.devn.co
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<link href="<?php echo THEME_URI; ?>/assets/js/comingsoon/animations.min.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" media="screen" href="<?php echo THEME_URI; ?>/assets/js/comingsoon/coming.css" type="text/css" />
<script src="<?php echo THEME_URI; ?>/assets/js/comingsoon/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo THEME_URI; ?>/assets/js/comingsoon/jquery.bcat.bgswitcher.js"></script>

<div id="bg-body"></div>
<!--end -->
<div class="site_wrapper">
	<div class="comingsoon_page">
		<div class="container">
			<div class="topcontsoon">
				<img src="<?php echo THEME_URI; ?>/assets/images/logo.png" alt="" />
				<div class="clearfix">
				</div>
				<h5>
					<?php _e('We\'re Launching Soon', KING_DOMAIN ); ?>
				</h5>
			</div>
			<!-- end section -->
			<div class="countdown_dashboard">
				<div class="dash day_dash">
					<span class="dash_title">
						days
					</span>
					<div class="digit">
						0
					</div>
					<div class="digit">
						0
					</div>
					<div class="digit">
						0
					</div>
				</div>
				<div class="dash hour_dash">
					<span class="dash_title">
						hrs
					</span>
					<div class="digit">
						0
					</div>
					<div class="digit">
						0
					</div>
				</div>
				<div class="dash min_dash">
					<span class="dash_title">
						min
					</span>
					<div class="digit">
						0
					</div>
					<div class="digit">
						0
					</div>
				</div>
				<div class="dash last sec_dash">
					<span class="dash_title">
						sec
					</span>
					<div class="digit">
						0
					</div>
					<div class="digit">
						0
					</div>
				</div>
			</div>
			<!-- end section -->
			<div class="clearfix"></div>
			<div class="socialiconssoon">
				<p>
					<?php _e("Our website is under construction. We'll be here soon with our new awesome site. Get best experience with this one.", KING_DOMAIN ); ?>
				</p>
				<div class="clearfix marb4"></div>
				<form name="myForm" action="" onSubmit="return validateForm();" method="post">
					<input type="text" name="email" class="newslesoon" value="Enter email..." onFocus="if (this.value == 'Enter email...') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Enter email...';}" >
					<input type="submit" value="Submit" class="newslesubmit">
				</form>
				<div class="clearfix"></div>
				<?php king::socials(); ?>
			</div>
			<!-- end section -->
		</div>
	</div>
</div>
<!-- ######### JS FILES ######### -->
<script type="text/javascript" src="<?php echo THEME_URI; ?>/assets/js/comingsoon/countdown.js"></script>
<!-- animations -->
<script src="<?php echo THEME_URI; ?>/assets/js/comingsoon/animations.min.js" type="text/javascript"></script>
<script type="text/javascript">

		var timeTarget =  {
	
		/*=======Start Config Target=======*/
		
			year: 2019,
			month: 3,
			day: 30,
			hour: 24,
			min: 60,
			sec: 1,
		
		
		/*=======End Config=======*/
		
		version: '@version',
		diff: null,
		refresh: 1000,
		easing: 'linear',
		dash: [
			{
				key: 'year', duration: 950}
			,
			{
				key: 'day', duration: 950}
			,
			{
				key: 'hour', duration: 950}
			,
			{
				key: 'min', duration: 950}
			,
			{
				key: 'sec', duration: 750}
		],
		// you may provide callback capabilities
		onEnd: $.noop
	};


	var srcBgArray = ["http://gsrthemes.com/aaika/fullwidth/js/comingsoon/img-slider-1.jpg",
		   			"http://gsrthemes.com/aaika/fullwidth/js/comingsoon/img-slider-2.jpg",
		   			"http://gsrthemes.com/aaika/fullwidth/js/comingsoon/img-slider-3.jpg"];
		   			
	$(document).ready(function() {
		$('#bg-body').bcatBGSwitcher({
			urls: srcBgArray,
			alt: 'Full screen background image',
			links: true,
			prevnext: true
		});
	}
					 );
	function validateForm() {
		var x = document.forms["myForm"]["email"].value;
		var atpos = x.indexOf("@");
		var dotpos = x.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
			alert("Not a valid e-mail address");
			return false;
		}
	}

	(function($) {
		"use strict";
		$('.countdown_dashboard').countdown();
		$('.stop').on('click', function(e){
			e.preventDefault();
			$('.countdown_dashboard').data('countdown').stop();
		}
		);
	}
	)(jQuery);
</script>
