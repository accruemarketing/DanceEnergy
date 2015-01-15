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

get_header(); 

?>

	<!-- Row for main content area -->
	<div id="content" class="row">

		<div id="main" class="nine columns" role="main">

			<div class="post-box">

				<?php while ( have_posts() ) : the_post(); ?>

					<!-- START: content.php -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					    <header class="entry-header">
				            <?php
				             $chkbanner = get_post_meta($post->ID);
				             
				             if(isset($chkbanner["wpcf-page-title"][0]) && $chkbanner["wpcf-page-title"][0] != "" ){ ?>
				                <h1 class="entry-title"><?php echo $chkbanner["wpcf-page-title"][0]; ?></h1>
				            <?php }else{ ?>
				                <h1 class="entry-title"><?php the_title(); ?></h1>
				            <?php } 

				             if(isset($chkbanner["wpcf-sub-title"][0]) && $chkbanner["wpcf-sub-title"][0] != "" ){ ?>
				                <h3 class="entry-title"><?php echo $chkbanner["wpcf-sub-title"][0]; ?></h3>
				            <?php }else{ ?>
				                <h3 class="entry-title"><?php the_title(); ?></h3>
				            <?php } ?>
				        </header><!-- .entry-header -->
						<?php if ( 'post' == get_post_type() ) : ?>
						<div class="entry-meta">
							<?php required_posted_on(); ?>
							<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
							<span class="label radius secondary"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'requiredfoundation' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php _ex( 'Featured', 'Post format title', 'requiredfoundation' ); ?></a></span>
							<?php endif; ?>
						</div><!-- .entry-meta -->
						<?php endif; ?>
						<?php if ( is_search() ) : // Only display Excerpts for Search ?>
						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div><!-- .entry-summary -->
						<?php else : ?>
						<div class="entry-content">
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'requiredfoundation' ) ); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'requiredfoundation' ) . '</span>', 'after' => '</div>' ) ); ?>
						</div><!-- .entry-content -->
						<?php endif; ?>

					</article><!-- #post-<?php the_ID(); ?> -->
					<!-- END: content.php -->
		<footer class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : ?>
			<?php get_template_part('entry-meta', get_post_format() ); ?>
			<?php endif; ?>
		</footer><!-- #entry-meta -->
				<?php endwhile; ?>
				<?php

					$post_id = get_the_ID();
				    $pagemeta = get_post_meta( $post_id );
				    $currentTerm = $pagemeta['wpcf-dance-style-slug'][0];

					wp_reset_query();
					$query = new WP_Query( array( 'post_type'=>'product', 'taxonomy'=>'product_tag', 'term' => $currentTerm, 'orderby' => 'menu_order', 'order' => 'asc' ) );
					if($query->have_posts()){

					    while ($query->have_posts()) : $query->the_post();
					    $postmeta = get_post_meta( $post->ID );
					    $post_id_class = $query->post->ID;
					    $startdate = $postmeta['wpcf-startdate'][0];
					    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id_class ), 'single-post-thumbnail' );

				?>
						<div class="row classintrodancestyle styles">
							<div class="four columns imgwrap">
									<?php if (!$image) { ?>
											<img src="/wp-content/plugins/woocommerce/assets/images/placeholder.png" alt="<?php echo the_title(); ?>">
										<?php }else{ ?>
											<img src="<?php echo $image[0]; ?>" alt="<?php echo the_title(); ?>">
										<?php } ?>
							</div>
							<div class="eight columnns">
								<!-- this is where you should insert your content of the posts -->
						       	<div class="twelve columns">
						       		<h2 class="styletitle"><?php echo the_title(); ?></h2>
						       		<h5 class="startdate">Start Date: <?php echo $startdate; ?></h5>
						       		<p><?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?></p>
						        	<p><?php //echo myTruncate(the_content(), 30); ?></p>
						       	</div>
								<div class="seven columns">
									<p>&nbsp;</p>
								</div>
								<div class="five columns">
						        	<a class="button" href="<?php echo  get_permalink(  ); ?>"> <?php  _e('Read More', 'Wordpress-text-domain');  ?></a>
								</div>
						    </div>
						</div>
						<hr class="styledivide">
				<?php 
						endwhile; 
					}else{ ?>
						<h2>Currently there are no classes scheduled.  Please <a href="/contact-us">contact us</a> for next available dates.</h2>
					<?php } ?>
		<?php wp_reset_query(); ?>
			</div>

		</div><!-- /#main -->

		<aside id="sidebar" class="three columns" role="complementary">

			<div class="sidebar-box">

				<?php get_sidebar(); ?>

			</div>

		</aside><!-- /#sidebar -->

	</div><!-- End Content row -->

<?php get_footer(); ?>