=== Exclusive Content Password Protect ===
Contributors: Cliconomics
Donate link: http://www.cliconomics.com
Tags: password, protect, exclusive content
Requires at least: 2.7 or higher
Tested up to: Version 3.1
Stable tag: 1.1.0

Exclusive Content Password Protect is a plugin that allows you to hide a section (or multiple sections) of content on your page or post.

== Description ==

Exclusive Content Password Protect is a plugin that allows you to hide a section (or multiple sections) of content on your page or post. Use this to create exclusive content that you only want email or RSS subscribers to have access to. Once they enter in the global password once, they will have access to all content from then on. Plus you can use passwords for certain sections to have even more exclusive content.


== Installation ==

1. Unzip the downloaded package and upload the 'exclusive-content-password-protect' folder into the 'wp-content/plugins' directory via FTP
2. Login into your WordPress Admin Panel
3. Activate the plugin 'Exclusive Content Password Protect' through the Plugins menu in WordPress
4. Go to your post/page and put [password-protect][/password-protect] short code to protect certain section of your content.
Note: You must enclosed your content like this [password-protect]your content here[/password-protect]
5. You can customize the global password and interface by logging in your Wordpress Admin and clicking the 'Content Protect' in your admin sidebar.

How to use:

Global password: [password-protect]your content here[/password-protect]

Custom password: [password-protect password="MySecretContent"]your content here[/password-protect]


== Frequently Asked Questions ==

= Visit http://www.cliconomics.com/exclusive-content-password-protect/ for more details =


== Screenshots ==

1. Front-end interface
2. Admin interface
3. Admin interface with color picker

== Changelog ==

= 1.1.0 =
* Fix form wont unlock since cookies are not saved.

= 1.0.9 =
* Fix saving settings in admin panel.

= 1.0.8 =
* Fixed jQuery.noConflict() issue.

= 1.0.7 =
* Settings variable related to the plugin is now prefixed with ecpp_. This will prevent conflict with other plugin settings.
* Fixed jQuery.noConflict() issue.

= 1.0.6 =
* Fixed unlock entire site which have been locked whenever the user unlocks one page.

= 1.0.5 =
* Moved js script to submit form via ajax to the main php plugin file.

= 1.0.4 =
* Fix random display of ajax.php after form submission

= 1.0.2 =
* Added default background image
* Added screenshot

== Upgrade Notice ==

== Arbitrary section ==

== Features ==

* Customable UI
* Global password and custom password for each page/post
* Easy to use
