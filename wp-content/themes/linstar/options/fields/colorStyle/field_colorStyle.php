<?php
class king_options_colorStyle extends king_options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since king_options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since king_options 1.0
	*/
	function render(){
		
	?>
	<div id="style-selector" class="inOptions">
		<ul class="styles" id="list-style-colors">     
		    <li>
		    	<a href="#" title="Default"><span class="pre-color-skin0">Default</span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="none" />
		    </li>
		    <li>
		    	<a href="#" title="Blue"><span class="pre-color-skin1"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="blue" />
		    </li>
		    <li>
		    	<a href="#" title="Red"><span class="pre-color-skin2"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="red" />
		    </li>
		    <li>
		    	<a href="#" title="Green"><span class="pre-color-skin3"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="green" />
		    </li>
		    <li>
		    	<a href="#" title="Cyan"><span class="pre-color-skin4"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="cyan" />
		    </li>
		    <li>
		    	<a href="#" title="Orange"><span class="pre-color-skin5"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="orange" />
		    </li>
		    <li>
		    	<a href="#" title="Light Blue"><span class="pre-color-skin6"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="lightblue" />
		    </li>
		    <li>
		    	<a href="#" title="Pink"><span class="pre-color-skin7"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="pink" />
		    </li>
		    <li>
		    	<a href="#" title="Purple"><span class="pre-color-skin8"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="purple" />
		    </li>
		    <li>
		    	<a href="#" title="Bridge"><span class="pre-color-skin9"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="bridge" />
		    </li>
		    <li>
		    	<a href="#" title="Slate"><span class="pre-color-skin10"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="slate" />
		    </li>
		    <li>
		    	<a href="#" title="Yellow"><span class="pre-color-skin11"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="yellow" />
		    </li>
		    <li>
		    	<a href="#" title="Dark Red"><span class="pre-color-skin12"></span></a>
		    	<br />
		    	<input type="radio" name="devn[colorStyle]" value="darkred" />
		    </li>
		</ul>
	</div>	

	<script type="text/javascript">
		(function($){
			$('#list-style-colors li').click(function(e){
				if( e.target.nodeName == 'INPUT' ){
					e.target.checked = true;
					return true;	
				}
				$(this).find('input').attr({ checked : true });
				e.preventDefault();
			});
			<?php
				
				if( !isset( $this->value ) ){
					echo '$("#list-style-colors input").eq(0).attr({checked:true})';	
				}else{
					if( $this->value == 'none' ){
						echo '$("#list-style-colors input").eq(0).attr({checked:true})';	
					}else{
					?>
					$("#list-style-colors input").each(function(){
						if( this.value == '<?php echo esc_html( $this->value ); ?>' )
							this.checked = true;
					});
					<?php	
					}
				}
				
			?>
		})(jQuery);
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
		
		wp_enqueue_style('styleSwitcher');
		
	}//function
	
}//class
?>