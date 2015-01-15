<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

?>
<div class="images">
	<?php
		 $postmeta = get_post_meta( $post->ID );

		if(isset($postmeta['wpcf-video-id'])){ ?>
			
		<iframe width="640" height="360" src="//www.youtube.com/embed/<?php echo $postmeta["wpcf-video-id"][0]; ?>?feature=player_embedded&autoplay=1&rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>

		<?php }else{

				if ( has_post_thumbnail() ) {
					
					$custom_url = get_post_meta( get_post_thumbnail_id( ), '_gallery_link_url', true );

					$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
					$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
					$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
						'title' => $image_title
						) );

					$attachment_count = count( $product->get_gallery_attachment_ids() );
					if ( $attachment_count > 0 ) {
						$gallery = '[product-gallery]';
					} else {
						$gallery = '';
					}

					if($custom_url != ''){
						
						echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $custom_url, $image_title, $image ), $post->ID );

					}else{
						
						echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $post->ID );
					}


				} else {

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $post->ID );

				}

			$attachment_ids = $product->get_gallery_attachment_ids();
		}

if ( $attachment_ids ) {
	?>
	<div class="thumbnails"><?php

		$loop = 0;
		$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

		foreach ( $attachment_ids as $attachment_id ) {
			$custom_url = get_post_meta( $attachment_id, '_gallery_link_url', true );

			$classes = array( 'zoom' );

			if ( $loop == 0 || $loop % $columns == 0 )
				$classes[] = 'first';

			if ( ( $loop + 1 ) % $columns == 0 )
				$classes[] = 'last';

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link )
				continue;

			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
			$image_class = esc_attr( implode( ' ', $classes ) );
			$image_title = esc_attr( get_the_title( $attachment_id ) );

			if($custom_url != ''){

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $custom_url, $image_title, $image ), $post->ID );

			}else{
				
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $post->ID );
			}
			$loop++;
		}

	?></div>
	<?php
}

?>
</div>
<div class="product_meta">
	<?php do_action( 'woocommerce_product_meta_start' ); ?>
	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
		<p class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span></p>
	<?php endif; ?>
	<?php echo $product->get_categories( ', ', '<p class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '</p>' ); ?>
	<?php echo $product->get_tags( ', ', '<p class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'woocommerce' ) . ' ', '</p>' ); ?>
	<?php do_action( 'woocommerce_product_meta_end' ); ?>


	<?php 
	/*$getcoursetype = $postmeta['wpcf-enrollment-course'][0];
	if ($getcoursetype === 'yes') {
		echo "<p><strong>Start date: </strong>" . $postmeta['wpcf-startdate'][0] . "</p>";
		echo "<p><strong>End date: </strong>" . $postmeta['wpcf-enddate'][0] . "</p>";
	}else{
		echo "<p><strong>" . $postmeta['wpcf-week-day'][0]. "</strong></p>";
	}
	echo "<p><strong>Start time:</strong> ". $postmeta['wpcf-starttime'][0] ."</p>";
	echo "<p><strong>End time:</strong> ". $postmeta['wpcf-endtime'][0] ."</p>";
	if($postmeta['wpcf-course-length'][0] > 0){	
		echo "<p><strong>Sessions: </strong>". $postmeta['wpcf-course-length'][0] ."</p>";
	}*/
	?>

</div>