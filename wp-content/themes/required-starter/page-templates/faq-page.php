<?php
/**
 * Template Name:FAQ Page
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
      		<btst-accordion>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

						<?php
							wp_reset_query();
							$query = new WP_Query( array( 'post_type'=>'frec-quests', 'orderby' => 'menu_order') ); 
						    while ($query->have_posts()) : $query->the_post();
						    $postmeta = get_post_meta( $post->ID );
						?>
							<div class="row classintrodancestyle">
								 <div class="twelve columnns">
									<!-- this is where you should insert your content of the posts -->
							       	<btst-pane title="<h2><?php echo the_title(); ?></h2>">
							       		<div><?php the_content(); ?></div>
        							</btst-pane>
								</div>
							</div>
						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
				<?php endwhile; // end of the loop. ?>
    		</btst-accordion>

			</div>

		</div><!-- /#main -->

	</div><!-- End Content row -->

<?php get_footer(); ?>