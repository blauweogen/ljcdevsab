<script type="text/javascript">
document.write('<link rel="stylesheet" type="text/css" href="<?php echo THEME_URI; ?>/assets/css/color_switcher.css" title="switcher style" />');
</script>	
<script type="text/javascript" src="<?php echo THEME_URI; ?>/assets/js/styleswitcher.js"></script>	
<div id="style-selector">
	<div class="style-selector-wrapper">
		<span class="title">Choose Theme Options</span>
		<div>        
			<br />
			<br />
			
			<span class="title-sub2">20 Different Demos</span>
			
			<ul class="styles" style="border-bottom: none;">
			    <li><a href="<?php echo SITE_URI; ?>" class="demolinks<?php if( is_home() )echo ' active'; ?>"> Main</a></li>
			    <?php
				    for( $i = 1; $i < 20; $i++ ){
					    echo '<li><a href="'.SITE_URI.'/home/demo-version-'.$i.'" class="demolinks';
					    if( strpos( $_SERVER['REQUEST_URI'], '/home/demo-version-'.$i.'/' ) !== false ){
							echo ' active';   
					    }
					    echo '"> Demo '.$i.'</a></li>';
				    }
			    ?>
			</ul>
			
			<span class="title-sub2">Choose Layout:</span>
			
			<div class="styles" id="layouts-style-colors">     
				&nbsp;
				<input checked="" type="radio" name="userChoice" id="navRadio01" value="wide"> WIDE
				&nbsp;
				<input type="radio" name="userChoice" id="navRadio02" value="boxed"> BOXED
			</div>
			
			<!-- end Predefined Color Skins --> 
			
			<span class="title-sub2">BG Patterns for Boxed</span>
			
			<ul class="styles" id="style-switcher-bg" style="border-bottom: none;">     
			    <li><a href="#"><span class="bg-patterns1"></span></a></li>
			    <li><a href="#"><span class="bg-patterns2"></span></a></li>
			    <li><a href="#"><span class="bg-patterns3"></span></a></li>
			    <li><a href="#"><span class="bg-patterns4"></span></a></li>
			    <li><a href="#"><span class="bg-patterns5"></span></a></li>
			    <li><a href="#"><span class="bg-patterns6"></span></a></li>
			    <li><a href="#"><span class="bg-patterns7"></span></a></li>
			    <li><a href="#"><span class="bg-patterns8"></span></a></li>
			    <li><a href="#"><span class="bg-patterns9"></span></a></li>
			    <li><a href="#"><span class="bg-patterns10"></span></a></li>
			    <li><a href="#"><span class="bg-patterns11"></span></a></li>
			    <li><a href="#"><span class="bg-patterns12"></span></a></li>
			    
			</ul><!-- end BG Patterns --> 
			
			<a href="#" class="close icon-chevron-right" id="switcher-style-button"><i class="fa fa-cogs"></i></a>  
			    
		</div>
	</div>
</div>
