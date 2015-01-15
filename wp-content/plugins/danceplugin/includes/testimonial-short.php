<?php
	if( !isset( $atts['animtype'] )){
		$animtype = fade; 
	}else{
		$animtype = $atts['animtype']; 
	}
	if( !isset( $atts['speed'] )){
		$speed = 4000; 
	}else{
		$speed = $atts['speed']; 
	}
 ?>
<script>
	(function ($) {
		"use strict";
		$(function () {
			// Place your public-facing JavaScript here
			jQuery('#testimonial_slideshow').bjqs({
				'height' : 320,
				'width' : 620,
				'responsive' : true,
				'animtype' : "<?php echo  $animtype;  ?>", // accepts 'fade' or 'slide'
				'animspeed' : <?php echo  $speed;  ?>, // the delay between each slide 
				'centercontrols' : false, // center controls verically
				'showmarkers' : false,
				'showcontrols': false,
				'nexttext' : '>>', // Text for 'next' button (can use HTML)
				'prevtext' : '<<', // Text for 'previous' button (can use HTML)
			});
		});
	}(jQuery));
</script>
<div id="testimonial_slideshow">
	<ul class="bjqs">
		<?php
			wp_reset_query();
			$query = new WP_Query( array( 'post_type'=>'testimonials-widget', 'orderby' => 'menu_order', 'order' => 'asc' ) ); 
			while ($query->have_posts()) : $query->the_post();
				$post_id_class = $query->post->ID;
		?>
			 <li>
				<div class="teststyle">
					<div class="twelve columnns">
						<h4>WHAT CLIENTS SAY</h4>
						<blockquote>
							<?php the_content(  ); ?>
							<div class="credit"><span class="author">- <?php echo the_title(); ?></span></div>
						</blockquote>
					</div>
				</div>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
<div class="row">
<!-- Row for main content area -->
	<div class="small-12 large-12 columns" role="main">
		<?php /* Display navigation to next/previous pages when applicable */ ?>
		<?php if ( function_exists('FoundationPress_pagination') ) { FoundationPress_pagination(); } else if ( is_paged() ) { ?>
			<nav id="post-nav">
				<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'FoundationPress' ) ); ?></div>
				<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'FoundationPress' ) ); ?></div>
			</nav>
		<?php } ?>

	</div>
</div>
<?php wp_reset_query(); ?>