<?php
/**
 * The template for displaying search forms in required+ Foundation
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.1.0
 */
?>
<!-- START: searchform.php -->
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<label class="hide" for="s"><?php _e( 'Search for:', 'requiredfoundation' ); ?></label>
    <div class="row collapse">
      	<div class="two mobile-one columns">
        	<input type="submit" id="searchsubmit" value="<?php _e( 's', 'requiredfoundation' ); ?>" class="postfix button">
      	</div>
    	<div class="ten mobile-three columns">
        	<input type="text" value="" name="s" id="s" placeholder="<?php _e( 'Search', 'requiredfoundation' ); ?>">
      	</div>
    </div>
</form>
<!-- END: searchform.php -->