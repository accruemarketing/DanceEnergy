<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the class=container div and all content after
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.1.0
 */
?>
		<?php
			/*
				A sidebar in the footer? Yep. You can can customize
				your footer with three columns of widgets.
			*/
			if ( ! is_404() )
				
			?>
		</div><!--Inner Container -->
		<div class="foot_wrap">
			<div class="lines">
				<div class="foot_pre"></div>
				<div class="foot_pre"></div>
				<div class="foot_pre"></div>
			</div>
			<div id="footer" class="row" role="contentinfo">
				<div class="twelve columns">
					<?php get_sidebar( 'footer' ); ?>
				</div>
			</div>
			<div class="lines">
				<div class="foot_pre"></div>
				<div class="foot_pre"></div>
				<div class="foot_pre"></div>
			</div>

		</div>
			<div id="copyright" >
				<div class="row">	
					<div class="four columns">

					</div>
					<div class="eight columns">
						<a href="" id="copyend">&copy; COPYRIGHT DANCENERGY <?php echo date("Y"); ?>, CALGARY ALBERTA CANADA</a>
					</div>
				</div>
			</div>
	</div><!-- Container End -->
</div><!--END ANGULAR CONTROLLER-->
<script type="text/javascript">
jQuery("#tip4").click(function() {
	jQuery.fancybox({
			'padding'		: 0,
			'autoScale'		: false,
			'transitionIn'	: 'none',
			'transitionOut'	: 'none',
			'title'			: this.title,
			'width'		: 680,
			'height'		: 495,
			'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
			'type'			: 'swf',
			'swf'			: {
			   	 'wmode'		: 'transparent',
				'allowfullscreen'	: 'true'
			}
		});

	return false;
});
</script>

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
	     chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7]>
		<script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

	<?php wp_footer(); ?>
</body>
</html>