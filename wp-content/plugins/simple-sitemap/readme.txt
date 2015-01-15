=== Simple Sitemap ===
Contributors: dgwyer
Tags: sitemap, html, global, sort, shortcode, pages, posts
Requires at least: 2.7
Tested up to: 4.0
Stable tag: 1.65

HTML sitemap to display content as a single linked list of posts and pages, or as groups sorted by taxonomy (via a drop-down box).

== Description ==

Update: From v1.6 Custom Post Types Supported!

This is probably the easiest way to add a powerful HTML sitemap to your site!

Unique and intuitive way to display your website content by simply adding a single shortcode [simple-sitemap] to a post, page, or sidebar (via a Text widget). See the Plugin screenshots for examples.

You can now also add the [simple-sitemap] shortcode to a Text widget too! Rendering of the sitemap is now one column so it fits nicely on posts, pages, or your sidebar.

The order and style in which your blog posts and pages are displayed on the screen depends on the dropdown option chosen in Plugin settings. Posts/pages are rendered as a single linked list of titles, or they can be grouped by date, author, category, and tag (each heading acts as a link the the relevant taxonomy type as well).

This gives your visitors an efficient way to view ALL site content in ONE place. It is also great for SEO purposes and makes it easier for spiders to index your site.

Available sorting options for pages are:

* Title
* Date
* Author

and for posts:

* Title
* Date
* Author
* Category
* Tag

To display the shortcode on a page, or post (though it's recommended you add it to a page) just add the shortcode [simple-sitemap]. Then, simply publish the page and you will now have a sitemap enabled on your website.

Please rate this Plugin if you find it useful, thanks.

See our <a href="http://www.wpgothemes.com" target="_blank">WordPress development site</a> for more information.

== Installation ==

1. Via the WordPress admin go to Plugins => Add New.
2. Enter 'Simple Sitemap' (without quotes) in the textbox and click the 'Search Plugins' button.
3. In the list of relevant Plugins click the 'Install' link for Simple Sitemap on the right hand side of the page.
4. Click the 'Install Now' button on the popup page.
5. Click 'Activate Plugin' to finish installation.
6. Add [simple-sitemap] shortcode to a page to display the sitemap on your site.

== Screenshots ==

1. Simple Sitemap displays a list of pages and posts side-by-side. Sort options via drop-down box.
2. Just activate the Plugin and add [simple-sitemap] shortcode to a page to display the sitemap.
3. Plugin admin options allow you to swap pages/posts display order, and choose which drop-down options get selected by default.
4. Shows the results of changing Plugin admin options to swap posts/page display order.

== Changelog ==

*1.65 update*

* More settings page updates.

*1.64 update*

* Settings page updated.

*1.63*

* Fixed bug with CPT links.

*1.62*

* Sitemap shortcode now works in text widgets.

*1.61*

* Fixed bug limiting CPT posts to displaying a maximum of 5 each.

*1.6*

* Links on Plugins page updated.
* Removed front end drop downs. Sitemap rendering now solely controlled via plugin settings.
* Support for Custom Post Types added!

*1.54*

* Security issue addressed.

*1.53*

* All functions now properly name-spaced.
* Added $wpdb->prepare() to SQL query.

*1.52*

* Updated Plugin options page text.
* Now works nicely in sidebars (via a Text widget)!
* Fixed bug where existing Plugin users saw no posts/pages on the sitemap after upgrade to 1.51.
* Added a 'Settings' link to the main Plugins page, next to the 'Deactivate' link to allow easy navigation to the Simple Sitemap Plugin options page.

*1.51*

* Updated WordPress compatibility version.
* Update to Plugin option page text.

*1.5*

* Updated for WordPress 3.5.1.
* Minor CSS bug fixed.
* ALL Plugin styles affecting the sitemap have been removed to allow the current theme to control the styles. This enables the sitemap to blend in with the current theme, and allows for easy customisation of the CSS as there are plenty of sitemap classes to hook into.
* All sitemap content is now listed in a single column to allow for additional listings for CPT to be added later.
* New Plugin options to show/hide posts or pages.

*1.4.1*

* Minor updates to Plugin options page, and some internal functions.

*1.4*

* Plugin option added to exclude pages by ID!
* Bug fix: ALL posts are now listed and are not restricted by the Settings -> Reading value.

*1.3.1*

* Fixed HTML bug. Replaced deprecated function.

*1.3*

* Dropdown sort boxes on the front end now work much better in all browsers. Thanks to Matt Bailey for this fix.

*1.28*

* Changed the .sticky CSS class to be .ss_sticky to avoid conflict with the WordPress .sticky class.

*1.27*

* Fixed minor bug in 'Posts' view, when displaying the date. There was an erroneous double quotes in the dates link.

*1.26*

* Fixed CSS bug. Was affecting the size of some themes Nav Menu font sizes.

*1.25*

* Now supports WordPress 3.0.3
* Updated Plugin options page
* Fixed issue: http://wordpress.org/support/topic/plugin-simple-sitemap-duplicated-id-post_item
* Fixed issue: http://wordpress.org/support/topic/plugin-simple-sitemap-empty-span-when-post-is-not-sticky

*1.20*

* Added Plugin admin options page
* Fixed several small bugs
* Sitemap layout tweaked and generally improved
* Added new rendering of sitemap depending on drop-down options
* New options to sort by category, author, tags, and date improved significantly

*1.10 Fixed so that default permalink settings work fine on drop-down filter*

*1.01 Minor amendments*

*1.0 Initial release*