<?php
class king_options_layouts extends king_options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since king_options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since king_options 1.0
	*/
	function render(){
		
		?>
		<ul>
		
		<?php king_available_blocks(); ?>
		
		</ul>
		
		<br />
		<br />
		<span><?php echo __('Tip: To use the layouts above, Go to <a href="'.SITE_URI.'/admin.php?page=visual-design" target=_blank>Visual Design</a> => Choose a page you want at left side => Look at top-right <span style="color:red">"Copy of"</span>','king'); ?>
		</span>
		
		<script type="text/javascript">
			jQuery('#layouts-list li').click(function(){
				if( confirm("<?php echo __('ARE YOU SURE?\n\nIf you delete this layout may some page will be lost layout and must use its parent layout','king'); ?>") )
				{
					jQuery.post(ajaxurl, {
						'action': 'loadLayout',
						"task": 'clearLayout',
						"name": 'general',
						"alias": this.id
					}, function (result) {});
					
					jQuery(this).animate({'width':10,'height':10,'margin-left':50},{'complete':function(){jQuery(this).remove();}});
					
				}	
			});
		</script>
		
	<?php	
			
	}//function
	
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since king_options 1.0
	*/
	function enqueue(){
		wp_enqueue_style('nhp-opts-jquery-ui-css');
		wp_enqueue_script(
			'nhp-opts-field-date-js', 
			king_options_URL.'fields/date/field_date.js', 
			array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
			time(),
			true
		);
		
	}//function
	
}//class
?>