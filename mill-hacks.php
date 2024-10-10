<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://millkeyweb.com
 * @since             1.0.0
 * @package           Mill_Hacks
 *
 * @wordpress-plugin
 * Plugin Name:       Mill Hacks
 * Plugin URI:        http://millkeyweb.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Tadashi Matsuura
 * Author URI:        http://millkeyweb.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mill-hacks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'inc/debug.php';
}

// require_once plugin_dir_path( __FILE__ ) . 'modules/ad/ad.php';
// require_once plugin_dir_path( __FILE__ ) . 'modules/socials/socials.php';
// require_once plugin_dir_path( __FILE__ ) . 'modules/embed.php';
// require_once plugin_dir_path( __FILE__ ) . 'modules/google-analytics.php';
// require_once plugin_dir_path( __FILE__ ) . 'modules/shortcode.php';
// require_once plugin_dir_path( __FILE__ ) . 'modules/author-box/author-box.php.php';
// require_once plugin_dir_path( __FILE__ ) . 'modules/breadcrumbs/breadcrumbs.php';
// require_once plugin_dir_path( __FILE__ ) . 'modules/related-posts/related-posts.php';
