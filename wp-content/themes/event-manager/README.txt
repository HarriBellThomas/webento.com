<style>
#readme-info { width: 500px;}
#readme-info ul {margin-left: 30px; list-style-type: disc;}
</style>


<div id="readme-info">

Thank you for choosing to use the Event Manager Theme, built on WordPress and Genesis. 

Helpful links: <a href="http://www.eventmanagerblog.com/store/login" target="_blank">Theme Support</a> | <a href="http://www.billerickson.net/get-genesis" target="_blank">Genesis Theme Framework</a> |  | <a href="http://en.support.wordpress.com/">WordPress Support</a>


<h2>Required Plugins</h2>

Once activating this theme, you should be prompted to automatically install three plugins. The <strong>Event Manager Theme Functionality</strong> plugin contains the core functionality of this theme and is required. This allows us to provide ongoing improvements and new features easily without interfering with any of your theme customizations. The second plugin is <strong>Posts 2 Posts</strong>, which allows you to link Sessions and Speakers. 

If you haven't done so already, please install and activate these plugins. There should be a notice at the top of this screen to start the installation.

<h2>Setup your Event Information</h2>
Go to the <a href="/wp-admin/admin.php?page=event-manager">Event Manager Settings</a> page to set up your event's information. 

In the first box (Event Information) you can define the date, location, and registration information. Sample information will already be entered to help guide you.

In the second box (Homepage Introduction) you can add additional content that displays below the site title on the homepage. By default there is two columns of text, but you can change this by clicking the HTML tab. See the <strong>Content Columns</strong> section below for more information. 

In the third box (Footer Text) you can specify the text on the left and right of the footer. Again, there is sample text to help guide you.

<h2>Menu</h2>

To set up the menu at the top of the page, first go to Genesis > Theme Settings and check "Include Primary Navigation Menu". Then go to Appearance > Menus, type a "Menu Name" (ex: Primary Menu), then click "Save Menu". Then in the left column, select your menu from the "Primary Navigation Menu" dropdown.

Now you can add pages to your menu and have them appear at the top of all your site's pages. 

You can also include a search form like the demo site by going to Genesis > Theme Settings, checking "Enable Extras on Right Side", and selecting "Search form".


<h2>Homepage</h2>

The homepage features three columns of "widget areas", which allow you to drop prebuilt widgets or text in any order and column you choose. Go to Appearance > Widgets to manage the three widget areas: Home Left, Home Middle, and Home Right.

On the demo site, the sidebars feature the following widgets:

<ul>
<li>Home Left: a <strong>text</strong> widget with general information about the event.</li>
<li>Home Middle: the <strong>Genesis - Featured Posts</strong> widget to display latest news, and <strong>Genesis - Latest Tweets</strong> widget for displaying recent twitter updates.</li>
<li>Home Right: the <strong>Speakers Widget</strong> for displaying all the event's speakers, and a <strong>text</strong> widget containing images of sponsor logos.
</ul>


<h2>Homepage Rotator</h2>

This theme has a built-in image rotator on the homepage, which displays above the three widget areas. It is only displayed if images have been added.

Go to <a href="/wp-admin/post-new.php?post_type=sc-rotator">Rotator > Add New</a> and click "Set Featured Image". Upload an image, then click "Use as Featured". The image will automatically be scaled down to 205px tall, so make sure the image is at least that tall. You can specify the image order by using the "Order" attribute in the right column (lower numbers come first).

<h2>Speakers</h2>

Go to Pages > Add New and create a page with any name you'd like (ex: Speakers). In the right column, under Page Attributes select "Speakers" as the page template. Publish the page. This creates the page that you can now add to your menu.

To populate the page with speakers, go to Speakers > Add New. Give the speaker a name in the title area and describe the speaker in the editor. Provide the speaker's website URL and twitter username in the Speaker Details box (both are optional fields). Upload their photo by clicking "Set Featured Image", uploading it, then clicking "Use as Featured". Specify the order in which they are displayed using the "Order" field in the right column. If you've created a session that this speaker will participate in, select it in the Connected Sessions area.

<h2>Schedule</h2>

Go to Pages > Add New and create a page with any name you'd like (ex: Schedule). In the right column, under Page Attributes select "Schedule" as the page template. Publish the page. this creates the page that you can now add to your menu.

