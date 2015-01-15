<?php
/**
 * Booking product add to cart
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce, $product;

if ( ! $product->is_purchasable() ) {
	return;
}
$post_id = get_the_ID();
$postmeta = get_post_meta( $post_id );
do_action( 'woocommerce_before_add_to_cart_form' ); 
if($postmeta['wpcf-enrollment-course'][0] == 'yes'){ ?>
<?php }else{ ?> 

<noscript><?php _e( 'Your browser must support JavaScript in order to make a booking.', 'woocommerce-bookings' ); ?></noscript>

<form class="cart" method="post" enctype='multipart/form-data'>

 	<div id="wc-bookings-booking-form" style="display:none">

 		<?php $booking_form->output(); ?>

 		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

 		<div class="wc-bookings-booking-cost" style="display:none"></div>

	</div>

	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

 	<button disabled="disabled" type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>

 	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

</form>
<?php } ?>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
