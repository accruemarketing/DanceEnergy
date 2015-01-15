=== Image Rotator Widget ===
Contributors: douglaskarr, srcoley 
Tags: jquery, widget, widgets, image, images, rotator, slider, logo, transition, sidebar
Stable tag: 1.0.2
Version: 1.0.2
Requires at least: 2.7
Tested up to: 4.0

A sidebar widget for rotating and displaying images in your sidebar, allowing you to loop, set the speed, target a new window if linked, and even randomize the order! Great for displaying client logos on a corporate site.

== Description ==

A widgetized plugin that puts an image rotator on your theme in any sidebar. You can choose from three different transitions: linear, loop, and fade. In the widget settings you have the ability to set the transition speed, apply nofollow if linked, open links in a new window, upload/select images to add to the Image Rotator, and to drag & drop to reorder the order you want the images to appear. Or you can set randomize and each time the widget loads it will start with a random image and work its way through your array. Watch the video below for a short demonstration of workflow. 

http://www.youtube.com/watch?v=D7YMN8b0Olg

Features include:

* Uses the WordPress 3.5 Media Uploader to upload or select images
* Options to make the image link to another page and even in a new window
* Choose from three different smooth transitions
* Set the transition speed
* Drag & drop to order the images or set to random to have them loaded in a different order each time
* Apply rel=“nofollow” for search engine optimization
* Use the Image Rotator Widget multiple times on the same page
* Works with modern versions of Chrome, Safari, Firefox, and Internet Explorer
* Includes `jQuery`.`qtip` and `jQuery`.`imagesLoaded`

= About =

The Image Rotator Widget was written by [Stephen Coley](http://coley.co) of [DK New Media](http://dknewmedia.com)

== Installation ==

Here's how you can get started quickly.

1. Install the Image Rotator Widget either via the WordPress.org plugin directory, or by uploading the files to your server
2. After activating the Image Rotator Widget from DK New Media, navigate to `Appearance` -> `Widgets`
3. Drag the `Image Rotator Widget` box to the sidebar region you wish to display it in.
4. Select from one of the transition types.
5. Add your desired images. You can specify the optional alt tag (recommended) and a link (optional) for the image.
6. Sort the images by dragging them up or down.
7. Save. You’re ready to go!

== Frequently Asked Questions ==

= How do I link an image in the rotator? =

To assign a link to an image, use the "Link To" option while in the Media Uploader.

= Does this plugin require a Widget-ready theme? =

Yes. Without a Widget-ready theme, you will not be able to display the image rotator.

= How do I add alternative text? =

Before inserting the image in the widget, be sure to fill in the alt text field.

= Can I add links to the images? =

Yes. Before inserting the image in the widget, be sure to fill in the link in the media browser. You can set the target of the link in the widget settings.

= What if I still have a question? =

You can ask questions [Here](http://www.dknewmedia.com/ "DK New Media")

== Screenshots ==

1. DK New Media's Image Rotator Widget.
2. Once you've selected or uploaded the image, you must click "Send to Image Rotator."
3. Images added to the widget are listed in dynamically in the Images section.
4. Multiple images have been added to this widget
5. Hover over the image name to see a tooltip that contains the image.
6. Drag & Drop images to sort, right in the widget settings.

== Changelog ==

= 1.0.2 =
Release Date: October 19, 2014

* Enhancement: Adjustment of speed transition to allow for slower transitions.

= 1.0.1 =
Release Date: October 19, 2014

* Enhancement: Updated speed transition to allow for slower transitions.


= 1.0.0 =
Release Date: October 19, 2014

* Enhancement: Updated instructions and Changelog for the widget.
* Bug Fix: Some reports of line 116 not being remarked out were reported. Removed all remarks.

= 0.3.4 =

* Bug Fix: Corrected a minor issue that wasn’t populating the alt tag correctly. Requires you to remove and add the images in order to display the alt tags properly.

= 0.3.3 =

* Enhancement: Enhanced the plugin to display the alt text from the WordPress Media Uploader

= 0.3.2 =

* Bug Fix: Correction of conflict Javascript, transition issues and array treatment that was looping can causing page freezes.

= 0.3.1 = 

* Bug Fix: Correction of a jQuery reference TypeError.

= 0.3.0 =

* Bug Fix: Correction of a jQuery reference TypeError: 'undefined' is not a function (evaluating '$(document)')

= 0.2.9 =

* Bug Fix: Stripped out additional url slashes to prevent getimagesize() errors.
* Bug Fix: Applied patch provided by deltafactory(thanks!) to help relieve mixed content warnings on ssl sites.
* Bug Fix: Added auto width and heights to images, per Tukkan's suggestion, to fix a bug where images weren't appearing in IE.
* Bug Fix: Changed the widget's z-index to 0 to prevent unwanted overlapping.

= 0.2.8 =

* Bug Fix: Switched $_SERVER['DOCUMENT_ROOT'] to get_home_path() to reduce getimagesize() errors

= 0.2.7 =

* Enhancement: Added a feature to randomize the order of images. 

= 0.2.6 =

* Bugfix: PHP getimagesize() is now used with a local file path instead of a url.

= 0.2.5 =

* Enhancement: An option to add a nofollow attribute to image links
* Enhancement: Adds width and height attributes to the image elements
* Enhancement: Links now work with anchor tags instead of a javascript on click events
* Bugfix: No more disappearing links after adding/removing images or after changing the order of the images

= 0.2.4 =

* Enhancement: Now uses the 3.5 Media Uploader
* Enhancement: Added responsive styles as suggested: http://wordpress.org/support/topic/responsive-css?replies=2
* Bugfix: Height issue when using two or more irw widgets on the same page
* Bugfix: Better compatibility for users upgrading from older versions

= 0.2.3 =

* Enhancement: Added the ability to open links in new a tab/window.
* Bugfix: Some images were clicking through to a link that was undefined. This has been fixed.
* Bugfix: Mouse now only turns to the pointer when an image is linked.
* Bugfix: Transition speed for the fade transition now works properly.

= 0.2.2 = 

* Enhancement: Now includes the latest release of imagesloaded.js
* Bugfix: Transition ppeed has been fixed for all three transitions
* Bugfix: Images set to link to themselves would not work

= 0.2.1 =

* Bugfix: Fixes error that shows "unexpected output" when activating.

= 0.2 =

* Bugfix: 0.1.9 bad release.

= 0.1.9 =

* Bugfix: 0.1.8 bad release.

= 0.1.8 =

* Bugfix: Fixes bug with linking images

= 0.1.7 =

* Enhancement: Added ability to make images in the rotator linkable
* Bugfix: 3.4.1 bug

= 0.1.6 =

* Enhancement: Added Transition Speed to the widget settings
* Bugfix: Fixed 3.4 a bug - wouldn't send the image url to the widget

= 0.1.5 =

* Bugfix: Fixed installation bug

= 0.1.4 =

* Enhancement: Added optional widget setting "title".
* Bugfix: Fixed localization bug with "Send the Image Rotator Widget" button

= 0.1.3 =

* Enhancement: Added ability to use the "From URL" tab on the Media Upload when selecting an image.

= 0.1.2 =

* Enhancement: WordPress 3.3 fix

= 0.1.1 =

* readme.txt updates
* Bugfix: Only run widget's scripts on the widget.php page

* Initial release
