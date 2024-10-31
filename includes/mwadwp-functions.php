<?php
// Create download function
function mwadwp_create_download_file() {
    global $options;
    global $post;
    $options = get_option( 'mwadwp_auto_download' );
    if ( !is_page( esc_attr( $options['mw_exclude'] ) ) ) { ?>
        <script type="text/javascript">
            window.onload=function(){
                <?php if(esc_attr( $options['mw_debug'] ) == 1):?>console.log( '%cMW Auto Download'+'%c | Starting up download(s)','font-weight: bold;','font-weight: normal;' );<?php endif;?>
                var x = document.getElementsByClassName('<?php echo esc_attr( $options['mw_classname'] );?>');
                var a = document.createElement('a');
                var h = "<?php esc_attr_e( $options[$post->ID]['hide'] );?>";
                if ( typeof a.download != "undefined" ) {
                    <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | HTML5 support active','font-weight: bold;','font-weight: normal;' );<?php endif;?>
                    setTimeout(function () {
                        var i;
                        for ( i = 0; i < x.length; i++ ) {
                            if (x[i] != null) {
                                x[i].setAttribute('download', '');
                                if(h === "1"){
                                    x[i].style.cssText = "visibility: hidden; width: 0px; height: 0px; display: inline-block;";
                                }
                                x[i].click();
                                <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | Dowload created for: '+x[i].href,'font-weight: bold;','font-weight: normal;' );<?php endif;?>
                            }
                        }
                        <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | Auto download successful','font-weight: bold;','font-weight: normal;' );<?php endif;?>
                    },<?php esc_attr_e( $options[$post->ID]['time'] );?>);
                } else {
                    <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | No HTML5 support','font-weight: bold;','font-weight: normal;' );<?php endif;?>
                    setTimeout(function () {
                        var i;
                        for ( i = 0; i < x.length; i++ ) {
                            if (x[i] != null) {
                                var tmp_url = x[i].href;
                                if(h === "1"){
                                    x[i].style.cssText = "visibility: hidden; width: 0px; height: 0px; display: inline-block;";
                                }
                                window.open(tmp_url);
                                <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | Dowload created for: '+tmp_url,'font-weight: bold;','font-weight: normal;' );<?php endif;?>
                            }
                        }
                        <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | Auto download successful','font-weight: bold;','font-weight: normal;' );<?php endif;?>
                    },<?php esc_attr_e( $options[$post->ID]['time'] );?>);
                }
            }</script>
    <?php }
}

// Create button
$options = get_option( 'mwadwp_auto_download' );
if( esc_attr( $options['mw_button'] ) == 1 ) {
    add_action( 'admin_print_footer_scripts', function() {
        $options = get_option( 'mwadwp_auto_download' ); ?>
        <script type="text/javascript">
            /* <![CDATA[ */
            ( function($) {
                $( function() {
                    $(document).on("wplink-open", function( inputs_wrap ) {
                        if( $('.link-autodownload').length === 0 ) {
                            <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | Created auto download checkbox','font-weight: bold;','font-weight: normal;' );<?php endif;?>
                            if( $(mw_adwp_extend_wplink_getLink() ).hasClass( "<?php esc_attr_e( $options['mw_classname'] );?>" ) === false ){
                                var link_data = {"type": "checkbox", "id": "wp-link-download"};
                            } else {
                                var link_data = {"type": "checkbox", "id": "wp-link-download", "checked": "checked"};
                            }
                            $("#link-options").append(
                                $("<div></div>").addClass("link-autodownload").html(
                                    $("<label></label>").html([
                                        $("<span></span>"),
                                        $("<input></input>").attr(link_data),
                                        "<?php _e( 'Auto download', 'mw-auto-download' );?>"
                                    ])
                                )
                            );
                            if ( wpLink && typeof ( wpLink.getAttrs ) == "function" ) {
                                wpLink.getAttrs = function () {
                                    wpLink.correctURL();
                                    return {
                                        href: $.trim($("#wp-link-url").val()),
                                        target: $("#wp-link-target").prop("checked") ? "_blank" : null,
                                        class: $("#wp-link-download").prop("checked") ? "<?php esc_attr_e( $options['mw_classname'] );?>" : null
                                    };
                                    <?php if( esc_attr( $options['mw_debug'] ) == 1 ):?>console.log( '%cMW Auto Download'+'%c | Auto download setting changed','font-weight: bold;','font-weight: normal;' );<?php endif;?>
                                };
                            }
                        }
                    });

                    function mw_adwp_extend_wplink_getLink() {
                        var _ed = window.tinymce.get( window.wpActiveEditor );
                        if ( _ed && ! _ed.isHidden() ) {
                            return _ed.dom.getParent( _ed.selection.getNode(), 'a[href]' );
                        }
                        return null;
                    }
                });
            })( jQuery );
            /* ]]> */
        </script>
        <?php
    }, 45 );
}
