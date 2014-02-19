=== Plugin Name ===
Contributors: shawneggplantstudiosca
Donate link: none
Tags: 301 redirects, redirects
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily manage and create 301 redirects for your Wordpress website. A robust interface allows you create and validate redirects.

== Description ==

**EPS 301 Redirects?** helps you manage and create 301 redirects for your Wordpress website. A robust interface allows you to select the *Destination URL* from drop down menus, and validates your redirects for troubleshooting.


**What is a 301 Redirect?** 
A redirect is a simple way to re-route traffic coming to a *Requested URL* to different *Destination URL*. 

A 301 redirect indicates that the page requested has been permanently moved to the *Destination URL*, and helps pass on the *Requested URLs* traffic in a search engine friendly manner. Creating a 301 redirect tells search engines that the *Requested URL*  has moved permanently, and that the content can now be found on the *Destination URL*. An important feature is that search engines will pass along any clout the *Requested URL* used to have to the *Destination URL*.


**When Should I use EPS 301 Redirects?** 
1. Replacing an old site design with a new site design
1. Overhauling or re-organizing your existing Wordpress content
1. You have content that expires (or is otherwise no longer available) and you wish to redirect users elsewhere.


**Features** 
* Choose from Pages, Posts, Custom Post types, Archives, Term Archives, 
* Creates its own database table, for faster indexing




Created by Shawn Wernig [Eggplant Studios](http://www.eggplantstudios.ca/ "Eggplant Studios")

*This release is compatible with all Wordpress versions since 3.0.1 (Tested. Plugin may not work as intended for older versions of Wordpress).*

*This plugin is English only; though it is somewhat language independent.*

== Installation ==



1. Upload the `eps-301-redirects` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Begin adding redirects in the Settings -> EPS 301 Redirects menu item.



== Frequently Asked Questions ==

= I'm getting an error about the default permalink structure? =

EPS 301 Redirects requires that you use anything but the default permalink structure. 


= My redirects aren't working =

This could be caused by many things, but please ensure that you are supplying valid URLs. Most common are extra spaces, spelling mistakes and invalid characters.


= My redirects aren't getting the 301 status code =

Your Request or Redirect URLS may be incorrect; please ensure that you are supplying valid URLs. Check slashes. Try Viewing the page by clicking the TEST button - does it load correctly?


= How do I delete a redirect? =

Click the small X beside the redirect you wish to remove. Save changes.





== Screenshots ==

1. The administrative area, Wordpress 3.5.1
2. Examples of some of the dropdown options. 
3. The redirect testing feature. 

== Changelog ==

= 2.0.1 =
Fixed an issue where the Automatic Update would not call the import process for pre 2.0 versions.

= 2.0.0 =
Overhauled the entire plugin. Redirects are stored in their own table. Gracefully migrates older versions.

= 1.4.0 =
* Performance updates, added a new 'Settings' page.

= 1.3.5 =
* Fixed a bug with spaces in the url. Added ease of use visual aids.


= 1.3.4 =
* Fixed nonce validation problem which would prevent saving of new redirects. Special Thanks to Bruce Zlotowitz for all his testing!

= 1.3.3 =
* Fixed major problem when switching from 1.2 to 1.3+


= 1.3.1 =
* Added hierarchy to heirarchical post type selects.


= 1.3 =
* Fixed a bug where duplicate urls were being overwritten, fixed a bug where you could not completely remove all redirects.

= 1.2 =
* Fixed some little bugs.

= 1.1 =
* Minor CSS and usability fixes. Also checking out the SVN!

= 1.0 =
* Release.

== Upgrade Notice ==

= 2.0.1 =
Fixed an issue where the Automatic Update would not call the import process for pre 2.0 versions.

= 2.0.0 =
Overhauled the entire plugin. Redirects are stored in their own table. Gracefully migrates older versions.

= 1.4.0 =
* Performance updates, added a new 'Settings' page.

= 1.3.5 =
Fixed a bug with spaces in urls. Also added a test for both the request and destination urls to verify that they're working as intended.

= 1.3.4 =
Fixed a bug when saving new redirects.

= 1.3.3 =
Compatibility update for users upgrading from 1.2 to 1.3+ - Update ASAP.

= 1.3.1 =
Functionality update, Cosmetic.

= 1.3 =
Bug fixes; Update ASAP.

= 1.2 =
Cosmetic updates.

= 1.1 =
Cosmetic updates.

= 1.0 =
Released May, 2013.