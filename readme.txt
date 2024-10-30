=== Keyword Meta ===
Contributors: appleuser
Tags: tags, head, meta, keywords, search engine, seo, description, open graph, opengraph, touch-icon, touch icon, google verify id
Requires at least: 5.0
Tested up to: 5.7.2
Stable tag: 3.2.1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5762TWVRT6RQ4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use your post or page excerpt to define description for a page automatically. Add OpenGraph-Meta, Google verification or touch-icon-support to your page.

== Description ==
Use your post or page excerpt to define description for a page automatically. Add OpenGraph-Meta, Google verification or touch-icon-support to your page in an easy way.

== Installation ==
1. Upload `keyword-meta` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Customize keywords for main page → Settings → "Keyword Meta"

== Changelog ==
= 3.2.1 =
* fixed security issues
= 3.0 =
* removed google call for jQueryUI-library and 
* replaced complete jQueryUI with tab-widget only to reduce loading size
* fixed a bug with quality-check in description textarea
= 2.1 =
* removed keywords from meta-section
* fixed bug with missing descriptions and/or category descriptions
* fixed a bug with remaining tags in description got from excerpt
= 2.0.1 =
* fixed bug with empty textareas
= 2.0 =
* cleaned sourcecode
* added media library support to choose open graph image and touch icons from library
* fixed an error where counting and coloring of keyword and description field did not work
= 1.3 =
* fixed an error where script was loaded before jquery so it couldn't access jquery-functions
= 1.2 =
* fixed bug that no keywords were shown on pages except front page when no tags were set
* added option to choose, whether keyword meta will combine page based keywords from tags with site based keywords from keyword meta prefs or just show the page based keywords if they were set 
= 1.1 =
* changed jQueryUI-library to 1.12.1
* updated/fixed stylesheet loading 
= 1.0 =
* completely rewritten and added a lot of features
* Visualisation of your keyword and description char count
* OpenGraph support
* Touch Icon support
* Google Verify ID 
= 0.6 =
* fixed an error with removing option while deinstalling
= 0.5 =
* stability check to wordpress 4.6
* integrated function_exists check to all functions
= 0.4 =
* updated developer information
= 0.3 =
* changed user `level integer` to `capability string`
* compatibility changes to wordpress 4.5
= 0.2 =
* german translation fixed
= 0.1 =
* first release