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
define('DB_NAME', 'santa_ljccdev');

/** MySQL database username */
define('DB_USER', 'santa_ljccdev');

/** MySQL database password */
define('DB_PASSWORD', 'jet8400');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '()~0_5&8|A:x*`fN,?W[@_7--L*4[;?hr%{g30G73Eb(~|=J<~gCO4^bt=d//JgD');
define('SECURE_AUTH_KEY',  '[BF)m/+_rO*O95[!#VnRtnpBo|;`OvSph*$y7&-ZQ*ewVBHW3/C[yHRiyWiPFJb[');
define('LOGGED_IN_KEY',    'CC.u2-o2a5;yc5icIsu#));L]TmvG+z_pg3E>Lg%*NBb.7if3o9e/@Qn`&EjaZ^9');
define('NONCE_KEY',        'b}7j(`m DUJV[ezu],o+)WLce&@)9;}s-.}#Ol7Q5kpzuCp]aj#j?`1d2S+NbxI9');
define('AUTH_SALT',        'l],DyAXTs7kwNUb!(EHF#!vb]}dWCEk(+O0hKD6Fyc6;|~~*+<rlqW3uUcQ0@.{S');
define('SECURE_AUTH_SALT', '<AwW:jf<6`#a1?y.5J&BJ0`76bpag8))eYe`tVOY4r_|ne-os`]{q3l53igoZ4*j');
define('LOGGED_IN_SALT',   '/d*2TtF3m=hMm0K.wyK(bGa}@3rAvX1v]bT4oU^`:ere$bJ2+H}6H@UWhVOdp[`:');
define('NONCE_SALT',       'S3#V j2m+pjsTB<b0L!wEx6[ON(0=*q-x/hUx%2Q{,mW !H|Q}$SO[ZVCBS~]7iP');

/**#@-*/

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
