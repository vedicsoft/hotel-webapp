<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'hotel');

/** MySQL database password */
define('DB_PASSWORD', 'h0tel');

/** MySQL hostname */
define('DB_HOST', '172.16.200.17');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'rr`!;gY|y!|i9*WX5*gx+|6=?tH%_+!^tU<B/x5Mqfkj)8sm@.kgg%C)&2%/2Z4-');
define('SECURE_AUTH_KEY',  '+XVAJ[g([%&%dOE2&r?_bJ,~M;!&c.@gC{YAPD]}9On1bGX]X lugu%)C59/RT,o');
define('LOGGED_IN_KEY',    'pC1CGR?[AwNV!0>8&s~ @-iM;0zsHog=L)>H*5Ns;|@^At@y2^&2}r/6G-}6f!Wq');
define('NONCE_KEY',        '}I##s5,<&G&(B>P6Pa,>?_t{x(EB.2eA^3ogX]&Ittc)o:*I%(.rM+L>FtNFRh+}');
define('AUTH_SALT',        'aBnER`cL~gfYHN*}gwua9:5xYx?Bn30Ln+],`6NQPok8FL:R*xutPGYd/[.&aoDH');
define('SECURE_AUTH_SALT', 'YfDy]J  oG[t|AEFF%J]3kv4:/Xwz&JsBkh{Vh:|v{ f_w`vSuoc>8|81biUr{/P');
define('LOGGED_IN_SALT',   '<L6Ed97wP5;nSB<~2&g<Kn{zQcJvP/r93L!DNJ5tqhZP<6`|.]%s_ZCwj6xyc,N]');
define('NONCE_SALT',       ':`6Sr YS.z%BX{YoGw7(nWw @o$x-3Vaml5B8D##i@R.f[j&/ O%WGWO.)tX]uG/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