To populate the page with sessions, go to Sessions > Add New. Give the session a title and describe the session in the editor. Under Session Details, select a date, time, and describe the location. The date and time are used for sorting the sessions. Connect a speaker to this session from the Connected Speakers section.

If you'd like to group your sessions (ex: Day 1, Day 2), go to Sessions > Session Groupings and create your groupings. Give each grouping a Name (ex: Day 1) and Description (ex: 23 Nov 2011). Then edit your sessions and add them to the appropriate groupings. 

<h2>FAQ</h2>

The FAQ in the demo is built using a standard page (no page template is needed). To link to individual answers:
<ul>
<li>Type the answer as a headline (ex: h3)</li>
<li>Switch to HTML view and give it a unique id ( ex: id="answer1")</li>
<li>Switch back to Visual view, type the answer at the top in the Questions section</li>
<li>Select the answer, click the Link button, and for the URL put #answer1 (where answer1 is the same as your unique id).</li>
</ul>

Here's an example: <a href="https://gist.github.com/1553021">https://gist.github.com/1553021</a> 

<h2>Registration</h2>

Go to Pages > Add New and create a page with any name you'd like (ex: Registration). In the right column, under Page Attributes select "Registration" as the page template. Publish the page.

Below the editor you'll now have a box that says "Registration Iframe". You can drop an iframe from your registration service (ex: EventBrite) and it will automatically be added to the end of the page's content.

<h2>Contact</h2>

The theme is designed to work with both Gravity Forms (paid plugin) and Contact Form 7 (free plugin). Install the plugin of your choice, build the form in the appropriate section, then create a page and drop the appropriate shortcode in it.

If using Contact Form 7, you might want to customize the HTML of the form itself. Here's the HTML from the demo site's form: <a href="https://gist.github.com/1553046">https://gist.github.com/1553046</a>

<h2>Content Columns</h2>

This theme has Content Columns built-in so that you can create multiple columns of content. For example, the <a href="#">Press</a> page in the theme demo has two columns of content, in addition to the sidebar on the right.

To create multiple columns, click the "HTML" tab on the editor and type the appropriate HTML code. Then switch back to the "Visual" tab and fill those content areas in.

For two columns, use this:

<pre>
&lt;div class="one-half first">This is the left column&lt;/div>
&lt;div class="one-half">This is the right column&lt;/div>
</pre>

For three columns, use this:

<pre>
&lt;div class="one-third first">This is the left column.&lt;/div>
&lt;div class="one-third">This is the middle column.&lt;/div>
&lt;div class="one-third">This is the right column.&lt;/div>
</pre>

For more information, see <a href="http://www.studiopress.com/tutorials/genesis/content-column-classes">How to use column classes</a>.

<h2>Recommended Plugins</h2>

<ul>
<li><a href="http://www.billerickson.net/go/gravity-forms">Gravity Forms</a> - The best contact form plugin available</li>
<li><a href="http://wordpress.org/extend/plugins/contact-form-7/">Contact Form 7</a> - The best <em>free</em> contact form plugin available</li>
<li><a href="http://wordpress.org/extend/plugins/genesis-simple-sidebars/">Genesis Simple Sidebars</a> - Create additional sidebars for specific pages</li>
<li><a href="http://wordpress.org/extend/plugins/genesis-title-toggle/">Genesis Title Toggle</a> - Disable page titles on certain pages</li>
</ul>

<h2>Advanced Customization</h2>

If you're a developer, there are hooks and filters in the Event Manager Theme Functionality plugin so that you can customize it to your needs.

<ul>
<li><code>apply_filters( 'sc_speaker_metabox_override', '__return_true' );</code> will remove the Speaker Details metabox (so that you can create your own)</li>
<li><code>apply_filters( 'sc_session_metabox_override', '__return_true' );</code> will remove the Session Details metabox</li>
<li><code>cmb_meta_boxes</code> filter can be used to create your own metaboxes. See the <a href="https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/wiki">metabox wiki</a> and <a href="https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/blob/master/example-functions.php">example file</a> for details</li>
</ul>

And of course the theme can be completely customized through the theme files. All future functionality will come through plugin updates, so you are free to tweak the theme.

</div>
