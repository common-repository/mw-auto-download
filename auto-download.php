<?php
/**
 * Plugin Name: MW Auto Download
 * Plugin URI: https://www.digibitz.nl/blog/wp-mw-autodownload.php
 * Description: Create automatic download links for files.
 * Version: 1.0.2
 * Requires at least: 4.6
 * Author: Michael Willems
 * Author URI: https://www.digibitz.nl/
 * Text Domain: mw-auto-download
 */

// Defines
defined( 'ABSPATH' ) || exit;
define( 'MWADWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Require files
require_once MWADWP_PLUGIN_DIR.'includes/mwadwp-admin.php';
require_once MWADWP_PLUGIN_DIR.'includes/mwadwp-functions.php';

// Create plugin button
function mwadwp_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=mwadwp-auto-download">'.__( 'Settings' , 'mw-auto-download' ).'</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$mw_plugin = plugin_basename(__FILE__);

// Set hook
add_action( 'admin_menu', 'mwadwp_add_settings' );
add_action( 'admin_init', 'mwadwp_register_settings' );
add_action( 'wp_footer', 'mwadwp_create_download_file' );
add_filter("plugin_action_links_".esc_attr($mw_plugin), 'mwadwp_plugin_settings_link' );