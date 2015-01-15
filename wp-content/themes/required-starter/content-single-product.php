<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<br>
<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row">

		</div>
		<div class="row">
			<div class="six columns">
				<?php
					$post_id = get_the_ID();
					$postmeta = get_post_meta( $post_id );
					$getcoursetype = $postmeta['wpcf-enrollment-course'][0];
					do_action( 'woocommerce_before_single_product_summary' );
					/**
					 * woocommerce_before_single_product_summary hook
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
				?>
			</div>
			<div class="summary entry-summary six columns">
				<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>
				<?php
					/**
					 * woocommerce_single_product_summary hook
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 */
					//do_action( 'woocommerce_single_product_summary' );

					the_content( );
					echo "<div class='meta_wrap'>";

						$getcoursetype = $postmeta['wpcf-enrollment-course'][0];
						if ($getcoursetype === 'yes') {
							echo "<p><span>Start date: </span>" . $postmeta['wpcf-startdate'][0] . "</p>";
							echo "<p><span>End date: </span>" . $postmeta['wpcf-enddate'][0] . "</p>";
						}else{
							echo "<p><span>" . $postmeta['wpcf-week-day'][0]. "</span></p>";
						}
						echo "<p><span>Start time:</span> ". $postmeta['wpcf-starttime'][0] ."</p>";
						echo "<p><span>End time:</span> ". $postmeta['wpcf-endtime'][0] ."</p>";
						if($postmeta['wpcf-course-length'][0] > 0){	
							echo "<p><span>Number of Sessions: </span>". $postmeta['wpcf-course-length'][0] ."</p>";
						}
						echo "<p><span>Price: </span> $" . $postmeta["_price"][0] . " plus GST</p>";
					echo "</div>";
					wc_get_template( 'single-product/temp_fix_before_launch.php' );

					/* UNCOMMENT THIS AFTER LAUNCH PLEASEEEE
						if ($getcoursetype != 'yes') {
							wc_get_template( 'single-product/price.php' );
						}else{
							$includesurl = get_stylesheet_directory().'/includes/enrollment.php';
							include_once $includesurl;
						}
					*/
				?>
			</div>
		</div>
	</div>

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 * remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
