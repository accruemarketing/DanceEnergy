<?php
/**
 * Slideshow Gallery About Dashboard v1.4.4.3
 */

?>

<div class="wrap slideshow slideshow-gallery about-wrap">
	<h1><?php echo sprintf( 'Welcome to Slideshow Gallery %s', $this -> version); ?></h1>
	<div class="about-text">
		<?php echo sprintf('Thank you for installing! Slideshow Gallery %s is more powerful, reliable and versatile than before. It includes many features and improvements.', $this -> version); ?>
	</div>
	<div class="slideshow-gallery-badge"><?php echo sprintf('Version %s', $this -> version); ?></div>
	
	<div class="changelog">
		<h3><?php echo  'What\'s new in this release'; ?></h3>
		<div class="feature-section col three-col">
			<div class="col-1">
				<img src="<?php echo $this -> url(); ?>/images/about/feature-1.png">
				<h4><?php echo 'WordPress 4.0 Compatibility'; ?></h4>
				<p><?php echo 'This version is 100% compatible with the latest WordPress version. It will fit nicely into your WordPress dashboard and maximizes the WordPress capabilities for speed, functionality and reliability.'; ?></p>
			</div>
			<div class="col-2">
				<img src="<?php echo $this -> url(); ?>/images/about/feature-2.jpg">
				<h4><?php echo 'Multilingual'; ?></h4>
				<p><?php echo 'This version of the Slideshow Gallery plugin is fully integrated with (m)qTranslate. It now supports internationalization and multilanguage through (m)qTranslate.'; ?></p>
			</div>
			<div class="col-3 last-feature">
				<img src="<?php echo $this -> url(); ?>/images/about/feature-3.jpg">
				<h4><?php echo 'Responsive Slideshow'; ?></h4>
				<p><?php echo 'The new, responsive option is a flexible foundation that adapts your slideshow to mobile devices and the desktop or any other viewing environment. In this way your slideshow can easily be viewed on a desktop or mobile device.'; ?></p>
			</div>
		</div>
	</div>
	
	<hr>

	<div class="feature-section col two-col">
		<div class="col-1">
			<img src="<?php echo $this -> url(); ?>/images/about/feature-4.jpg">			
			<h4><?php echo 'Compatibility with Thickbox'; ?></h4> 
			<p><?php echo ' Slideshows in this version is compatibile with Thickbox/Lightbox to show slide images in an overlay.'; ?></p>
		</div>
		<div class="col-2 last-feature">
						<img src="<?php echo $this -> url(); ?>/images/about/feature-5.jpg">
						<h4><?php echo 'More than one slideshow'; ?></h4>
						<p><?php echo 'Create a beautiful page with more than one slideshow. You now have the ability to add unlimited slideshows per page, as many as you want. They will all play along nicely!'; ?>
			<p><?php /*echo 'The plugin can automatically send a "Sorry to see you go..." email to a user when they unsubscribe to both confirm their subscription, express your disappointment that they are leaving and it also includes a resubscribe link to convert.'; */?></p>
		</div>
</div>

<hr>
		<div class="changelog under-the-hood">
		<h3>Under the Hood</h3>
	
		<div class="feature-section col three-col">
		<div>
		<h4><?php echo 'Auto Slide'; ?></h4>
		<p><?php echo 'Set your slideshow to autoslide, when viewing a slideshow it will autoslide and it won\'t be necessary for the user to manually flip through the images.'; ?></p>		
		<h4><?php echo 'WordPress Object Cache API'; ?></h4>
		<p><?php echo 'Speed up the plugin with the WordPress Object Cache API which is now built in to cache queries through the WordPress database object.'; ?></p>
		</div>
		<div>
		<h4><?php echo 'Hide information bar on mobile devices'; ?></h4>
		<p><?php echo 'Hide the information bar on mobile devices with a responsive slideshow to view a full slideshow without any text over it..'; ?></p>
		
		<h4><?php echo 'Show latest/feature products from Shopping Cart plugin'; ?></h4>
		<p><?php echo 'Add a slideshow to show your products from the WordPress Shopping Cart plugin. You can choose to show either latest products or feature products images in the slideshow. The images, titles, descriptions, etc. will be automatically pulled from the Shopping Cart plugin, it is fully automated and integrated.'; ?></p>
		</div>
		<div class="last-feature">
		<h4><?php echo 'Revamp of Configuration Settings'; ?></h4>
		<p><?php echo 'The Configurations Settings got a Revamp. New Sliders for speed settings, Color picker for color settings, Debugging setting and more.'; ?></p>
		
		<h4><?php echo 'Child Theme Folder Support'; ?></h4>
		<p><?php echo 'The best way to make modifications to template files in the Slideshow Gallery plugin is to create a child theme folder for the plugin inside your WordPress theme folder. This version now supports a child theme folder.'; ?></p>
		</div>
		
		</div>
		
		<hr>
		
		<div class="return-to-dashboard">
		<a href="<?php echo admin_url('admin.php'); ?>?page=<?php echo $this -> sections -> welcome; ?>"><?php echo 'Go to Slideshow Gallery overview'; ?></a>
		</div>
	
	</div>
</div>