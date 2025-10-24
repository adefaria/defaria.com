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
define( 'DB_NAME', 'rockready' );

/** Database username */
define( 'DB_USER', 'rockready' );

/** Database password */
define( 'DB_PASSWORD', 'ZEaoJtCG%K2fpi@M' );

/** Database hostname */
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
define( 'AUTH_KEY',         'k>B!++Vr+qsDG.e&=C%B& iG~?~JX2lQXvzktDU9UG=tu-L=/ci}i3g)5YQsNn>5' );
define( 'SECURE_AUTH_KEY',  '%SBLn]UU~)IkRqc6W[e1s&C]p{>^RIrNomG5nJ0#1vo#rH^`,1QTD7hx<-RoHY{4' );
define( 'LOGGED_IN_KEY',    'H<|<F:./x1fYwvaT@AorMN]_+=e0f 8c-n!{`E-;}i`_s^<(2{iKathsgY^@;G$]' );
define( 'NONCE_KEY',        'i5mHcGyBjO-C^Lm:nN,ES8A4$U8g>gUs^U+VlEqO&;}^owBto{s^u#l+uXzaG!80' );
define( 'AUTH_SALT',        'XbZdGN</0&91wX@mX^H@@9&%4.Ni~NRdm?#a(lB#({.<a~lv:D(e?{9C;Q!A/ON6' );
define( 'SECURE_AUTH_SALT', '5DSoDv)s9oau?j`M:)-d~cep7IF9rg=sIu!LG}LXB=w@N$^{Hb2dV},q5#wgC:}{' );
define( 'LOGGED_IN_SALT',   'nB.<|M*d]]d^R%63pr/b{~*4=q()D>>lBSL{6Lo5vNBde$1pNqJUpLT?r@A]+!{}' );
define( 'NONCE_SALT',       'uD`5E%CtMvdL#4&.J;h$&lonaun4 @LJj9<WK2:{[#y/GAvGeP[2f}@f#CaJh{wr' );

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
$table_prefix = 'rr_';

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
define('FS_METHOD', 'direct');
define('WP_MEMORY_LIMIT', '512M');

/* That's all, stop editing! Happy publishing. */
// START Custom SMTP Configuration
define('SMTP_HOST', 'defaria.com'); // Use your actual mail server host name
define('SMTP_PORT', '465');         // Or 465 (for SSL)
define('SMTP_USER', 'andrew');      // Your full email address
define('SMTP_PASS', 'teafor1063');  // Your email password
define('SMTP_AUTH', true);          // Required for authentication
define('SMTP_SECURE', 'ssl');       // Use 'ssl' or 'tls'
define('WPMS_ON', true);            // Tells WordPress to use the SMTP configuration
define('SMTP_FROM', 'Andrew@DeFaria.com');
define('SMTP_NAME', 'Rock Ready Band');
// END Custom SMTP Configuration

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
