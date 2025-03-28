<?php


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'threeboy' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '7ncf7Dllr7PQvJcy1axsIdHVgsNS1vjsFaGGzIeaKTIXRd8Yc65JTqof4SriL4cn' );
define( 'SECURE_AUTH_KEY',  'cSn9dRSUvrBuAcZzR6z2OHJnYjdJsj0lZSIjW1QsiDr4359474D6Xm2kwAsSTrwm' );
define( 'LOGGED_IN_KEY',    'p015aYOA6oms7xf85D8ePHiR2lModsiVL14ue15nMozaxvCMKe30tamu7c9kOA3L' );
define( 'NONCE_KEY',        'CqDZ86KfFl9pCSuFiaFrPIQl4nl3ZBrvw8oGwZhhg9Hh4W7vopsd1XHDHONNIJxa' );
define( 'AUTH_SALT',        'Gyxz5YtRZ2Woi8z7gFrO7msygJ1MuJfseOHhPhxAJ6ewtycDmlCTYWB25gmicg8q' );
define( 'SECURE_AUTH_SALT', 'Nl9hP9STp1xzuN8M9ZkUFLftOleHTgpRKuqUWsb9dLNVJG4ZcZ6xMES8VAao4esW' );
define( 'LOGGED_IN_SALT',   'YMq9nBXmVjUWfU9HU8kKxUd7Gf2LccSI78yzPvcVH0psPmFBUjQ8o6xUgLd4hFAI' );
define( 'NONCE_SALT',       'n37uIBXhPc3tnyM1Q7cbq0iII7gSGy3Dl08Ru9Ayc3F1Qoo4bA93o41ckYBgj0pS' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
