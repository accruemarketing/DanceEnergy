<?php
/**
 * Courses product realted classes add to cart loop
 *
 * @author 		Vimes1984
 * @package 	Dance energy/theme
 * @version     1.0.0
 */

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	global $post, $product;
	
	//set up the query
	$query = new WP_Query( array( 'post_type'=>'product', 'taxonomy'=>'product_cat', 'term' => 'group-classes', 'orderby' => 'name' ) ); 
	//query the query
	while ($query->have_posts()) : $query->the_post();
		//here we call our custom theme files! 
		?>

		<div class="individual_product">
		
				
		<?php wc_get_template( 'single-product/title-booking.php' ); ?>
		
		<?php wc_get_template( 'single-product/price.php' ); ?>
			
		<?php wc_get_template( 'single-product/add-to-cart/enrollment-add-to-cart.php' ); ?>

		</div>
 	<?

 		endwhile;
	
		wp_reset_query(); 

	?> 