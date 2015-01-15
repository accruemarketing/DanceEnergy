<?php

/**
 * Adds meta boxes to the post/pages
 *
 */
function dpArticleShare_meta_box_add() {
	global $dpArticleShare;

	$screens = $dpArticleShare['scope'];
	if(is_array($screens)) {
		foreach ( $screens as $screen ) {
			add_meta_box( 'dpArticleShare_article_share_side_meta', __('Article Social Share Settings', 'dpArticleShare'), 'dpArticleShare_article_share_side_display', $screen, 'side', 'core' );
		}
	}
}
add_action( 'add_meta_boxes', 'dpArticleShare_meta_box_add' );

function dpArticleShare_meta_box_save( $post_id ) {
	
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		)
	);

	// make sure data is set, if author has removed the field or not populated it, delete it
	update_post_meta( $post_id, 'dp_article_share_disable', wp_kses( $_POST['dp_article_share_disable'], $allowed ) );
	update_post_meta( $post_id, 'dp_article_share_position', wp_kses( $_POST['dp_article_share_position'], $allowed ) );
	update_post_meta( $post_id, 'dp_article_share_counter', wp_kses( $_POST['dp_article_share_counter'], $allowed ) );
	update_post_meta( $post_id, 'dp_article_share_tooltip', wp_kses( $_POST['dp_article_share_tooltip'], $allowed ) );
	update_post_meta( $post_id, 'dp_article_share_skin', wp_kses( $_POST['dp_article_share_skin'], $allowed ) );
	update_post_meta( $post_id, 'dp_article_share_counter_position', wp_kses( $_POST['dp_article_share_counter_position'], $allowed ) );
}
add_action( 'save_post', 'dpArticleShare_meta_box_save' );

function dpArticleShare_article_share_side_display( $post ) {
	global $wpdb, $table_prefix;
	
	$values = get_post_custom( $post->ID );
	$dp_article_share_disable = isset( $values['dp_article_share_disable'] ) ? $values['dp_article_share_disable'][0] : '0';
	$dp_article_share_position = isset( $values['dp_article_share_position'] ) ? $values['dp_article_share_position'][0] : '';
	$dp_article_share_counter = isset( $values['dp_article_share_counter'] ) ? $values['dp_article_share_counter'][0] : '';
	$dp_article_share_tooltip = isset( $values['dp_article_share_tooltip'] ) ? $values['dp_article_share_tooltip'][0] : '';
	$dp_article_share_skin = isset( $values['dp_article_share_skin'] ) ? $values['dp_article_share_skin'][0] : '';
	$dp_article_share_counter_position = isset( $values['dp_article_share_counter_position'] ) ? $values['dp_article_share_counter_position'][0] : '';
		
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
    <div id="misc-publishing-actions">
        <div class="misc-pub-section">
            <input type="checkbox" name="dp_article_share_disable" id="dp_article_share_disable" value="1" <?php echo ($dp_article_share_disable ? 'checked="checked"' : ''); ?> />
            <label for="dp_article_share_disable"><?php _e('Disable the icons for this post/page', 'dpArticleShare'); ?></label>
        </div>
        <div class="misc-pub-section">
            <label for="dp_article_share_position"><?php _e('Position', 'dpArticleShare'); ?></label>
            <select name='dp_article_share_position'>
                <option value=""><?php _e('Use Default','dpArticleShare'); ?></option>
                <option value="vertical" <?php echo ($dp_article_share_position == 'vertical' ? 'selected="selected"' : '')?>><?php _e('Vertical','dpArticleShare'); ?></option>
                <option value="vertical-inside" <?php echo ($dp_article_share_position == 'vertical-inside' ? 'selected="selected"' : '')?>><?php _e('Vertical Inside Content','dpArticleShare'); ?></option>
                <option value="horizontal-top" <?php echo ($dp_article_share_position == 'horizontal-top' ? 'selected="selected"' : '')?>><?php _e('Horizontal Top','dpArticleShare'); ?></option>
                <option value="horizontal-bottom" <?php echo ($dp_article_share_position == 'horizontal-bottom' ? 'selected="selected"' : '')?>><?php _e('Horizontal Bottom','dpArticleShare'); ?></option>
                <option value="horizontal-top-bottom" <?php echo ($dp_article_share_position == 'horizontal-top-bottom' ? 'selected="selected"' : '')?>><?php _e('Horizontal Top and Bottom','dpArticleShare'); ?></option>
            </select>
        </div>
        <div class="misc-pub-section">
            <label for="dp_article_share_skin"><?php _e('Skin', 'dpArticleShare'); ?></label>
            <select name='dp_article_share_skin'>
                <option value=""><?php _e('Use Default','dpArticleShare'); ?></option>
                <option value="light" <?php echo ($dp_article_share_skin == 'light' ? 'selected="selected"' : '')?>><?php _e('Light','dpArticleShare'); ?></option>
                <option value="dark" <?php echo ($dp_article_share_skin == 'dark' ? 'selected="selected"' : '')?>><?php _e('Dark','dpArticleShare'); ?></option>
                <option value="color" <?php echo ($dp_article_share_skin == 'color' ? 'selected="selected"' : '')?>><?php _e('Color','dpArticleShare'); ?></option>
                <option value="compact" <?php echo ($dp_article_share_skin == 'compact' ? 'selected="selected"' : '')?>><?php _e('Compact','dpArticleShare'); ?></option>
                <option value="flat" <?php echo ($dp_article_share_skin == 'flat' ? 'selected="selected"' : '')?>><?php _e('Flat','dpArticleShare'); ?></option>
            </select>
        </div>
        <div class="misc-pub-section">
            <label for="dp_article_share_counter"><?php _e('Counters', 'dpArticleShare'); ?></label>
            <select name='dp_article_share_counter'>
                <option value=""><?php _e('Use Default','dpArticleShare'); ?></option>
                <option value="yes" <?php echo ($dp_article_share_counter == "yes" ? 'selected="selected"' : '')?>><?php _e('Yes','dpArticleShare'); ?></option>
                <option value="no" <?php echo ($dp_article_share_counter == "no" ? 'selected="selected"' : '')?>><?php _e('No','dpArticleShare'); ?></option>
            </select>
        </div>
        <div class="misc-pub-section">
            <label for="dp_article_share_counter_position"><?php _e('Counter Position', 'dpArticleShare'); ?></label>
            <select name='dp_article_share_counter_position'>
                <option value=""><?php _e('Use Default','dpArticleShare'); ?></option>
                <option value="bottom" <?php echo ($dp_article_share_counter_position == "bottom" ? 'selected="selected"' : '')?>><?php _e('Bottom','dpArticleShare'); ?></option>
                <option value="right" <?php echo ($dp_article_share_counter_position == "right" ? 'selected="selected"' : '')?>><?php _e('Right','dpArticleShare'); ?></option>
            </select>
        </div>
        <div class="misc-pub-section no_border">
            <label for="dp_article_share_tooltip"><?php _e('Tooltip', 'dpArticleShare'); ?></label>
            <select name='dp_article_share_tooltip'>
                <option value=""><?php _e('Use Default','dpArticleShare'); ?></option>
                <option value="yes" <?php echo ($dp_article_share_tooltip == "yes" ? 'selected="selected"' : '')?>><?php _e('Yes','dpArticleShare'); ?></option>
                <option value="no" <?php echo ($dp_article_share_tooltip == "no" ? 'selected="selected"' : '')?>><?php _e('No','dpArticleShare'); ?></option>
            </select>
        </div>
    </div>
    
	<?php
}
