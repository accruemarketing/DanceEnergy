<?php
/*
Plugin Name: Simple Sitemap
Plugin URI: http://wordpress.org/plugins/simple-sitemap/
Description: HTML sitemap to display content as a single linked list of posts and pages, or as groups sorted by taxonomy (via a drop-down box).
Version: 1.65
Author: David Gwyer
Author URI: http://www.wpgothemes.com
*/

/*  Copyright 2009 David Gwyer (email : david@wpgothemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* @todo:
 * - Redo Plugin screenshots.
 * - Add text box that allows you to enter a comma separated list of custom post type names to exclude from the front end sitemap. Maybe only show this text box if the checkbox to show cpt is selected. For convenience, show a current list of cpt names that can be excluded.
 * - Add option that if a page ID is excluded, it also excludes its children?
 * - Get rid of query_posts().
 * - Enable responsive wrapping so that if there is space multiple columns will span across the page and automatically wrap to the next line when not enough space.
 * - Add archive pages to sitemap: http://wordpress.org/support/topic/include-archive-urls.
 * - Be able to sort ascending/descending in addition to the sort drop down options for each list.
 * - Add all Plugin options page in-line styles to external style sheet.
 * - Options to display custom post types, with ability to show which custom post types to display or not display, and in what order?
 * - Consider adding a drop down in Plugin options to show the page hierarchy as it appears in 'Pages' (i.e. the way it works now), or to show it via a defined custom menu hierarchy in Appearance -> Menus.
 * - Add option to remove certain tags, categories, or posts.
 * - Use the 'prepare' WP feature when querying the db directly.
 * - Use translation functions _e(), __() etc. Add Plugin text domain too.
 * - Show all the posts in each category not just the maximum allowed in Settings -> Reading.
 * - Update Plugin description and other sections, as well as the images which are out of date (show the single column working on the sidebar).
 * - Maybe support shortcode attributes so that users can specify to add custom behaviour for individual sitemaps if more than one is needed on a site. Any attributes would override global Plugin settings that affects all sitemaps by default.
 * - Exclude certain posts and categories from showing, similar to how pages can be excluded. Later extend this to CPT posts and archives too.
 */

/* wpss_ prefix is derived from [W]ord[P]ress [s]imple [s]itemap. */
add_shortcode( 'simple-sitemap', 'wpss_gen' );

register_activation_hook( __FILE__, 'wpss_add_defaults' );
register_uninstall_hook( __FILE__, 'wpss_delete_plugin_options' );
add_action( 'admin_init', 'wpss_init' );
add_action( 'admin_menu', 'wpss_add_options_page' );
add_filter( 'plugin_action_links', 'wpss_plugin_action_links', 10, 2 );
add_filter( 'widget_text', 'do_shortcode' ); // make sitemap shortcode work in text widgets

/* Delete options table entries ONLY when plugin deactivated AND deleted. */
function wpss_delete_plugin_options() {
	delete_option( 'wpss_options' );
}

/* Define default option settings. */
function wpss_add_defaults() {

	$tmp = get_option( 'wpss_options' );
	if ( ( ( isset( $tmp['chk_default_options_db'] ) && $tmp['chk_default_options_db'] == '1' ) ) || ( ! is_array( $tmp ) ) ) {
		delete_option( 'wpss_options' );
		$arr = array( "drp_pages_default"      => "post_title",
					  "drp_posts_default"      => "title",
					  "chk_default_options_db" => "0",
					  "chk_show_pages"         => "1",
					  "chk_show_cpts"          => "1",
					  "chk_show_posts"         => "1",
					  "txt_page_ids"           => ""
		);
		update_option( 'wpss_options', $arr );
	}

	// Make sure that something displays on the front end (i.e. the post, page, CPT check boxes are not all off)
	$tmp1 = get_option( 'wpss_options' );
	if ( isset( $tmp1 ) && is_array( $tmp1 ) ) {
		if ( ! ( isset( $tmp1['chk_show_posts'] ) && $tmp1['chk_show_posts'] ) && ! ( isset( $tmp1['chk_show_pages'] ) && $tmp1['chk_show_pages'] ) ) {
			// show pages and posts if nothing selected
			$tmp1['chk_show_pages'] = "1";
			$tmp1['chk_show_posts'] = "1";
		}

		update_option( 'wpss_options', $tmp1 );
	}
}

