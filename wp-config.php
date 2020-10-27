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
define( 'DB_NAME', 'assignment' );

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
define( 'AUTH_KEY',         't{GB%>eR/9v49Gdtu;}0,t?9dahB]V3C8:-!nZIs3dL_M~OGD9L^@9=p<$PY(bZY' );
define( 'SECURE_AUTH_KEY',  'H(V5K3}:W/ &GgCT{m;8ThDV$KPQEHZ4AnG}qDOr[rz7cLSVaP=u$-u>84UxlVYy' );
define( 'LOGGED_IN_KEY',    'HA7:do%UMaAjRqm6q+jGdl(gWRm-:#EqD+S><GmaTE0E.|:QR;e/iix+F,WY^K0U' );
define( 'NONCE_KEY',        '6MU|mdfTmYF1Z)@`fcE27Tk&~O_* iA#^ydFo0Z!*:uj[p?$s&U9)7qP=kkBLt`b' );
define( 'AUTH_SALT',        '$F;8r={vhT26E}K?1L;k#`Mz0]$hwz*U3,wF{hRv^Z1|iH>^CO*e;IHr:,(3D996' );
define( 'SECURE_AUTH_SALT', 'eI}$X25ke?sEBFG5<KF +K4h;PS*FPl!y(~)XW=X8O1eM3pn3yWbO,`oC$x[=hkL' );
define( 'LOGGED_IN_SALT',   'Wl2{Zh8ZQ(yzN:$PA3H.&SRP$~/$)PZzulR=Ce7GXP/=hr`cl$n*Z@Ed~qc WiAI' );
define( 'NONCE_SALT',       'pp-us603)Q]?T#q|:SW1?ca`g~/P2+F8_W!Em+Th3<[RgEqr6css^lg|?:<_e~c0' );

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
