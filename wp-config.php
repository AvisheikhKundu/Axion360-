<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u591737630_whCin' );

/** Database username */
define( 'DB_USER', 'u591737630_WypXj' );

/** Database password */
define( 'DB_PASSWORD', 'rSRXr2LEGd' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '%F9VkE1~I,G@RDaW]ls tm2&/j`20Hi#>z;hj7jMaTpLh;(3%Mx+p*=-<RZ!EcyA' );
define( 'SECURE_AUTH_KEY',   'GIU 2(Sb(b/KW%l-UC0(40[]9pV&hHr),1Z&J?`h~sVwH?$s5wnftdKenF}q&CNf' );
define( 'LOGGED_IN_KEY',     'Qi0Y+_?C((6TU_p6.w^:flISXEB/2V_}p0M]9od$z-cdj|=28h,0eHAqbG*oEt.7' );
define( 'NONCE_KEY',         '@H#th0O&`;rt1.At*znMM}=?~KYzpID{:(iVH7JX7uu89/kJUZc(80UBcciw5&o|' );
define( 'AUTH_SALT',         'KN*xo<OU5Z6l#Hy]aWN|HZhD*aO9l(A_IC^@jM.&h_|XX=*4WZ^Qm3tIS1A}rJg?' );
define( 'SECURE_AUTH_SALT',  '@pL8@TkQUILh+zy1i=jD;dz1~R:/Xu]K0lETW|LE7dU_sz@c;HVij,k4{O7wx>L;' );
define( 'LOGGED_IN_SALT',    'b*L:a/uapZ$bEmt1eO_%/emFPd9i,:]a5/m5&FCXd)qh0hecKXlVb<W<[N$Lz|qp' );
define( 'NONCE_SALT',        'H^Kj:kN5d_qNJ$TXclzG~,8#nMYsYl0HzsBTZr&1OS/R4};&k]R+)yxX&I5_t,X[' );
define( 'WP_CACHE_KEY_SALT', 's(evJPAYau7.?bbv`,f1juO!}=kQTzc[MHTUI4tKC<.$KPx(6`d;H]+Xfb<:`Wt<' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'e3be669fcac57faca386baa87014566f' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
