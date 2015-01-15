<?php
/**
 * Template Name: Carrere page template
 * Description: A Page Template for dance styles
 *
 * If there aren't any other templates present to
 * display content, it falls back to index.php
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.1.0
 */
$post_id = get_the_ID();
$pagemeta = get_post_meta( $post_id );
get_header(); ?>

	<!-- Row for main content area -->
	<div id="content" class="row">

		<div id="main" class="nine columns" role="main">
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

		<aside id="sidebar" class="three columns" role="complementary">
			<div class="sidebar-box">
				<div id="supplementary_shop" class="row">
					<?php dynamic_sidebar( 'carrers' );  ?>
				</div>
			</div><!-- #supplementary -->
		</aside><!-- /#sidebar -->
	</div><!-- End Content row -->
<div class="row">
	<div class="six columns">
		<h3><?php echo types_render_field( "left-hand-column-header", array( ) ); ?></h3>


		<?php 
			wp_reset_query();

			$query = new WP_Query( array( 'post_type'=>'job', 'orderby' => 'menu_order', 'order' => 'asc' ) ); 

		    while ($query->have_posts()) : $query->the_post();

		    $postmeta = get_post_meta( $post->ID );
		?>
		<div class="job_wrap">
			<h4><?php echo the_title(); ?></h4>
			<?php the_excerpt( ); ?>
		</div>	
		
		<?php endwhile; ?>
		
		<?php wp_reset_query(); ?>

	</div>
	<div class="six columns">
		<h3><?php echo types_render_field( "right-hand-column-header", array( ) ); ?></h3>
		<ul id="carrerslistkey">
			<?php
				$titlesarray = $pagemeta['wpcf-reasons-to-work-title'];
				$contentarray = $pagemeta['wpcf-reasons-to-work-content'];
				$content = array_map(null, $titlesarray, $contentarray);
				foreach ($content as $entry) {
				    printf('<li><h4>%s</h4></li>', $entry[0]);
				}
			?>
		</ul>		
	</div>
</div>
<?php get_footer(); ?>
