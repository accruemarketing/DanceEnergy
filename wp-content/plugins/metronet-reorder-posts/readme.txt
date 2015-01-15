=== Reorder Posts ===
Contributors: ryanhellyer, metronet, ronalfy, scottbasgaard
Author URI: https://github.com/ronalfy/reorder-posts
Plugin URL: https://wordpress.org/plugins/metronet-reorder-posts/
Requires at Least: 3.7
Tested up to: 4.1
Tags: reorder, re-order, posts, wordpress, post-type, ajax, admin, hierarchical, menu_order, ordering
Stable tag: 2.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple and easy way to reorder your custom post-type posts in WordPress.

== Description ==

A simple and easy way to reorder your custom post-type posts in WordPress. Adds drag and drop functionality for post ordering in the WordPress admin panel. Works with custom post-types and regular posts.

A settings panel is available for determining which post types to enable ordering for.  Advanced options allow you to change the menu order for post types.

<h3>Features</h3>
<ul>
<li>Adds "Reorder" sub-menu to all post types by default</li>
<li>Hierarchical post type support (i.e., supports nested posts)</li>
<li>Allows you to re-nest hierarchical posts</li>
<li>Auto-saves order without having to click an update button</li>
<li>Dedicated settings panel for determining which post types can be reordered</li>
<li>Advanced settings panel for overriding the menu order of custom post type queries</li>
</ul>

Advanced customization is allowed via hooks.  See the <a  href="https://github.com/ronalfy/reorder-posts#plugin-filters">Plugin Filters on GitHub</a>.

<h3>Spread the Word</h3>
If you like this plugin, please help spread the word.  Rate the plugin.  Write about the plugin.  Something :)

<h3>Translations</h3>
 None so far.

If you would like to contribute a translation, please leave a support request with a link to your translation.

You are welcome to help us out and <a href="https://github.com/ronalfy/reorder-posts">contribute on GitHub</a>.

<h3>Support</h3>

Please feel free to leave a support request here or create an <a href="https://github.com/ronalfy/reorder-posts/issues">issue on GitHub</a>.  If you require immediate feedback, feel free to @reply us on Twitter with your support link:  (<a href="https://twitter.com/ryanhellyer">@ryanhellyer</a> or <a href="https://twitter.com/ronalfy">@ronalfy</a>).  Support is always free unless you require some advanced customization out of the scope of the plugin's existing features.  We'll do our best to get with you when we can.  Please rate/review the plugin if we have helped you to show thanks for the support.

<h3>Credits</h3>
This plugin was originally developed for <a href="https://metronet.no/">Metronet AS in Norway</a>.

The plugin is now independently developed by <a href="https://geek.hellyer.kiwi/">Ryan Hellyer</a>, <a href="http://www.ronalfy.com">Ronald Huereca</a> and <a href="http://scottbasgaard.com/">Scott Basgaard</a>.

Banner image courtesy of <a href="https://www.flickr.com/photos/pagedooley">Kevin Dooley</a>.

== Installation ==

Either install the plugin via the WordPress admin panel, or ... 

1. Upload `metronet-reorder-posts` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

For each post type, you will see a new "Reorder" submenu.  Simply navigate to "Reorder" to change the order of your post types. Changes are saved immediately, there is no need to click a save or update button.  

By default, ordering is enabled for all post types.  A settings panel is available for determining which post types to enable ordering for.  Advanced options allow you to change the menu order for post types.

== Frequently Asked Questions ==

= Where's the settings page? =

The settings are located under Settings->Reorder Posts.  Settings are optional, of course, as the plugin will work with no configuration.  We consider the settings useful for only advanced users (i.e., users with coding experience).

= Where is the "save" button when re-ordering? =

There isn't one. The changes are saved automatically.

= Do I need to add custom code to get this to work? =

Yes, and no.  There are many ways to retrieve posts using the WordPress API, and if the code has a `menu_order` sort property, the changes should be reflected immediately.

Often, however, there is no `menu_order` argument.  In the plugin's settings, there is an "Advanced" section which will attempt to override the `menu_order` property.  Please use this with caution. 

= Can I use this on a single post type? =

You are able to override the post types used via a filter (see below) or navigate to the plugin's settings and enable which post types you would like to use.

`<?php

add_filter( 'metronet_reorder_post_types', 'slug_set_reorder' );
function slug_set_reorder( $post_types ) {
	$post_types = array( 'my_custom_post_type', 'my_other_post_type' );
	return $post_types;
}

?>`

= Does the plugin work with hierarchical post types? =

Yes, but be wary that the plugin now allows you to re-nest hierarchical items easily.

= Does it work in older versions of WordPress? =

This plugin requires WordPress 3.7 or above.  We urge you, however, to always use the latest version of WordPress.

== Screenshots ==

1. Reorder Posts allows you to easily drag and drop posts to change their order
2.  Admin panel settings

== Changelog ==

= 2.0.2 =
* Released 2014-12-26
* Bug fix:  Saving admin panel settings resulted in a variety of PHP offset error messages.
* Bug fix:  Querying multiple post types resulted in PHP illegal offset error messages.

= 2.0.1 =
* Released 2014-12-23
* Altered contributor documentation.
* Adding filters for determining where the Reorder sub-menu will show up.
* Sub-menu headings now reflect the post type that is being re-ordered.
* Fixed bug in display when there are no post types to re-order.
* Changed class names to be more unique.

= 2.0.0 =
* Released 2014-12-12 
* Added settings panel for enabling/disabling the Reorder plugin for post types.
* Added advanced settings for overriding the menu order of post types.
* Added internationalization capabilities. 
* Slightly adjusted the styles of the Reordering interface.

= 1.0.6 =
* Updated 2014-12-11 - Ensuring WordPress 4.1 compatibility
* Released 2013-07-19
* Added new filter for editing the post-types supported
* Thanks to mathielo for the suggestion and code contribution.

= 1.0.5 =
* Released 2012-08-09
* Added expand/collapse section for nested post types
* Added better page detection for scripts and styles

= 1.0.4 =
* Released 2012-07-11
* Added support for hierarchical post types

= 1.0.3 =
* Released 2012-05-09
* Updated screenshot
* Corrected function prefix
* Additional: changed readme.txt (didn't bump version number)

= 1.0.2 =
* Released 2012-05-09
* Added ability to post type of posts to be reordered
* Fixed bug in initial order

= 1.0.1 =
* Added ability to change menu name via class argument
* Removed support for non-hierarchical post-types

= 1.0 =
* Initial plugin release

== Upgrade Notice ==

= 2.0.2 =
Bug fixes with PHP error notices.  Highly recommend you upgrade.

= 2.0.1 =
Filter additions, and several bug fixes.

= 2.0.0 =
New admin panel settings for setting Reorder for post types, and advanced options for modifying the menu order of post type queries.
