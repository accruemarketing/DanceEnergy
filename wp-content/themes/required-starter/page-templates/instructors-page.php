<?php
/**
 * Template Name: Instructors Landing Page Template
 * Description: A Page Template without a sidebar
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.2.0
 */

get_header(); ?>

	<!-- Row for main content area -->
	<div id="content" class="row">

		<div id="main" class="twelve columns" role="main">

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

				<?php
					wp_reset_query();
					$query = new WP_Query( array( 'post_type'=>'instructor', 'orderby' => 'menu_order') ); 
					while ($query->have_posts()) : $query->the_post();
					$postmeta = get_post_meta( $post->ID );
				?>
				<div class="instructor_wrap">
					<div class="img_wrap"><img width="150" height="200" src="<?php echo $postmeta['wpcf-profile-photo-instructor'][0]; ?>" title="profile picture <?php echo $postmeta['wpcf-instructor-name'][0]; ?>"></div>
					<h2><?php echo the_title(); ?></h2>
					<a href="<?php echo get_permalink(); ?>" class="button instructors">Read More</a>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>

			</div>

		</div><!-- /#main -->

	</div><!-- End Content row -->

<?php get_footer(); ?>