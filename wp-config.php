<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'hzh0314007');

/** MySQL hostname */
define('DB_HOST', 'mysql');

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
//define('AUTH_KEY',         '9c2f36fea1bb655502b3595595cbee0081dc194b');
//define('SECURE_AUTH_KEY',  '1f446a8a63e302e3116a2a81db0fc4a500c88c79');
//define('LOGGED_IN_KEY',    '4c84f4cc18136a798bc02b5b0faabf0ac1f935c5');
//define('NONCE_KEY',        '0c570e18f3da5aea6042b3bea932796d6d3575b3');
//define('AUTH_SALT',        'b608d3ddce0dec7a1b624acd9b8c3ef5eaf07aa0');
//define('SECURE_AUTH_SALT', 'f1421c1400c87389c35787b204e10adde4f57b94');
//define('LOGGED_IN_SALT',   'ed63350c264d250c29dbbf7e80c2dd440051698d');
//define('NONCE_SALT',       '78f4c03da1764a37a42d37ffaedf1929a4dcd8df');



define('AUTH_KEY',         '6d50a4e69a0258624916afe5c0f07867f933121f');         
define('SECURE_AUTH_KEY',  'ad189e018796d5b8e44c3fd7e3ee9b96238ac9e7');         
define('LOGGED_IN_KEY',    'da260470517e38ea5194b6be92ffc6281d3caef3');         
define('NONCE_KEY',        '8ce30c5c84584be6137edd75768f9b90183e3f1f');         
define('AUTH_SALT',        '8b30969f91bc48e8694168db6625f4cb1cf57128');         
define('SECURE_AUTH_SALT', '3a44265d0ba6da6686f8653342ea2212a16275b6');         
define('LOGGED_IN_SALT',   '204a412a6e08750d19ce6941ecf5cbbce3b327de');         
define('NONCE_SALT',       '665146d19cc541bb48e0c98f89ae6c339bb64605'); 











/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

// If we're behind a proxy server and using HTTPS, we need to alert Wordpress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
