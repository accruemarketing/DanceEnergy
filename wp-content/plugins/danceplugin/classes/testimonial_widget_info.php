<?php 
/****
	*
	*Testimonial WIDGET CLASS
	*
	**/
class testimonial_widget_info extends WP_Widget {


		//Name the widget, here testimonial info will be displayed as widget name, $widget_ops may be an array of value, which may holds the title, description like that.

		function testimonial_widget_info () {

			$this->WP_Widget('testimonial_widget_info', 'Custom Testimonials', $widget_ops );        
		}

		//Designing the form widget, which will be displayed in the admin dashboard widget location.

		public function form( $instance ) {

			if (  isset($instance['title']) ) {

				$title = $instance[ 'title' ];
				$titlelink = $instance[ 'titlelink' ];
				//$titlelink = $instance[ 'titlelink' ];
				$animspeed = $instance[ 'animspeed' ];
				$slidewidth = $instance[ 'slidewidth' ];
				$slideheight = $instance[ 'slideheight' ];
				//Testimonials
				$testauthorone = $instance[ 'testauthorone' ];
				$testcontentone = $instance[ 'testcontentone' ];
				$testauthortwo = $instance[ 'testauthortwo' ];
				$testcontenttwo = $instance[ 'testcontenttwo' ];

				$testauthorthree = $instance[ 'testauthorthree' ];
				$testcontentthree = $instance[ 'testcontentthree' ];

				$testauthorfour = $instance[ 'testauthorfour' ];
				$testcontentfour = $instance[ 'testcontentfour' ];
				$testauthorfive = $instance[ 'testauthorfive' ];
				$testcontentfive = $instance[ 'testcontentfive' ];																


			}else {
				$title = __( '', 'bc_widget_title' );
				$titlelink = __( '', 'bc_widget_titlelink' );
				$animspeed = __('', 'bc_widget_animspeed');
				$slidewidth = __('', 'bc_widget_slidewidth');
				$slideheight = __('', 'bc_widget_slideheight');		
				//Testimonials
				$testauthorone = __('', 'bc_widget_testauthorone');
				$testcontentone = __('', 'bc_widget_testcontentone');
				$testauthortwo = __('', 'bc_widget_testauthortwo');
				$testcontenttwo = __('', 'bc_widget_testcontenttwo');

				$testauthorthree = __('', 'bc_widget_testauthorthree');
				$testcontentthree = __('', 'bc_widget_testcontentthree');

				$testauthorfour = __('', 'bc_widget_testauthorfour');
				$testcontentfour = __('', 'bc_widget_testcontentfour');
				$testauthorfive = __('', 'bc_widget_testauthorfive');
				$testcontentfive = __('', 'bc_widget_testcontentfive');																

			} 
			?>
			<p>Title:<br><input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" /></p>
			<p>Title link:<br><input name="<?php echo $this->get_field_name( 'titlelink' ); ?>" type="text" value="<?php echo esc_attr( $titlelink );?>" /></p>
			<h3>::::Testimonials::::</h3>
				<br>Testimonial Author 1: <br/>
				<input name="<?php echo $this->get_field_name( 'testauthorone' ); ?>" type="text"  value="<?php echo esc_attr( $testauthorone );?>" id="" />
				<br>Testimonial Content 1: <br/>
				<textarea name="<?php echo $this->get_field_name( 'testcontentone' ); ?>" id="" cols="30" rows="10"><?php echo esc_attr( $testcontentone );?></textarea>
				<br>Testimonial Author 2: <br/>
				<input name="<?php echo $this->get_field_name( 'testauthortwo' ); ?>" type="text"  value="<?php echo esc_attr( $testauthortwo );?>" id="" />
				<br>Testimonial Content 2: <br/>
				<textarea name="<?php echo $this->get_field_name( 'testcontenttwo' ); ?>" id="" cols="30" rows="10"><?php echo esc_attr( $testcontenttwo );?></textarea>

				<br>Testimonial Author 3: <br/>
				<input name="<?php echo $this->get_field_name( 'testauthorthree' ); ?>" type="text"  value="<?php echo esc_attr( $testauthorthree );?>" id="" />
				<br>Testimonial Content 3: <br/>
				<textarea name="<?php echo $this->get_field_name( 'testcontentthree' ); ?>" id="" cols="30" rows="10"><?php echo esc_attr( $testcontentthree );?></textarea>

				<br>Testimonial Author 4: <br/>
				<input name="<?php echo $this->get_field_name( 'testauthorfour' ); ?>" type="text"  value="<?php echo esc_attr( $testauthorfour );?>" id="" />
				<br>Testimonial Content 4: <br/>
				<textarea name="<?php echo $this->get_field_name( 'testcontentfour' ); ?>" id="" cols="30" rows="10"><?php echo esc_attr( $testcontentfour );?></textarea>
				<br>Testimonial Author 5 : <br/>
				<input name="<?php echo $this->get_field_name( 'testauthorfive' ); ?>" type="text"  value="<?php echo esc_attr( $testauthorfive );?>" id="" />
				<br>Testimonial Content 5 : <br/>
				<textarea name="<?php echo $this->get_field_name( 'testcontentfive' ); ?>" id="" cols="30" rows="10"><?php echo esc_attr( $testcontentfive );?></textarea>														
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

			
			$instance['titlelink'] = ( ! empty( $new_instance['titlelink'] ) ) ? strip_tags( $new_instance['titlelink'] ) : '';

			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
			
			$instance['animspeed'] = ( ! empty( $new_instance['animspeed'] ) ) ? strip_tags( $new_instance['animspeed'] ) : '';

			$instance['slidewidth'] = ( ! empty( $new_instance['slidewidth'] ) ) ? strip_tags( $new_instance['slidewidth'] ) : '';

			$instance['slideheight'] = ( ! empty( $new_instance['slideheight'] ) ) ? strip_tags( $new_instance['slideheight'] ) : '';
			
			//Testimonials
			$instance['testauthorone'] = ( ! empty( $new_instance['testauthorone'] ) ) ? strip_tags( $new_instance['testauthorone'] ) : '';
			$instance['testcontentone'] = ( ! empty( $new_instance['testcontentone'] ) ) ? strip_tags( $new_instance['testcontentone'] ) : '';
			$instance['testauthortwo'] = ( ! empty( $new_instance['testauthortwo'] ) ) ? strip_tags( $new_instance['testauthortwo'] ) : '';
			$instance['testcontenttwo'] = ( ! empty( $new_instance['testcontenttwo'] ) ) ? strip_tags( $new_instance['testcontenttwo'] ) : '';

			$instance['testauthorthree'] = ( ! empty( $new_instance['testauthorthree'] ) ) ? strip_tags( $new_instance['testauthorthree'] ) : '';
			$instance['testcontentthree'] = ( ! empty( $new_instance['testcontentthree'] ) ) ? strip_tags( $new_instance['testcontentthree'] ) : '';

			$instance['testauthorfour'] = ( ! empty( $new_instance['testauthorfour'] ) ) ? strip_tags( $new_instance['testauthorfour'] ) : '';
			$instance['testcontentfour'] = ( ! empty( $new_instance['testcontentfour'] ) ) ? strip_tags( $new_instance['testcontentfour'] ) : '';
			$instance['testauthorfive'] = ( ! empty( $new_instance['testauthorfive'] ) ) ? strip_tags( $new_instance['testauthorfive'] ) : '';
			$instance['testcontentfive'] = ( ! empty( $new_instance['testcontentfive'] ) ) ? strip_tags( $new_instance['testcontentfive'] ) : '';



			return $instance;

		}

