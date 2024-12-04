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
define( 'DB_NAME', 'dermalaser' );

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
define( 'AUTH_KEY',         'WLNW3hlk7AYnqbtSehbkD17M7DPIlbNtBEGnhjVLPpQkzJBA6X0xS9XnLaTnioOX' );
define( 'SECURE_AUTH_KEY',  'S3zGy0JioZeM9meZsRpPj4N4wvtJCeq2w86uxghEKbLoVmx9oJA4mVD22tjE98DH' );
define( 'LOGGED_IN_KEY',    'KnaP0YjAeKXrMiNypZHisjdG6wn9q6ErB3g36a5HwJQlchxSJhSHy0xDkFsymQmC' );
define( 'NONCE_KEY',        'U1gje5zLCKx5w1F1dpvdPrRl1IsKn3jhDrBhLKXiyr6HNKF6BbBKA6560Q4EZLgR' );
define( 'AUTH_SALT',        'VM5fWTtm68YfxP8ZOcpPW7kbU1rv1GV2NnDVcvSxuizuoFeYIvZGNXNN0rkiTEgu' );
define( 'SECURE_AUTH_SALT', 'sxPSUiL53EWrmnBEdSi5aH8NZSJ4lObFyu5k49J8jlArpEDfKUTj0NaQBJuNwz6B' );
define( 'LOGGED_IN_SALT',   'mZgx3wiPipOeik9913ZcGSddeCAv0D9v5thVV9TSeL1WlwHF7PGmId08CBihGzxI' );
define( 'NONCE_SALT',       'OmQsko1nvYKK7dRRTwDZQMU3tPvn997o2c0NT4wPdCMJMayel30XGawN3gfZcAW0' );

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
