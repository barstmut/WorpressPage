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
define( 'DB_NAME', 'WordpressPage_DB' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'blC#Z!/>`q?)aTDR*FSJ|B%HUns?$_c)<@j/NC5]{G dsl9x483r_83^/{.yx*=5' );
define( 'SECURE_AUTH_KEY',  'V291kk(&x}/PHgeRfYt_PK4`T7Uxq)E8W|m<[OKg}5{GT[FQ:zx/bU_K3B=P~*z@' );
define( 'LOGGED_IN_KEY',    '%e(WL7dh-6I(0lY#LNrAzRO)hJ`Okc2%.[dJoK`Q(#ac.N)bi^/ymgchSk#a h|J' );
define( 'NONCE_KEY',        '/gjJ3i#S}kq<4W,K]_W<#eGZxW)/D}]q$l+%:T>UW>`?j AZT)dJYz%))*oh6(qf' );
define( 'AUTH_SALT',        '-a,><o{n=,ea4)kk~-|v;90/J?sxh]4`Z.,],;htG)OMn*acf3AwPgYQ,Lk.%CKD' );
define( 'SECURE_AUTH_SALT', '~Ys`TNnfC7s6L])bN cD0Pku4?Z6#dvAPHJ*,-tyoU-<A(X&$$96Lo/S}Ve+8[f&' );
define( 'LOGGED_IN_SALT',   'f8?W?(d!ZyKC;<-Qks=Y8axxm_1JsnjV-uW#t|m]5<poazS?|@=T-/Y?Sg(XrT_D' );
define( 'NONCE_SALT',       '+H3#N837=I^zX*Euh)ONv!S{gF?^3FrpD4@H-[_<t/KpDK05!z{Xq1[|P~zv>_dO' );

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
