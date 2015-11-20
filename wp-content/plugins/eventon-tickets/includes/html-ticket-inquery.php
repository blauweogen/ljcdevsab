<?php
/**
 * Inquires Section for front-end
 */

	if(!empty($txmeta['_allow_inquire']) && $txmeta['_allow_inquire'][0]=='yes'):
?>
<div class='evotx_inquery'>
	<em></em>
	<a class='evcal_btn evotx_INQ_btn'><?php echo eventon_get_custom_language($opt, 'evoTX_inq_01','Inquire before buy');?></a>
	<div class='evotxINQ_box' style='display:none'>
		<div class='evotxINQ_form' data-event_id='<?php echo $object->event_id;?>' data-ri='<?php echo $object->repeat_interval; ?>' data-err='<?php echo eventon_get_custom_language($opt, 'evoTX_inq_06','Required fields missing, please try again!');?>'>
			<p><label for=""><?php echo eventon_get_custom_language($opt, 'evoTX_inq_02','Your Name');?></label><input class='evotxinq_field' name='name' type="text"></p>
			<p><label for=""><?php echo eventon_get_custom_language($opt, 'evoTX_inq_03','Email Address');?></label><input class='evotxinq_field' name='email' type="text"></p>
			<p><label for=""><?php echo eventon_get_custom_language($opt, 'evoTX_inq_04','Question');?></label><textarea class='evotxinq_field' name='message' ></textarea></p>
			<p class='notif' data-notif='<?php echo eventon_get_custom_language($opt, 'evoTX_inq_05','All Fields are required.');?>'><?php echo eventon_get_custom_language($opt, 'evoTX_inq_05','All Fields are required.');?></p>
			<p><a class="evcal_btn evotx_INQ_submit"><?php echo eventon_get_custom_language($opt, 'evoTX_inq_07','Submit');?></a></p>
		</div>
		<div class='evotxINQ_msg' style='display:none'>
			<em></em>
			<p><?php echo eventon_get_custom_language($opt, 'evoTX_inq_08','GOT IT! -- We will get back to you as soon as we can.');?></p>
		</div>
	</div>
</div>
<?php
	endif;
?>