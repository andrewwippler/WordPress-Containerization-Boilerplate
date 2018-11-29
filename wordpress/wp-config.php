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
define('DB_NAME', getenv('WORDPRESS_DB_NAME'));

/** MySQL database username */
define('DB_USER', getenv('WORDPRESS_DB_USER'));

/** MySQL database password */
define('DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD'));

/** MySQL hostname */
define('DB_HOST', getenv('WORDPRESS_DB_HOST'));

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
define('AUTH_KEY',         'NSTDV[`ki10%P3UkdkiQo./R+3-K-*x@u-Tx8[+l`yz,_+_0L+Wxg%[Rq(^A4W9!');
define('SECURE_AUTH_KEY',  'KV}6Gnj VX+kXy<XPT$~[=_Y$i-Yw?O^-#@FA_I4CRP.^{Oq{ s/?95U@h4|(<)M');
define('LOGGED_IN_KEY',    '-c]Mt$ARrzc(Ok]c]|ZZv(h@ ~#_tb uQ@RO|RxY@-X/aDn5]>mSw@U~F0QVR4r3');
define('NONCE_KEY',        'E53{3,P!$i^kiUO8/F75uL>:`F5linom/~+8[CL6,n+YVjRU:D/rB](MCa;%~[9Q');
define('AUTH_SALT',        '3Qvm+Jr-*%XYP(1~0-.*`DBUW}+K|p_C&~730Z[FJ#sMrG{~-e;}M6=XH<%$~(+P');
define('SECURE_AUTH_SALT', '%b[C/KdL~X;]2zutut,uLTuVi,jT}i.U~=)7wKd3=:FFc{gF )#A2:IkZ?0n-+hC');
define('LOGGED_IN_SALT',   'X(jJF~NcMOW!a5=Y%ic,kT!-a]T+m^kq*c2_]#5{@%$5X>fL^Aa9G3ZY_jAxy,T|');
define('NONCE_SALT',       '5vI/`^p%Na#-A~ykN0+MyezuG@gK }[zm8bmlVlbO0E??yF:/dEc+++E#R!4u26s');

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

// If we're behind a proxy server and using HTTPS, we need to alert Wordpress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
