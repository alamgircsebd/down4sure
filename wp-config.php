<?php
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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          '!PRObD-TMbTS7R^NZEsnh@o%mOV:2$=-sR_zWEF-9VxDFlSTT}#n+X7UXZB8(*na' );
define( 'SECURE_AUTH_KEY',   '{184%?)T4Qd(CB(_p76iwO#E-0N`SU@M4QlQ-F{TiX3km@,Bf^4g?JE#j<jGY)=5' );
define( 'LOGGED_IN_KEY',     '%|ZN:*wa8-2_L|!B9U}/^Yav1?aso`Rj/H!KLAD[P8ppvfR<A3 %#ipR%zB<pw~)' );
define( 'NONCE_KEY',         '`8cJ (%^W0>8%gV1s ;cfLlD|SHgNHon5L#i/YsbOyzZL)=K,4A2CH~9#wDe}|y{' );
define( 'AUTH_SALT',         ')1~$;Z_q]Fz|uf$P1R~85& YQmcNpDYi_]mv[k*+kJo=U71uL}Qh*~;:7FNeazE5' );
define( 'SECURE_AUTH_SALT',  'Sb=u<mOFN0),PEhC^7-D-D[<nHOhWV3ps0Gu!.S~euheMxRq5I8<,4(|w  |2Mc]' );
define( 'LOGGED_IN_SALT',    '`fPX?fL;Q{eWeEjf/~1nwiag@zC+DJ}3-5S;7XBKh> %AiQ*YxP[^BYT0N|f4 ,&' );
define( 'NONCE_SALT',        'r~(O[1qIV~W:O52[lo4~6RQ5h/e};/PItax3&C.DN9@{2CJd^Q/n!h/,-$Pjw,ng' );
define( 'WP_CACHE_KEY_SALT', '+?8/yLZ$<By;i5picDS#oC-a#R| Z<0WRm:UIqu,0EXG$o_3{Sf#dIhuZg~Ycw 8' );


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

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
