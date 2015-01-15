<?php
/**
 * The template for displaying all single instructor posts.
 *
 * This is the template that displays all single posts by default.
 * Please note that this is the WordPress construct of posts
 * and that other 'posts' on your WordPress site will use a
 * different template.
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.3.0
 */

get_header(); ?>

	<!-- Row for main content area -->
	<div id="main_instructor" class="row">

		<div class="twelve columns" role="main">

			<div class="post-box">

				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php 
					$postmeta = get_post_meta( $post->ID );
					?>
					<div class="row">
						<div class=" four columns instrcutorimg">
							<h3><?php echo $postmeta['wpcf-instructor-name'][0]; ?></h3> 
							<img src="<?php echo $postmeta['wpcf-profile-photo-instructor'][0]; ?>" title="profile picture <?php echo $postmeta['wpcf-instructor-name'][0]; ?>">
						</div>
						<div class="eight columns instrcutorbio">
							<h4>Speciality: <?php  echo $postmeta["wpcf-instructor-specialty"][0]; ?></h4>
							<?php echo types_render_field("instructor-bio", array( )); ?>
						</div>
					</div>
					<div class="row">
						<div class="four columns prevnext">
							<span class="nav-instruc"><a href="/about-us/meet-instructors/">Back to Meet Our Instructors</a></span>
						</div>
						<div class="eight columns">
							<nav class="nav-single prevnext">
								<span class="six columns"><?php previous_post_link( '%link', '< %title' ); ?></span>
								<span class="six columns"><?php next_post_link( '%link', '%title >' ); ?></span>
							</nav><!-- .nav-single -->
						</div>
					</div>

					<?php if($postmeta["wpcf-instructor-testimonial-video-link"][0] != ''){ ?>
					<div class="row">
						<div class="twelve columns">
						<div class="videoWrapper">						
							<object width="700" height="560">
							  <param name="movie" value="https://www.youtube.com/v/<?php echo $postmeta["wpcf-instructor-testimonial-video-link"][0]; ?>?controls=0&version=3&rel=0&showinfo=0"></param>
							  <param name="allowFullScreen" value="true"></param>
							  <param name="allowScriptAccess" value="always"></param>
							  <embed src="https://www.youtube.com/v/<?php echo $postmeta["wpcf-instructor-testimonial-video-link"][0]; ?>?controls=0&version=3&rel=0&showinfo=0" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="360"></embed>
							</object>
						</div>	
						</div>
					</div>
					<?php } ?>



				<?php endwhile; ?>

			</div>

		</div><!-- /#main -->



	</div><!-- End Content row -->

<?php get_footer(); ?>