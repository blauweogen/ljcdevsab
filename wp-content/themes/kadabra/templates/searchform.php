<?php $id_s = uniqid('s_'); ?>
<form role="search" method="get" id="<?php echo uniqid('searchform_'); ?>" class="form-search" action="<?php echo home_url('/'); ?>">
  <input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="<?php echo $id_s; ?>" class="search-query" placeholder="<?php _e('Search on site...', 'dfd'); ?>">
  <input type="submit" value="<?php _e('Search', 'dfd'); ?>" class="btn">
</form>