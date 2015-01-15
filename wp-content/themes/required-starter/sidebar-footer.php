<?php
/**
 * The Footer widget areas.
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.1.0
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'sidebar-footer-1' )
		&& ! is_active_sidebar( 'sidebar-footer-2' )
		&& ! is_active_sidebar( 'sidebar-footer-3' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<!-- START: sidebar-footer.php -->
<div id="supplementary" class="row">
	<?php if ( is_active_sidebar( 'sidebar-footer-1' ) ) : ?>
	<div id="first" class="widget-area four columns">
		<?php dynamic_sidebar( 'sidebar-footer-1' ); ?>
	</div><!-- #first .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-footer-2' ) ) : ?>
	<div id="second" class="widget-area five columns">
		<?php dynamic_sidebar( 'sidebar-footer-2' ); ?>
	</div><!-- #second .widget-area -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-footer-3' ) ) : ?>
	<div id="third" class="widget-area three columns">
		<?php dynamic_sidebar( 'sidebar-footer-3' ); ?>
	</div><!-- #third .widget-area -->
	<?php endif; ?>
</div><!-- #supplementary -->
<!-- END: sidebar-footer.php -->