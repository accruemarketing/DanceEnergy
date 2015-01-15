<?php
/**
 * The template for displaying all single posts.
 *
 * This is the template that displays all single posts by default.
 * Please note that this is the WordPress construct of posts
 * and that other 'posts' on your WordPress site will use a
 * different template.
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.3.0
 */

global $post, $woocommerce, $product;

get_header('shop'); ?>

	<!-- Row for main content area -->
	<div id="content" class="row">

		<div id="main" class="" role="main">

			<div class="post-box">
			<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>
			</div>
		</div><!-- /#main -->
	</div><!-- End Content row -->

<?php get_footer(); ?>