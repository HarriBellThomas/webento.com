Our FAQs plugin makes it easy to create FAQ sections on your WordPress powered blog. Simply activate and add FAQ items + groups, then display them on a post or page by using the shortcode.

= Features = 

*	Create as many FAQ items and groups as you like
*	Utilises WordPress custom post types and taxonomies
*	Output your FAQ's using a simple shortcode
*	Choose to enable 'folding' - powered by javascript (click to open and close).
*	Optional index at the top of each FAQ

= Installation =

*	Upload the wp-faq plugin folder to your wp-content/plugins/ directory.
*	Activate the plugin from the WordPress admin panel
*	Your ready! In the WP admin panel you will now see the 'FAQ' section, just below posts.
*	Create FAQ items (just like posts) - you can even place them in categories (groups) if you want multiple FAQ lists.
*	To show the FAQ use our [faq] shortcode in a post or page. OR if you want to show it in the template itself use: echo do_shortcode('[faq]');

= Shortcode Instructions =

The [faq] shortcode takes a few arguments:

*	id (optional) - ID of group to show
*	name (optional) - Name of group to show
*	slug (optional) - Slug of group to show
*	folding (optional) - true by default. Set to true to enable jQuery folding up of items
*	orderby (optional) - title by default. Set to 'order' to use the custom ordering field found on the edit faq item page.
*	show_index (optional) - true by default. Set to false to hide the index at the top of the faq list.

You *must* use either id, name, or slug so the plugin knows what group to show, otherwise it will show all FAQ items.

= Change Log =

1.0 - First Release

1.0.1 - 09/02/11
	- Potential date bug fixed
	- Added filters to get_the_content()