/* Init plugin options to white list our options. */
function wpss_init() {

	register_setting( 'wpss_plugin_options', 'wpss_options', 'wpss_validate_options' );
}

/* Add menu page. */
function wpss_add_options_page() {
	add_options_page( 'Simple Sitemap Options Page', 'Simple Sitemap', 'manage_options', __FILE__, 'wpss_render_form' );
}

/* Draw the menu page itself. */
function wpss_render_form() {
	?>
	<div class="wrap">
		<h2>Simple Sitemap Options</h2>

		<div style="background:#fff;border: 1px dashed #ccc;font-size: 13px;margin: 20px 0 10px 0;padding: 5px 0 5px 8px;">To display the Simple Sitemap on a post, page, or sidebar (via a Text widget), enter the following
			<a href="http://codex.wordpress.org/Shortcode_API" target="_blank">shortcode</a>: <b><code>[simple-sitemap]</code></b>
		</div>
		<form method="post" action="options.php">
			<?php settings_fields( 'wpss_plugin_options' ); ?>
			<?php $options = get_option( 'wpss_options' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">Pages Default List Type</th>
					<td>
						<select style="width:90px;" name='wpss_options[drp_pages_default]'>
							<option value='post_title' <?php selected( 'post_title', $options['drp_pages_default'] ); ?>>Title</option>
							<option value='post_date' <?php selected( 'post_date', $options['drp_pages_default'] ); ?>>Date</option>
							<option value='post_author' <?php selected( 'post_author', $options['drp_pages_default'] ); ?>>Author</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">Posts Default List Type</th>
					<td>
						<select style="width:90px;" name='wpss_options[drp_posts_default]'>
							<option value='title' <?php selected( 'title', $options['drp_posts_default'] ); ?>>Title</option>
							<option value='date' <?php selected( 'date', $options['drp_posts_default'] ); ?>>Date</option>
							<option value='author' <?php selected( 'author', $options['drp_posts_default'] ); ?>>Author</option>
							<option value='category' <?php selected( 'category', $options['drp_posts_default'] ); ?>>Category</option>
							<option value='tags' <?php selected( 'tags', $options['drp_posts_default'] ); ?>>Tags</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">Exclude Pages</th>
					<td>
						<input type="text" size="30" name="wpss_options[txt_page_ids]" value="<?php echo $options['txt_page_ids']; ?>" />

						<p class="description">Enter a comma separated list of Page ID's.</p>
					</td>
				</tr>

				<tr>
					<th scope="row">Show Pages</th>
					<td>
						<label><input name="wpss_options[chk_show_pages]" type="checkbox" value="1" <?php if ( isset( $options['chk_show_pages'] ) ) {
								checked( '1', $options['chk_show_pages'] );
							} ?> /> Display pages on front end sitemap?</label>
					</td>
				</tr>

				<tr>
					<th scope="row">Show Posts</th>
					<td>
						<label><input name="wpss_options[chk_show_posts]" type="checkbox" value="1" <?php if ( isset( $options['chk_show_posts'] ) ) {
								checked( '1', $options['chk_show_posts'] );
							} ?> /> Display posts on front end sitemap?</label>
					</td>
				</tr>

				<tr>
					<th scope="row">Show Custom Post Types</th>
					<td>
						<label><input name="wpss_options[chk_show_cpts]" type="checkbox" value="1" <?php if ( isset( $options['chk_show_cpts'] ) ) {
								checked( '1', $options['chk_show_cpts'] );
							} ?> /> Display Custom Post Types on front end sitemap?</label>
					</td>
				</tr>

				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="wpss_options[chk_default_options_db]" type="checkbox" value="1" <?php if ( isset( $options['chk_default_options_db'] ) ) {
								checked( '1', $options['chk_default_options_db'] );
							} ?> /> Restore Plugin defaults upon deactivation/reactivation</label>

						<p class="description">Only check this if you want to reset plugin settings upon reactivation</p>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
			</p>
		</form>

		<?php

		$discount_date = "14th August 2014";
		if( strtotime($discount_date) > strtotime('now') ) {
		echo '<p style="background: #fff;border: 1px dashed #ccc;font-size: 13px;margin: 0 0 10px 0;padding: 5px 0 5px 8px;">For a limited time only! <strong>Get 50% OFF</strong> the price of our brand new mobile ready, fully responsive <a href="http://www.wpgothemes.com/themes/minn/" target="_blank"><strong>Minn WordPress theme</strong></a>. Simply enter the following code at checkout: <code>MINN50OFF</code></p>';
		} else {
		echo '<p style="background: #eee;border: 1px dashed #ccc;font-size: 13px;margin: 0 0 10px 0;padding: 5px 0 5px 8px;">As a user of our free plugins here\'s a bonus just for you! <strong>Get 30% OFF</strong> the price of our brand new mobile ready, fully responsive <a href="http://www.wpgothemes.com/themes/minn/" target="_blank"><strong>Minn WordPress theme</strong></a>. Simply enter the following code at checkout: <code>WPGO30OFF</code></p>';
		}

		?>

		<div style="clear:both;">
			<p>
				<a href="http://www.twitter.com/dgwyer" title="Follow me Twitter!" target="_blank"><img src="<?php echo plugins_url(); ?>/simple-sitemap/images/twitter.png" /></a>&nbsp;&nbsp;
				<input class="button" style="vertical-align:12px;" type="button" value="Visit Our NEW Site!" onClick="window.open('http://www.wpgothemes.com')">
				<input class="button" style="vertical-align:12px;" type="button" value="Minn, Our Latest Theme" onClick="window.open('http://www.wpgothemes.com/themes/minn')">
			</p>
		</div>

	</div>
<?php
}

/* Shortcode function. */
function wpss_gen() {

	ob_start(); // start output caching (so that existing content in the [simple-sitemap] post doesn't get shoved to the bottom of the post

	$opt = get_option( 'wpss_options' );

	// Page query args from Plugin options
	if ( $opt['drp_pages_default'] == "post_title" ) {
		$page_params = 'menu_order, post_title';
	} else {
		if ( $opt['drp_pages_default'] == "post_date" ) {
			$page_params = 'post_date';
		} else {
			$page_params = 'post_author';
		}
	}

	$page_args = array( 'sort_column' => $page_params, 'title_li' => '' );
	if ( ! empty( $opt['txt_page_ids'] ) ) {
		$page_args['exclude'] = $opt['txt_page_ids'];
	}

	// Post query args from Plugin options
	if ( $opt['drp_posts_default'] == "title" ) {
		$post_params = 'title';
	} else {
		if ( $opt['drp_posts_default'] == "date" ) {
			$post_params = 'date';
		} else {
			if ( $opt['drp_posts_default'] == "author" ) {
				$post_params = 'author';
			} else {
				if ( $opt['drp_posts_default'] == "category" ) {
					$post_params = 'category';
				} else {
					$post_params = 'tags';
				}
			}
		}
	}

	$post_args = array( 'orderby' => $post_params, 'posts_per_page' => - 1, 'order' => 'asc' );

	?>

	<div class="ss_wrapper">

	<?php // RENDER SITEMAP PAGES ?>

	<?php if ( isset( $opt['chk_show_pages'] ) && $opt['chk_show_pages'] ) : ?>

		<div id="ss_pages">

			<h2 class='page_heading'>Pages</h2>

			<?php
			if ( strpos( $page_params, 'post_date' ) !== false ) {
				echo '<ul class="page_item_list">';
				$page_args = array( 'sort_order' => 'desc', 'sort_column' => 'post_date', 'title_li' => '' );
				if ( ! empty( $opt['txt_page_ids'] ) ) {
					$page_args['exclude'] = $opt['txt_page_ids'];
				}
				wp_list_pages( $page_args ); // show the sorted pages
				echo '</ul>';
			} elseif ( strpos( $page_params, 'post_author' ) !== false ) {
				$authors = get_users(); //gets registered users
				foreach ( $authors as $author ) {
					$empty_page_args = array( 'echo' => 0, 'authors' => $author->ID, 'title_li' => '' );
					$empty_test      = wp_list_pages( $empty_page_args ); // test for authors with zero pages
					//echo '$empty_test = '.$empty_test;

					if ( $empty_test != null || $empty_test != "" ) {
						echo "<div class='page_author'>$author->display_name</div>";
						echo "<div class=\"ss_date_header\"><ul class=\"page_item_list\">";
						$page_args = array( 'authors' => $author->ID, 'title_li' => '' );
						if ( ! empty( $opt['txt_page_ids'] ) ) {
							$page_args['exclude'] = $opt['txt_page_ids'];
						}
						wp_list_pages( $page_args );
						echo "</ul></div>";
					} else {
						echo "<div class='page_author'>$author->display_name <span class=\"ss_sticky\">(no pages published)</span></div>";
					}
				} ?>
			<?php
			} else { /* default = title */
				echo '<ul class="page_item_list">';
				wp_list_pages( $page_args ); /* Show sorted pages with default $page_args. */
				echo '</ul>';
			}
			?>
		</div><!--ss_pages -->

	<?php endif; ?>


	<?php // RENDER SITEMAP POSTS ?>

	<?php if ( isset( $opt['chk_show_posts'] ) && $opt['chk_show_posts'] ) : ?>

		<div id="ss_posts">
			<h2 class='post_heading'>Posts</h2>

			<?php
			if ( strpos( $post_params, 'category' ) !== false ) {
				$categories = get_categories();
				foreach ( $categories as $category ) {
					$category_link = get_category_link( $category->term_id );
					$cat_count     = $category->category_count;

					echo '<div class="ss_cat_header"><a href="' . $category_link . '">' . ucwords( $category->cat_name ) . '</a> ';
					query_posts( 'posts_per_page=-1&post_status=publish&cat=' . $category->term_id ); // show the sorted posts
					?>
					<?php
					global $wp_query;
					echo '(' . $wp_query->post_count . ')</div>'; ?>
					<?php
					if ( have_posts() ) :
						echo '<div class="post_item_list"><ul class="post_item_list">';
						while ( have_posts() ) :
							the_post(); ?>
							<li class="post_item">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</li>
						<?php  endwhile;
						echo '</ul></div>';
					endif;
					wp_reset_query();
				}
			} else if ( strpos( $post_params, 'author' ) !== false ) {
				$authors = get_users(); //gets registered users
				foreach ( $authors as $author ) {
					echo '<a href="' . get_author_posts_url( $author->ID ) . '">' . $author->display_name . '</a> ';
					query_posts( 'posts_per_page=-1&post_status=publish&author=' . $author->ID ); // show the sorted posts
					?>
					<?php
					global $wp_query;
					echo '(' . $wp_query->post_count . ')'; ?>
					<?php
					if ( have_posts() ) :
						echo '<div class="post_item_list"><ul class="post_item_list">';
						while ( have_posts() ) :
							the_post(); ?>
							<li class="post_item">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</li>
						<?php  endwhile;
						echo '</ul></div>';
					endif;
					wp_reset_query();
				}
			} else if ( strpos( $post_params, 'tags' ) !== false ) {
				$post_tags = get_tags();
				echo '<div class="ss_tag_header">';
				foreach ( $post_tags as $tag ) {
					$tag_link = get_tag_link( $tag->term_id );
					echo "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
					echo "{$tag->name}</a> ($tag->count)";

					query_posts( 'posts_per_page=-1&post_status=publish&tag=' . $tag->slug ); // show posts
					?>
					<?php
					if ( have_posts() ) :
						echo '<div class="post_item_list"><ul class="post_item_list">';
						while ( have_posts() ) :
							the_post(); ?>
							<li class="post_item">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</li>
						<?php  endwhile;
						echo '</ul></div>';
					endif;
					wp_reset_query();
				}
				echo '</div>';
			} else {
				if ( strpos( $post_params, 'date' ) !== false ) {
					?>
					<div class="ss_date_header">
						<?php
						global $wpdb;
						$months = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT MONTH(post_date) AS month , YEAR(post_date) AS year FROM $wpdb->posts WHERE post_status = %s and post_date <= now( ) and post_type = %s GROUP BY month, year ORDER BY post_date DESC", 'publish', 'post' ) );
						foreach ( $months as $curr_month ) {
							query_posts( 'posts_per_page=-1&post_status=publish&monthnum=' . $curr_month->month . '&year=' . $curr_month->year ); // show posts
							?>
							<?php
							global $wp_query;
							echo "<a href=\"";
							echo get_month_link( $curr_month->year, $curr_month->month );
							echo '">' . date( 'F', mktime( 0, 0, 0, $curr_month->month ) ) . ' ' . $curr_month->year . '</a> (' . $wp_query->post_count . ')'; ?>
							<?php
							if ( have_posts() ) :
								echo '<div class="post_item_list"><ul class="post_item_list">';
								while ( have_posts() ) :
									the_post(); ?>
									<li class="post_item">
										<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
									</li>
								<?php  endwhile;
								echo '</ul></div>';
							endif;
							wp_reset_query();
						} ?>
					</div>
				<?php
				} else { /* default = title */
					?>
					<?php query_posts( $post_args ); /* Show sorted posts with default $post_args. */
					if ( have_posts() ) :
						echo '<ul class="post_item_list">';
						while ( have_posts() ) :
							the_post();
							$sticky = "";
							if ( is_sticky( get_the_ID() ) ) {
								$sticky = " (sticky post)";
							} ?>
							<li class="post_item">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								<?php if ( $sticky == ' (sticky post)' ) : ?>
									<span class="ss_sticky"><?php echo $sticky; ?></span>
								<?php endif; ?>
							</li>
						<?php  endwhile;
						echo '</ul>';
					endif;
					wp_reset_query();
				}
			}
			?>
		</div><!--ss_posts-->

	<?php endif; ?>


	<?php // RENDER SITEMAP CUSTOM POST TYPES ?>

	<?php
	$args = array( 'public' => true, '_builtin' => false );
	$custom_post_types = get_post_types( $args, 'objects' );

	foreach ( $custom_post_types as $post_type ) :
		?>

		<?php if ( isset( $opt['chk_show_cpts'] ) && $opt['chk_show_cpts'] ) : ?>

		<div id="ss_cpt">

			<h2 class='cpt_heading'><?php echo $post_type->label; ?></h2>

			<?php

			$cpt_posts = get_posts( 'post_type=' . $post_type->name . '&posts_per_page=-1' );

			if ( $cpt_posts ) : ?>
				<ul class="cpt_item_list">
					<?php foreach ( $cpt_posts as $cpt_post ) : ?>
						<?php $cpt_link = get_post_permalink( $cpt_post->ID ); ?>
						<li><a href="<?php echo $cpt_link; ?>"> <?php echo $cpt_post->post_title; ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div><!--ss_cpt -->

	<?php endif; ?>

	<?php endforeach ?>

	</div>
	<?php

	$output = ob_get_contents();;
	ob_end_clean();

	return $output;

}

// Display a Settings link on the main Plugins page
function wpss_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$posk_links = '<a href="' . get_admin_url() . 'options-general.php?page=simple-sitemap/simple-sitemap.php">' . __( 'Settings' ) . '</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $posk_links );
	}

	return $links;
}

/* Sanitize and validate input. Accepts an array, return a sanitized array. */
function wpss_validate_options( $input ) {
	// Strip html from textboxes
	// e.g. $input['textbox'] =  wp_filter_nohtml_kses($input['textbox']);

	$input['txt_page_ids'] = sanitize_text_field( $input['txt_page_ids'] );

	return $input;
}