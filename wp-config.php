

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'h_msbsdtbs' );

/** MySQL database username */
define( 'DB_USER', 'h_msbsusr1' );

/** MySQL database password */
define( 'DB_PASSWORD', '*g?SWnTd3^6wkz4LU5qs' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ZIWWDMMpAbXEG1fTUA5LudXyq3ePADmnMXHQYF1wGEXecLbbmKRbMWQOHnIci9tC' );
define( 'SECURE_AUTH_KEY',  'VLGiML6Uhke6bOy0dDAkWNmyXEWKAwrnXCyGRRkA6Xwu6T6kRgiAMtNaFHzk8Xt8' );
define( 'LOGGED_IN_KEY',    'L0R8CgXLSREkyKlOZ91VRLWjQOsVj5U69W017soLJ92jinCvBUpHFm78dOqa6j2o' );
define( 'NONCE_KEY',        'IpkuO5acp2d2EzyVZgkP4cISJU6XlVqUaK5UATqisahHwoG180RUmidOBuArMGtj' );
define( 'AUTH_SALT',        'Z454vKMcTKQOKA3ekuB67h3MY7uQVMjnLt5ZkPJLmE5lA1Am1ejhx5gK0aAbgywH' );
define( 'SECURE_AUTH_SALT', '4NyCSuwgASAi05VdFPB7JkzUnArBkeOn3JCr15kE7KSr4khdk2IUZ7qwg9BQ179u' );
define( 'LOGGED_IN_SALT',   'FUgcGatowPOqMuzuHuQWj0R5rbugnQZdwbjF9aeQNRoxGWHowU2TF4XkX5Wq9wlu' );
define( 'NONCE_SALT',       'j53RVjaElrJqL9keRCssNG6D2neDTCfBihhshq3CO47k5c02Fm1aXMjQiLcWOcox' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
