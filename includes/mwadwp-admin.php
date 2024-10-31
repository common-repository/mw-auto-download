<?php
// Create settings
function mwadwp_add_settings() {
    add_options_page('Automatic download', 'Auto download', 'manage_options', 'mwadwp-auto-download', 'mwadwp_render_plugin_settings_page');
    add_option('mwadwp_auto_download', array( "mw_classname" => "mw_auto_download", "mw_button" => 1, "mw_exclude" => "" ), '', 'no');
}

// Render setting page
function mwadwp_render_plugin_settings_page() {
    if( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permission to access this page.' );
    } ?>
    <h2><?php _e( 'Automatic download', 'mw-auto-download' );?></h2>
    <form action="options.php" method="post" name="mwadwp_auto_download_form">
        <?php
        settings_fields( 'mwadwp_auto_download' );
        do_settings_sections( 'mwadwp_auto_download_page' ); ?>
        <h2><?php _e( 'Page settings', 'mw-auto-download' );?></h2>
        <p>
            <?php
            $options = get_option( 'mwadwp_auto_download' );
            $get_pages = get_pages( 'hide_empty=0' );
            foreach ( $get_pages as $page ) {
                if( !in_array( esc_attr( $page->ID ),$options['mw_exclude'] ) ):?>
                    <div class="mwadwp__accordion"><?php esc_attr_e( $page->post_title );?> <span class="mwadwp__pageId">[ id: <?php esc_attr_e( $page->ID );?> ]</span></div>
                    <div class="mwadwp__panel">
                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row">
                                    <?php _e( "Hide links <p class='mwadwp__tooltip'>Hide the automatic download links on page</p>", 'mw-auto-download' );?>
                                </th>
                                <td>
                                    <input id='mwadwp_plugin_hide_<?php esc_attr_e( $page->ID );?>' name='mwadwp_auto_download[<?php esc_attr_e( $page->ID );?>][hide]' type='checkbox' value='1' <?php echo checked( esc_attr( $options[$page->ID]['hide'] ), 1);?>/>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php _e( "Time before download starts <p class='mwadwp__tooltip'>1000 stands for 1 second.</p>", 'mw-auto-download' );?>
                                </th>
                                <td>
                                    <input id='mwadwp_plugin_time_<?php esc_attr_e( $page->ID );?>' name='mwadwp_auto_download[<?php esc_attr_e( $page->ID );?>][time]' placeholder='0' type='number' value='<?php esc_attr_e( $options[$page->ID]['time'] );?>' />
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endif;
            }
            ?>
        </p>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
    require_once MWADWP_PLUGIN_DIR.'includes/mwadwp-script.php';
}

// Set styles
function mwadwp_enqueue_custom_admin_style( $hook ) {
    if ( $hook === 'settings_page_mwadwp-auto-download' ) {
        wp_register_style('custom_wp_admin_css', plugin_dir_url( __FILE__ ) . '../assets/mwadwp-admin.css', false, '1.0.0');
        wp_enqueue_style('custom_wp_admin_css');
    }
}

add_action( 'admin_enqueue_scripts', 'mwadwp_enqueue_custom_admin_style' );

// Register setting
function mwadwp_register_settings() {
    register_setting('mwadwp_auto_download', 'mwadwp_auto_download' );
    add_settings_section('mwadwp_auto_download_settings', '', 'mwadwp_plugin_section_text', 'mwadwp_auto_download_page' );

    add_settings_field( 'mwadwp_plugin_classname', __("Classname <p class='mwadwp__tooltip'>The main class name that will be used to initiate the auto download</p>","mw-auto-download"), 'mwadwp_plugin_classname', 'mwadwp_auto_download_page', 'mwadwp_auto_download_settings' );
    add_settings_field( 'mwadwp_plugin_button', __("Option in editor <p class='mwadwp__tooltip'>Show checkbox in the editor when adding a url</p>","mw-auto-download"), 'mwadwp_plugin_button', 'mwadwp_auto_download_page', 'mwadwp_auto_download_settings' );
    add_settings_field( 'mwadwp_plugin_exclude', __("Exclude pages <p class='mwadwp__tooltip'>Hold <strong>CTRL</strong> to select multiple pages</p>","mw-auto-download"), 'mwadwp_plugin_exclude', 'mwadwp_auto_download_page', 'mwadwp_auto_download_settings' );
    add_settings_field( 'mwadwp_plugin_debug', __("Debug mode <p class='mwadwp__tooltip'>Toggle on to show console logs when using the plugin.</p>","mw-auto-download"), 'mwadwp_plugin_debug', 'mwadwp_auto_download_page', 'mwadwp_auto_download_settings' );
}

// Render options main text
function mwadwp_plugin_section_text() {
    _e("<p>Here you find the settings for the automatic download plugin. You can change the pages to add, time before the download starts and more.</p>","mw-auto-download");
}

// render option (Classname)
function mwadwp_plugin_classname() {
    $options = get_option( 'mwadwp_auto_download' );
    echo "<input id='mwadwp_plugin_classname' name='mwadwp_auto_download[mw_classname]' placeholder='mwadwp_auto_download' type='text' value='" . esc_attr( $options['mw_classname'] ) . "' />";
}

// render option (Exclude)
function mwadwp_plugin_exclude() {
    $options = get_option( 'mwadwp_auto_download' );
    $get_pages = get_pages( 'hide_empty=0' );
    echo "<select id='mwadwp_plugin_exclude' name='mwadwp_auto_download[mw_exclude][]' multiple='multiple'>";
    foreach ( $get_pages as $page ) {
        echo "<option value='".esc_attr( $page->ID )."'"; if(in_array( esc_attr( $page->ID ),$options['mw_exclude'])){ echo "selected"; } echo ">".esc_attr( $page->post_title )."</option>";
    }
    echo "</select>";
}

// render option (Button)
function mwadwp_plugin_button() {
    $options = get_option( 'mwadwp_auto_download' );
    echo "<input id='mwadwp_plugin_button' name='mwadwp_auto_download[mw_button]' type='checkbox' value='1' "; echo checked(esc_attr( $options['mw_button'] ), 1); echo "/>";
}

// render option (Debug)
function mwadwp_plugin_debug() {
    $options = get_option( 'mwadwp_auto_download' );
    echo "<input id='mwadwp_plugin_debug' name='mwadwp_auto_download[mw_debug]' type='checkbox' value='1' "; echo checked(esc_attr( $options['mw_debug'] ), 1); echo "/>";
}

// The [mw-download] shortcode.
// Attributes:
// Url = Url for file or location to go to.
function mwadwp_mw_download( $atts = [], $content = null ) {

    // normalize attribute keys, lowercase
    $atts = array_change_key_case( ( array ) $atts, CASE_LOWER );
    $uni_id = mwfaqwp_get_random_string( 5 );

    $html = "<a class='mw_auto_download' id='mw_auto_download_url_".esc_attr( $uni_id )."' href='".esc_attr( $atts['url'] )."'>".__($content,'mw-auto-download')."</a>";

    // Output needs to be return
    return $html;
}

// register shortcodes
function mwadwp_shortcodes_init() {
    // register shortcode "mw-download" for displaying download url
    add_shortcode( 'mw-download', 'mwadwp_mw_download' );
}

// Init shortcodes
add_action( 'init', 'mwadwp_shortcodes_init' );