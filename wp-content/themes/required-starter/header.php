<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package required+ Starter
 * @since required+ Starter 0.1.0
 */
?><!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="shortcut icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri(); ?>/images/devices/apple-touch-icon-iphone.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/images/devices/apple-touch-icon-iphone.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/images/devices/apple-touch-icon-ipad.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/images/devices/apple-touch-icon-ipad.png" />
	<!-- IE Fix for HTML5 Tags -->
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<body <?php body_class(); ?> data-ng-app="ngAppDemo">
	<div data-ng-controller="mainapp">	
	<!-- Start the main container -->
	<div id="container" class="container" role="document" ng-init="docheight" contentele resize	>
		<!-- Row for blog navigation -->
		<div id="head_wrap">
			<div class="row">
			<header class="twelve columns required-header" role="banner">
				<div class="row">
					<div class="eight columns">
					</div>
					<div class="four columns">	
						<?php
						/*$logininout = wp_loginout('',false);
						$loginurlformat = '<ul id="menu-topmenu" class="inline-list right"><li id="item-id">'.$logininout.'</li>%3$s</ul>';
						wp_nav_menu( array(
							'theme_location' => 'meta',
							'container' => false,
							'menu_class' => 'inline-list right',
							'fallback_cb' => false, 
							'items_wrap' => $loginurlformat 
						) );*/
						 ?>
					</div>
				</div>
					<hgroup class="row">
					<?php
						/**
					 	* Include our custom-header.php
					 	*
					 	* Used with the header image stuff.
					 	*/
						get_template_part( 'custom-header' );
					?>
					</hgroup>
				<?php
					/**
					 * Include the default navigation
					 *
					 * You could easily do something like:
					 * if ( is_front_page() ) {
					 * 	get_template_part( 'nav', 'front-page' ); // nav-front-page.php
					 * } else {
					 * 	get_template_part( 'nav' );	// nav.php
					 * }
					 */
					if ( ! is_page_template( 'page-templates/off-canvas-page.php' ) ) {
						get_template_part( 'nav' );
					}
				?>
			</header>
			</div><!-- // header.php -->
		</div>
		<?php
			$chkbannerHEAD = get_post_meta(get_the_ID());
			if(!isset($chkbannerHEAD["wpcf-layer-slider-id"][0]) && !isset($chkbannerHEAD["wpcf-banner-image"][0]) || $chkbannerHEAD["wpcf-banner-image"][0] === '' && $chkbannerHEAD["wpcf-layer-slider-id"][0] === ''){ ?>
				<div id="slider">
					<?php layerslider(1); ?>
				</div>
		<?php }elseif (isset($chkbannerHEAD["wpcf-layer-slider-id"][0])) { ?>
				<div id="slider">
					<?php layerslider($chkbannerHEAD["wpcf-layer-slider-id"][0]); ?>
				</div>
			<?php }elseif (isset($chkbannerHEAD["wpcf-banner-image"][0])) { ?>
				<div id="slider" class="bannerimgslide">
					<img class="bannerimg" src="<?php echo $chkbannerHEAD["wpcf-banner-image"][0]; ?>" alt="Banner image">
				</div>
			<?php } 

			?>

		<div id="containerinner">
		<?php
			/**
			 * Include the Foundation Top Bar
			 *
			 * It uses the same navigation as nav.php
			 * so you might want to use a different navigation
			 * here.
			 */
			if ( is_page_template( 'page-templates/off-canvas-page.php' ) ) {
				get_template_part('nav', 'top-bar');
			}
		?>