<?php
/**
 * Template Name: Frontpage Page Template
 * Description: A Page Template without a sidebar
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.2.0
 */

get_header(); ?>

	<!-- Row for main content area -->
	<div id="content" class="row">

		<div id="main" class="eight columns" role="main">

			<div class="post-box">
				
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php
						/**
						 * Seriously I never used comments on a page, what for?
						 */
						//comments_template( '', true );
					?>

				<?php endwhile; // end of the loop. ?>

			</div>

		</div><!-- /#main -->
		<aside id="sidebar" class="four columns" role="complementary">
			<div class="sidebar-box">
				    <?php dynamic_sidebar( 'home-sidebar' ); ?>
			</div>
		</aside><!-- /#sidebar -->
	</div><!-- End Content row -->

<?php get_footer(); ?>