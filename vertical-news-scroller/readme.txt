=== Vertical News Scroller ===
Contributors:nik00726
Tags:news ticker,scrolling news,news widget,vertical scroller,gutenberg block
Donate link: http://www.i13websolution.com/donate_for_news_scroller.php
Requires at least:3.0
Tested up to:7.0
Stable tag:1.31
Version:1.31
License:GPLv2 or later
License URI:http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

**Vertical News Scroller** turns any sidebar, footer, or page section into a live scrolling news ticker - no coding required. Add unlimited news items, choose how they scroll, and drop the scroller in with a widget, shortcode, or the native block editor.

Whether you're running a news site, a school or club website, a business homepage with announcements, or just want a "what's new" ticker that doesn't take up much space, this plugin gets it on your page in a couple of minutes.

* [Live Demo](http://blog.i13websolution.com/live-preview-vertical-news-scroller/)
* [Vertical News List Shortcode Demo](http://blog.i13websolution.com/vertical-news-list-demo/)

= Why people use it =

* **Two scroll styles** - a smooth jQuery slide-up scroller, or a simple continuous marquee-style scroll
* **Works everywhere** - native Gutenberg block, classic widget, or shortcode - use whichever fits your site
* **Reliable on modern themes** - built on a lightweight v3 scroll engine that fixes the inconsistent behavior older ticker plugins are known for
* **Full control** - set scroll speed, direction, height, width, and whether to show the news content or just the title
* **Custom links** - send each news item to any URL, opening in the same tab or a new one
* **Lightweight** - no bloated settings, no tracking, no ads

= Want more? =

The Pro version adds unlimited news categories, automatic RSS feed integration (pull in headlines from any site), thumbnail images, custom colors and fonts, 4 visual style presets, a lightbox preview popup, and colored item labels (News/Event/Announcement/Notice). See the full comparison at [Vertical News Scroller Pro](https://www.i13websolution.com/product/wordpress-vertical-news-scroller-pro/).


= Features =

1. Add unlimited news items from a clean admin screen
2. Two scroll styles: smooth jQuery scroller or classic marquee-style scroll
3. Native Gutenberg block - search "News Scroller" in the block inserter
4. Classic widget support - drag and drop into any widget area
5. Shortcode support for placing the scroller anywhere
6. Lightweight v3 scroll engine (default) - reliable scrolling across most themes, with v1/v2 kept for backward compatibility
7. Control scroll speed, direction, height, width, and padding
8. Choose to show full news content or just the title
9. Add a custom link to any news item, opening in the same tab or a new one
10. Two visual styles (Default and Card) to match your site
11. No coding knowledge needed - works out of the box
12. Responsive admin layout

= Pro Version Features =

1. Unlimited news categories - organize news by section instead of one combined list
2. Automatic RSS feed integration - pull in headlines from BBC, Reuters, or any site and mix them with your own news
3. Thumbnail image support for every news item
4. 4 visual style presets: Default, Card, Minimal, Headline
5. Lightbox preview popup - readers preview a headline without leaving your page
6. Colored item labels: News, Event, Announcement, Notice (with customizable text)
7. Custom title/content colors and font sizes
8. News list shortcode (static list, no scrolling) for archives and footers
9. Manual news ordering and mass reorder
10. Turn any existing post or page into a news item with one checkbox
11. Role and capability support for multi-author sites
12. Multisite compatible
13. No ads, ever

[Get Support](http://www.i13websolution.com/contacts) 


== Installation ==

1. Upload the vertical-news-scroller folder to the /wp-content/plugins/ directory, or install directly through the WordPress plugin search.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the new "Manage Scrolling News" menu in your admin sidebar to add your first news items.

### Usage ###

1. After activating, go to **Manage Scrolling News** in your admin sidebar and add at least two or three news items to get started (any number works, but a few items look best while scrolling).

2. Add the scroller to your site using whichever method fits your workflow:

* **Block editor** - edit any post or page, click Add Block, search for "News Scroller", and configure it from the sidebar panel.
* **Widget** - go to Appearance → Widgets and drag "Vertical news scroll" into any widget area (sidebar, footer, etc).
* **Shortcode** - paste this anywhere shortcodes are supported: `[print_vertical_news_scroll maxitem="5" padding="10" add_link_to_title="1" show_content="1" delay="60" height="200px" width="100%" lib="v3"]`

3. That's it - your scrolling news will appear on the frontend.



== Screenshots ==

1. Vertical News Scroller in action on the frontend
2. The Pro version's expanded styling and thumbnail support
3. Widget settings panel
4. Manage News admin screen - add, edit, and organize your news items
5. Pro version: manage multiple news categories
6. Pro version: Manage News screen with categories and thumbnails
7. Pro version: add or edit a news category
8. Pro version: add or edit a news item with thumbnail support
9. Pro version: widget settings with full style control
10. Pro version: static news list shortcode (no scrolling)

== License ==

This plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But you can make some donations if you realy find it useful.

== Changelog ==


= 1.31 =

* Removed the donate button and a redundant "Upgrade to Pro" banner from the admin screens.
* Added Block Editor (Gutenberg) usage instructions to the Manage Scrolling News help section, alongside the existing shortcode instructions.


= 1.30 =

* Fixed scroller width using max-width instead of width, which could make it render wider than its parent container on some themes.
* The previous update's "card" look is no longer applied automatically - it's now an optional Visual Style choice (Default or Card) in the shortcode, widget, and block, so existing scrollers keep their original appearance after updating.


= 1.29 =

* Added a Pro features sidebar to the Manage Scrolling News page, replacing the leftover empty space after removing third-party ad banners - shows what's included in the Pro version at a glance.


= 1.28 =

* Added a native Gutenberg block ("News Scroller") - add a scroller from the block inserter instead of pasting a shortcode.
* Removed third-party promotional banners from the admin screens.
* Added an admin menu icon for easier recognition in the WordPress sidebar.


= 1.27 =

* Added new v3 scroll engine - lighter and more reliable across themes, now used by default for both the shortcode and widget. Use lib="v1" or lib="v2" in your shortcode if you need the previous engines for compatibility.
* Fixed a bug where the widget's scroll engine setting could be incorrectly sanitized and not save properly.
* Refreshed the default visual look of the scroller (clean card-style appearance) for a more polished out-of-the-box experience.
* Reorganized the shortcode documentation in the admin screen for clarity, with copyable code snippets.


= 1.26 =

* Fixed news link update not working



= 1.25 =

* Make shortcode compatible with block editor



= 1.24 =

* Fixed latest Theme add p tags to script tags
* Tested with WordPress 6.3


= 1.23 =

* Fixed security issue.
* Tested with WordPress 6.2


= 1.22 =

* Remove link as required field.
* Tested with WordPress 6.1


= 1.21 =

* Fixes undefined variable errors.
* Tested with WordPress 5.8


= 1.20 =

* Security fixes. 


= 1.19 =

* Security fixes. 


= 1.18 =

* Security fixes and fixed shorten url. 

= 1.17 =

* Security fixes


= 1.16 =

* Added V2 library for modern jQuery scroll. For those who have repeated news scroll problem after few min of page load can use v2
* Tested with WordPress 5.7


= 1.15 =

* Set publish date time as local wp date and time
* Tested with WordPress 5.5


= 1.13 =

* Fixed marquee not working
* Tested with WordPress 5.4


= 1.12 =

* Improve news scroller loading.


= 1.11 =

* Fix for slash not supported (apostrophe)
* Tested with WordPress 5.3


= 1.10 =

* Vulnerability fixes. Sanitize, escape, and validate your POST/GET.

= 1.9 =

* Improve code so that plugin continue work even jquery added to footer.
* Tested with WordPress 5.2

= 1.8 =

* Tested with WordPress 5.1
* Fixed Css issue in admin for WordPress 5.1

= 1.7 =

* Added capabilities for admin role


= 1.6 =

* Added facility to allow roles and capabilities for "Manage News"
* Bug fix for undefined error in debug.log 

= 1.5 =

* Improve news scroller so that if some one in pro version will not loose free version data
* Improve admin UI
* Tested with WordPress 5.0


= 1.4 =

* Now support jQuery scroller as well as marque scroller



= 1.3.2 =

* Fixed for shortcode not workin in wordpress 4.8
* Tested upto wordpress 4.8


= 1.3.1 =

* Added support for shortcode
* Tested upto wordpress 4.6


= 1.3 =

* Added support for single quote and double quote in news title .

= 1.2 =

* Bug fix for adding news with single quote and double quote 
  in news content and title.

* Css changes for admin panel.

* Release Pro version.

= 1.1 =

* Css not loading issue fixed.

* Add News Form validation not working fixed.


= 1.0 =

* Stable 1.0 first release


== Upgrade Notice ==

= 1.30 =

* Fixes a scroller width issue and reverts the default visual style back to original - no action needed.

= 1.27 =

* The scroller now defaults to the new v3 engine. If you customized scroller behavior with lib="v1" or lib="v2" in your shortcode, that still works as before.

= 1.12 =

* Please clear WordPress Cache( If you have installed any cache plugin)


= 1.11 =

* Please clear WordPress Cache( If you have installed any cache plugin)


= 1.9 =

* Please clear WordPress Cache( If you have installed any cache plugin)

= 1.3.1 =

* added support for shortcode.

= 1.3 =

* Added support for single quote and double quote in news title .

* Bug fix for adding news with single quote and double quote 
  in news content and title.

* Css changes for admin panel.

* Release Pro version.

= 1.1 =

* Css not loading issue fixed.

* Add News Form validation not working fixed.

= 1.0 =

* Stable 1.0 first release

 
== Frequently Asked Questions ==

= How do I add the news scroller to my site? =

Three ways: search "News Scroller" in the block editor's block inserter, drag the "Vertical news scroll" widget into any widget area under Appearance → Widgets, or paste the shortcode shown on the Manage Scrolling News admin page. All three work the same way under the hood, so pick whichever fits how you build your site.

= Where do I find the shortcode and its options? =

Go to Manage Scrolling News in your admin sidebar. The shortcode, its options, and a one-click copy button are shown right there, along with the block editor instructions.

= Which scroller engine should I use - v1, v2, or v3? =

v3 is the default and recommended option - it's lighter and more reliable across different themes. v1 and v2 are kept available for sites that already had a shortcode using them before this update, so nothing breaks on upgrade.

= What's the difference between the Default and Card visual styles? =

Default keeps the plugin's original look (underlined title, justified text). Card adds a boxed, shadowed container around each news item for a more modern feel. Both are available in the shortcode, widget, and block.

= Can I organize my news into categories? =

The free version displays all news items together. The Pro version adds unlimited categories, so you can run separate scrollers for different sections of your site (for example, one for company news and another for events).

= Can I pull in news from other websites automatically? =

That's a Pro feature - RSS feed integration lets you connect any feed (BBC, Reuters, your own blog, etc.) and the plugin automatically mixes those headlines in with your own news, sorted by date.

= Does the scroller support thumbnail images? =

Thumbnail support is included in the Pro version, along with custom colors, fonts, and 4 visual style presets.

= Will updating this plugin change how my existing scroller looks? =

No. Updates are designed to be backward compatible - any visual or behavior changes are opt-in (for example, the Card style is only applied if you choose it), so an existing scroller keeps working and looking the same after you update.

= Is this plugin translation-ready? =

Yes, all visible text uses standard WordPress translation functions and a .pot file is included in the languages folder.
