<?php 


/****
	*
	*CARRER WIDGET CLASS
	*
	**/
class carrer_widget_info extends WP_Widget {


		//Name the widget, here carrer info will be displayed as widget name, $widget_ops may be an array of value, which may holds the title, description like that.

		function carrer_widget_info () {

			$this->WP_Widget('carrer_widget_info', 'Jobs', $widget_ops );        
		}

		//Designing the form widget, which will be displayed in the admin dashboard widget location.

		public function form( $instance ) {

			if ( isset( $instance[ 'number' ] ) && isset( $instance['orderby'] ) && isset( $instance['excerpt']) && isset( $instance['title']) && isset($instance['animspeed']) && isset($instance['slideheight']) && isset($instance['slidewidth'])) {

				$title = $instance[ 'title' ];
				$number = $instance[ 'number' ];
				$orderby = $instance[ 'orderby' ];
				$excerpt = $instance[ 'excerpt' ];
				$animspeed = $instance[ 'animspeed' ];
				$slidewidth = $instance[ 'slidewidth' ];
				$slideheight = $instance[ 'slideheight' ];

			}else {
				$title = __( '', 'bc_widget_title' );
				$number = __( '', 'bc_widget_number' );
				$orderby = __('', 'bc_widget_orderby');
				$excerpt = __('', 'bc_widget_excerpt');
				$animspeed = __('', 'bc_widget_animspeed');
				$slidewidth = __('', 'bc_widget_slidewidth');
				$slideheight = __('', 'bc_widget_slideheight');				
			} 
			?>
			<p>TITLE:<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" /></p>
			<h3>::::Post Options::::</h3>
			<ul>
				<?php 
				    $custom_posts = new WP_Query();
				    $custom_posts->query("post_type=job&posts_per_page=$number&orderby=$orderby");
				    while ($custom_posts->have_posts()) : $custom_posts->the_post();
				?>
				    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile; ?> 
				<?php  wp_reset_postdata(); ?>
			</ul>
			<?php if($orderby == "none"){echo "selected";} ?>
			<p>MAX Number of job's to show: <input name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $number );?>" /></p>
			<p>Excerpt length: <input name="<?php echo $this->get_field_name( 'excerpt' ); ?>" type="text" value="<?php echo esc_attr( $excerpt );?>" /></p>
			<p>Order job's by: <br> <select name="<?php echo $this->get_field_name( 'orderby' ); ?>" value="<?php echo esc_attr( $orderby );?>" id="">
				<option value="none" <?php if($orderby == "none"){echo "selected";} ?>>No order</option>
				<option value="ID" <?php if($orderby == "ID"){echo "selected";} ?>>Order by post id</option>
				<option value="title" <?php if($orderby == "title"){echo "selected";} ?>>Order by author</option>
				<option value="name" <?php if($orderby == "name"){echo "selected";} ?>>Order by title</option>
				<option value="type" <?php if($orderby == "type"){echo "selected";} ?>>Order by post type</option>
				<option value="date" <?php if($orderby == "date"){echo "selected";} ?>>Order by date</option>
				<option value="modified" <?php if($orderby == "modified"){echo "selected";} ?>>Order by last modified date.</option>
				<option value="rand" <?php if($orderby == "rand"){echo "selected";} ?>>Random order</option>
				<option value="menu_order" <?php if($orderby == "menu_order"){echo "selected";} ?>>Order by MENU Order</option>
			</select></p>
			<h3>::::Slider Options::::</h3>
			<p>Animspeed (the delay between each slide in ms): <br> <input name="<?php echo $this->get_field_name( 'animspeed' ); ?>" type="number"  value="<?php echo esc_attr( $animspeed );?>" id="" /></p>
			<p>Width/Height (these values act as maximum dimensions Reccommended: 320X620): 
				<br>Height: <input name="<?php echo $this->get_field_name( 'slideheight' ); ?>" type="number"  value="<?php echo esc_attr( $slideheight );?>" id="" />
				<br>Width: <input name="<?php echo $this->get_field_name( 'slidewidth' ); ?>" type="number"  value="<?php echo esc_attr( $slidewidth );?>" id="" />
			</p>
			<?php 

		}

		// update the new values in database

		function update($new_instance, $old_instance) {

			$instance = $old_instance;

			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';

			$instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';

			$instance['excerpt'] = ( ! empty( $new_instance['excerpt'] ) ) ? strip_tags( $new_instance['excerpt'] ) : '';
			
			$instance['animspeed'] = ( ! empty( $new_instance['animspeed'] ) ) ? strip_tags( $new_instance['animspeed'] ) : '';

			$instance['slidewidth'] = ( ! empty( $new_instance['slidewidth'] ) ) ? strip_tags( $new_instance['slidewidth'] ) : '';

			$instance['slideheight'] = ( ! empty( $new_instance['slideheight'] ) ) ? strip_tags( $new_instance['slideheight'] ) : '';

			return $instance;

		}

		//Display the stored widget information in webpage.

		function widget($args, $instance) {
			extract($args);

			echo $before_widget; //Widget starts to print information
			$title = empty( $instance['title'] ) ? '&nbsp;' : $instance['title'];
			$number = empty( $instance['number'] ) ? '&nbsp;' : $instance['number'];
			$orderby = empty( $instance['orderby'] ) ? '&nbsp;' : $instance['orderby'];
			$excerpt = empty( $instance['excerpt'] ) ? '&nbsp;' : $instance['excerpt'];
			$animspeed = empty( $instance['animspeed'] ) ? '&nbsp;' : $instance['animspeed'];
			$slidewidth = empty( $instance['slidewidth'] ) ? '&nbsp;' : $instance['slidewidth'];
			$slideheight = empty( $instance['slideheight'] ) ? '&nbsp;' : $instance['slideheight'];
			if (empty( $instance['excerpt'] )) {
				# code...
				echo "<h2>PLEASE SELECT A EXCERPT LENGTH</h2>";
			}else{

			?>
			<script>
				(function ($) {
					"use strict";
					$(function () {
						// Place your public-facing JavaScript here
						    jQuery('#faq_slideshow').bjqs({
						        'height' : <?php echo $slideheight; ?>,
						        'width' : <?php echo $slidewidth; ?>,
						        'responsive' : true,
						        'animspeed' : <?php echo $animspeed; ?>, // the delay between each slide 
						        'centercontrols' : false, // center controls verically
						        'showmarkers' : false, 
								'nexttext' : '>>', // Text for 'next' button (can use HTML)
								'prevtext' : '<<', // Text for 'previous' button (can use HTML)
						    });
					});
				}(jQuery));
			</script>
			<h3 class="widget-title"><?php echo $title; ?></h3>
			<div id="faq_slideshow">
				<ul class="bjqs">
					<?php 
					    $custom_posts = new WP_Query();
					    $custom_posts->query("post_type=job&posts_per_page=$number&orderby=$orderby");
					    while ($custom_posts->have_posts()) : $custom_posts->the_post();
						$postmeta = get_post_meta( $custom_posts->post->ID );
						$getcontent = $custom_posts->post->post_content;
						$content = myTruncate($getcontent, $excerpt);
						/*
						echo "<pre>";
						var_dump($postmeta['wpcf-profile-photo-instructor'][0]);
						echo "</pre>";
						*/
					?>
				
					    <li>
					    	<span class="twelve columns">
					    		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					    	</span>
							<span class="twelve columns"><?php echo $content; ?></span>
					    </li>
					<?php endwhile; ?> 
					<?php  wp_reset_postdata(); ?>
				</ul>
			</div>
			<?php
			}
			echo $after_widget; //Widget ends printing information
		} 
}