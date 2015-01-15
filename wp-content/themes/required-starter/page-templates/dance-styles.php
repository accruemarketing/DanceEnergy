<?php
/**
 * Template Name: Dance styles template
 * Description: A Page Template for dance styles
 *
 * If there aren't any other templates present to
 * display content, it falls back to index.php
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.1.0
 */

get_header(); ?>

	<!-- Row for main content area -->
	<div id="content" class="row">

		<div id="main" class="nine columns" role="main">
			<div class="post-box">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

			<?php if ( function_exists( 'required_pagination' ) ) {
				required_pagination();
			} ?>
			</div>

		</div><!-- /#main -->

		<aside id="sidebar" class="three columns" role="complementary">
			<div class="sidebar-box">
				<?php get_sidebar('dance-style'); ?>
			</div>
		</aside><!-- /#sidebar -->
	</div><!-- End Content row -->
		<?php
			$post_id = get_the_ID();
		    $pagemeta = get_post_meta( $post_id );
		    $currentTerm = $pagemeta['wpcf-dance-style-category'][0];

			wp_reset_query();
			$query = new WP_Query( array( 'post_type'=>'dance-style', 'taxonomy'=>'category', 'term' => $currentTerm, 'orderby' => 'menu_order', 'order' => 'asc' ) ); 
		    while ($query->have_posts()) : $query->the_post();
		    $postmeta = get_post_meta( $post->ID );
		?>
			<div class="row classintrodancestyle">
				<div class="four columns vidwrap">
					<?php 
						if( $postmeta['wpcf-youtube-video-id'][0] != '' ){ 
					?>
					<div class="videoWrapper">						
						<object width="640" height="360">
						  <param name="movie" value="https://www.youtube.com/v/<?php echo $postmeta['wpcf-youtube-video-id'][0]; ?>?controls=0&version=3&rel=0&showinfo=0"></param>
						  <param name="allowFullScreen" value="true"></param>
						  <param name="allowScriptAccess" value="always"></param>
						  <embed src="https://www.youtube.com/v/<?php echo $postmeta['wpcf-youtube-video-id'][0]; ?>?controls=0&version=3&rel=0&showinfo=0" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="360"></embed>
						</object>
					</div>
					<?php }elseif ($postmeta['wpcf-image-url-dance-style'][0]!='') { ?>
						<img src="<?php echo $postmeta['wpcf-image-url-dance-style'][0]; ?>" alt="<?php echo the_title(); ?>">
					<?php } ?>
				</div>
				<div class="eight columnns">
					<!-- this is where you should insert your content of the posts -->
			       	<h2><?php echo the_title(); ?></h2>
			        <p><?php echo strip_tags( $postmeta['wpcf-short-description-dance-style'][0] ); ?></p>
			        <a class="button" href="<?php echo the_permalink(); ?>">Read more...</a>
				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>

<?php get_footer(); ?>