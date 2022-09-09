<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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
define( 'DB_NAME', 'techstor_home' );

/** MySQL database username */
define( 'DB_USER', 'techstor_home' );

/** MySQL database password */
define( 'DB_PASSWORD', '5Rp[5Dp4S-' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'f8ps5gjojmgpysdfs0vvyrk1gkjjki3vihozvf5rvvwuekl3w65pzb3cgtryndkj' );
define( 'SECURE_AUTH_KEY',  'xs4lhdivtiquca3vnjkammygpmp38in9fpnafuo8ewesqywd4vremfzqutdscwbu' );
define( 'LOGGED_IN_KEY',    'xqk68z8ftxpyfhmk0joleb7skhembnaeoyd5sit98gbankwlzicddpp5uutgzuxm' );
define( 'NONCE_KEY',        'rllo0ynsdthjddvp8epms06phipfs9maimly4yjuwbojkj2a7vb2eyarwhagl3jm' );
define( 'AUTH_SALT',        '3mjqr3mziyfoqxj7txdwge4ediu4faskmu9yzgnik4unzaubwcyiq0ttoajtvbak' );
define( 'SECURE_AUTH_SALT', 'wu2q7nabhwsa6shmrpiggwvpid2jnf4f0z5mupvd8ozimklpacwy7ff8mv0lrkm5' );
define( 'LOGGED_IN_SALT',   'ehs9tlauyh9vyzszdhgm8jmdvp5vu9vy4izwanyvdwiffj4ka1ivdqzwm6kd0ar3' );
define( 'NONCE_SALT',       'frxr7f6bhkpwn6e8trdjufqoadiv8ua21fxk00fofm10czrdzzypmp4dspxsjadu' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wphe_';

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
