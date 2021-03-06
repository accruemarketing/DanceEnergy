<?php
/*
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
*/
/**
 * Template Name: Learn to dance template
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

				<?php while ( have_posts() ) : the_post(); ?>
						<!-- START: content.php -->
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						    <header class="entry-header">
					            <?php
					             $chkbanner = get_post_meta(get_the_ID());
					             
					             if(isset($chkbanner["wpcf-page-title"][0]) && $chkbanner["wpcf-page-title"][0] != "" ){ ?>
					                <h1 class="entry-title dancetemplate"><?php echo $chkbanner["wpcf-page-title"][0]; ?></h1>
					            <?php }else{ ?>
					                <h1 class="entry-title dancetemplate"><?php the_title(); ?></h1>
					            <?php } 

					             if(isset($chkbanner["wpcf-sub-title"][0]) && $chkbanner["wpcf-sub-title"][0] != "" ){ ?>
					                <h3 class="entry-title"><?php echo $chkbanner["wpcf-sub-title"][0]; ?></h3>
					            <?php }else{ ?>
					                <h3 class="entry-title"><?php the_title(); ?></h3>
					            <?php } ?>
					        </header><!-- .entry-header -->
					        <hr>
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
				<?php endwhile; ?>
			</div>
			<?php
				$chekvalue = types_render_field( "advert-three-title", array( ) );
				if($chekvalue != ''){
			?>
			<div class="row dancestyleadvert_wrap">
				<div class="four columns dancestyleadvert">
					<h4><?php echo types_render_field( "advert-one-title", array( ) ); ?></h4>
						<?php echo types_render_field( "advert-one", array( ) ); ?>
				</div>
				<div class="four columns dancestyleadvert">
					<h4><?php echo  types_render_field( "advert-two-title", array( ) ); ?></h4>
						<?php echo types_render_field("advert-two", array() ); ?>
					
				</div>
				<div class="four columns dancestyleadvert">
					<h4><?php echo  types_render_field( "advert-three-title", array( ) ); ?></h4>
						<?php echo types_render_field("advert-three", array() ); ?>
				</div>
			</div>
			<hr>
			<?php } ?> 
	<?php
		$chekvalue = types_render_field( "advert-three-title", array( ) );
		if($chekvalue === ''){
	?>
		<div class="row classintrodancestyle inlinedisplay">
			<?php
				$post_id = get_the_ID();
			    $pagemeta = get_post_meta( $post_id );
			    $currentTerm = $pagemeta['wpcf-learn-to-dance-product-tag'][0];
			if($currentTerm !== 'NONE'){

				wp_reset_query();

				$query = new WP_Query( array( 'post_type'=>'product', 'taxonomy'=>'product_tag', 'term' => $currentTerm, 'orderby' => 'menu_order', 'order' => 'asc' ) ); 

			    while ($query->have_posts()) : $query->the_post();

			    $postmeta = get_post_meta( $post->ID );

			    $themepath = get_stylesheet_directory();

				include_once( $themepath . '/single-product/product-thumbnails.php');
			?>			
			<!-- this is where you should insert your content of the posts -->
					<div class="twelve columnns contentstyle">
						<a href="<?php echo the_permalink(); ?>">
						<!-- this is where you should insert your content of the posts -->
						<h2><?php echo the_title(); ?></h2>
						<?php 
						 	echo apply_filters( 'woocommerce_short_description', $query->post->post_excerpt );

						 	echo $product->get_price_html();
						 ?>
						</a>
				    </div>
			<?php endwhile; ?>
			<?php } ?>
				</div>
			<?php wp_reset_query(); ?>
		<?php } ?>
		</div><!-- /#main -->
		<aside id="sidebar" class="three columns" role="complementary">
			<div class="sidebar-box">
					<div id="supplementary_shop" class="row">
				<?php
				$sidebarname = types_render_field( "sidebar-name", array( ) );
				if($sidebarname == '' && !is_active_sidebar( $sidebarname )){
					dynamic_sidebar( 'learn-dance' ); 
				}else{
					dynamic_sidebar( $sidebarname ); 
				}
				?>
				</div>
			</div><!-- #supplementary -->
		</aside><!-- /#sidebar -->
	</div><!-- End Content row -->

<?php get_footer(); ?>