		//Display the stored widget information in webpage.

		function widget($args, $instance) {
			extract($args);

			echo $before_widget; //Widget starts to print information
			$title = empty( $instance['title'] ) ? '&nbsp;' : $instance['title'];
			$titlelink = empty( $instance['titlelink'] ) ? '&nbsp;' : $instance['titlelink'];
			$number = empty( $instance['number'] ) ? '&nbsp;' : $instance['number'];
			$excerpt = empty( $instance['excerpt'] ) ? '&nbsp;' : $instance['excerpt'];
			$animspeed = empty( $instance['animspeed'] ) ? '&nbsp;' : $instance['animspeed'];
			$slidewidth = empty( $instance['slidewidth'] ) ? '&nbsp;' : $instance['slidewidth'];
			$slideheight = empty( $instance['slideheight'] ) ? '&nbsp;' : $instance['slideheight'];


			$testauthorone = empty( $instance['testauthorone'] ) ? '&nbsp;' : $instance['testauthorone'];
			$testcontentone = empty( $instance['testcontentone'] ) ? '&nbsp;' : $instance['testcontentone'];
			$testauthortwo = empty( $instance['testauthortwo'] ) ? '&nbsp;' : $instance['testauthortwo'];
			$testcontenttwo = empty( $instance['testcontenttwo'] ) ? '&nbsp;' : $instance['testcontenttwo'];
			$testauthorthree = empty( $instance['testauthorthree'] ) ? '&nbsp;' : $instance['testauthorthree'];
			$testcontentthree = empty( $instance['testcontentthree'] ) ? '&nbsp;' : $instance['testcontentthree'];
			$testauthorfour = empty( $instance['testauthorfour'] ) ? '&nbsp;' : $instance['testauthorfour'];
			$testcontentfour = empty( $instance['testcontentfour'] ) ? '&nbsp;' : $instance['testcontentfour'];
			$testauthorfive = empty( $instance['testauthorfive'] ) ? '&nbsp;' : $instance['testauthorfive'];
			$testcontentfive = empty( $instance['testcontentfive'] ) ? '&nbsp;' : $instance['testcontentfive'];
			?>
			<script>
				(function ($) {
					"use strict";
					$(function () {
						// Place your public-facing JavaScript here
						    jQuery('#testimonial_slideshow').bjqs({
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
			<h3 class="widget-title"><a href="<?php echo $titlelink; ?>" title="<?php echo $title; ?>" style="color: #fff;"><?php echo $title; ?></a></h3>
			<div id="testimonial_slideshow">
				<ul class="bjqs">
					<li>
					    <span class="twelve columns"><?php echo $testcontentone; ?></span>
						<span class="twelve columns authordetails"><?php echo $testauthorone; ?></span>
					</li>
					<li>
					    <span class="twelve columns"><?php echo $testcontenttwo; ?></span>
						<span class="twelve columns authordetails"><?php echo $testauthortwo; ?></span>
					</li>
					<?php if (!empty( $instance['testauthorthree'] )) { ?>					
					<li>
					    <span class="twelve columns"><?php echo $testcontentthree; ?></span>
						<span class="twelve columns authordetails"><?php echo $testauthorthree; ?></span>
					</li>
					<?php } ?>
					<?php if (!empty( $instance['testauthorfour'] )) { ?>					
					<li>
					    <span class="twelve columns"><?php echo $testcontentfour; ?></span>
						<span class="twelve columns authordetails"><?php echo $testauthorfour; ?></span>
					</li>
					<?php } ?>
					<?php if (!empty( $instance['testauthorfive'] )) { ?>					
					<li>
					    <span class="twelve columns"><?php echo $testcontentfive; ?></span>
						<span class="twelve columns authordetails"><?php echo $testauthorfive; ?></span>
					</li>	
					<?php } ?>																								
				</ul>
			</div>
			<?php
			echo $after_widget; //Widget ends printing information
		} 
}