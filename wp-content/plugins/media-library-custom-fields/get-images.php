<?php
/**
 * Returns array of image URLs, links and other attributes.
 * Call this function from yout theme or plugin.
 *
 * @since  1.0.0
 * @access public
 * @param  array $attr
 * @return array
 * Required input parameters: array size => image size, include => single image id or comma delimited list of image id's.
 * Optional input parameters: numberposts => max qty of records to return. Default = -1 (all).
 */
function abcfmlcf_get_images( $attr ){

/**
 * Sample input parameters
 *      $attr = array(
 *          'size' => 'thumbnail',
 *          'include'     => '121,122,123'
 *      );
 */
    /* Default parameters. */
    $defaults = array(
        'size'        => 'thumbnail',
        'include'     => '0',
        'numberposts' => -1
    );

    /* Merge the defaults with user input.  */
    $attr = wp_parse_args( $attr, $defaults );
    extract( $attr );

    $args = array(
        'include'           => $include,
        'post_status'       => 'inherit',
        'post_type'         => 'attachment',
        'post_mime_type'    => 'image',
        'orderby'           => 'post__in',
        'numberposts'       => $numberposts
        );

    $items = array();
    $item = array();
    $imgSrc = '';

    //Get all attachements
    $attachments = get_posts( $args );
    if ( empty( $attachments ) ) { return $items; }

    //Return images data
    foreach ( $attachments as $attachment ) {
        $imgSrc = wp_get_attachment_image_src( $attachment->ID, $size, false );

        if(!empty($imgSrc)){
            $item['imgUrl'] = $imgSrc[0];
            $item['w'] = $imgSrc[1];
            $item['h'] = $imgSrc[2];
            $item['linkUrl'] = get_post_meta($attachment->ID, '_abcfmlcf_custom_url', true);
            $item['linkTarget'] = get_post_meta($attachment->ID, '_abcfmlcf_custom_url_target', true);
            $item['alt'] = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            $item['title'] = wptexturize($attachment->post_title);
            $item['desc'] = wptexturize($attachment->post_content);
            $item['cap1'] = wptexturize($attachment->post_excerpt);
            $item['cap2'] = wptexturize(get_post_meta($attachment->ID, '_abcfmlcf_caption2', true));
            $item['cap3'] = wptexturize(get_post_meta($attachment->ID, '_abcfmlcf_caption3', true));
            $item['cap4'] = wptexturize(get_post_meta($attachment->ID, '_abcfmlcf_caption4', true));
            $item['setNo'] = wptexturize(get_post_meta($attachment->ID, '_abcfmlcf_set_no', true));
            $item['imgID'] = $attachment->ID;
            $item['onclickJS'] = get_post_meta($attachment->ID, '_abcfmlcf_onclick_js', true);
            $items[] = $item;
         }
    }
      return $items;
}