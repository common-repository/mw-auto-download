<?php
/**
 * Uninstall script.
 */

defined( 'ABSPATH' ) || exit;

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'mwadwp_auto_download' );
