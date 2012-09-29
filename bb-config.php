<?php
/** 
 * The base configurations of bbPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys and bbPress Language. You can get the MySQL settings from your
 * web host.
 *
 * This file is used by the installer during installation.
 *
 * @package bbPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for bbPress */
define( 'BBDB_NAME', 'wordpress_6' );

/** MySQL database username */
define( 'BBDB_USER', 'wordpress_6' );

/** MySQL database password */
define( 'BBDB_PASSWORD', 'K!L9zcgD10' );

/** MySQL hostname */
define( 'BBDB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'BBDB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'BBDB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/bbpress/ WordPress.org secret-key service}
 *
 * @since 1.0
 */
define( 'BB_AUTH_KEY', 'l)IUKf5AcSXUH8jKXjVGigKIjsw%xlqH#E#6pg6dvLE^Rs6^mjG%77P$3h67LQ*H' );
define( 'BB_SECURE_AUTH_KEY', '%!c3UBZ^7(sGofkS!Fm19a^NBo!!IFE!@I$uvGQ)ZWYH5i@nRK!8lBelMPi3cFHi' );
define( 'BB_LOGGED_IN_KEY', 'LzTwjLNOi9KUrKjXXhOyooZphdAE7Hasv(DIFqX3fOLPeYIX9)ZgSLPVT5NxzDg8' );
define( 'BB_NONCE_KEY', 'm%k#qTxwpqFOkHyp1z0P7&C(s%xO4OvIbd$n%7zwuEIK!PjOD$(EJP8i#Mp^MEDL' );
/**#@-*/

/**
 * bbPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$bb_table_prefix = 'wp_bb_';

/**
 * bbPress Localized Language, defaults to English.
 *
 * Change this to localize bbPress. A corresponding MO file for the chosen
 * language must be installed to a directory called "my-languages" in the root
 * directory of bbPress. For example, install de.mo to "my-languages" and set
 * BB_LANG to 'de' to enable German language support.
 */
define( 'BB_LANG', 'en_US' );
$bb->custom_user_table = 'wp_users';
$bb->custom_user_meta_table = 'wp_usermeta';

$bb->uri = 'http://webento.com/wp-content/plugins/buddypress/bp-forums/bbpress/';
$bb->name = 'Webento Forums';
$bb->wordpress_mu_primary_blog_id = 1;

define('BB_AUTH_SALT', 'sXbizTqFxznUoL68F$z3Tk*zz7DqownThs&DeI0&lyIudJjbj(6v7pNW0DeuRYQl');
define('BB_LOGGED_IN_SALT', 'B3yhB0CpFxQY@wpBVo1a6fLo*hAtR3n!7Uik$M9DmZ2GPRyCTX6OXvXH0gV$LaDm');
define('BB_SECURE_AUTH_SALT', ')YTsXhotX&b1MypTg7IcWMIeGUR8Ds3kFH5KJISg)l)#4J68nSmFh0FKm24qkZmm');

define('WP_AUTH_COOKIE_VERSION', 2);

?>