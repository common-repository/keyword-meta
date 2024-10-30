<?php
/** 
 * Plugin Name:     Keyword Meta
 * Plugin URI:      https://wordpress.org/plugins/keyword-meta/
 * Description:     Add Description, OpenGraph-Meta and Google verification to your page on the fly. 
 * Version:         3.2.1
 * Author:          appleuser
 * Author URI:      https://profiles.wordpress.org/appleuser
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     keyword-meta
 * Domain Path:     /languages
 */

if (!(function_exists('createKeywordMeta'))) {
    function createKeywordMeta() {
        echo "<!-- keyword meta -->\n";
        // description
        if (!is_front_page()) {
            if (term_description()!='') {
                $desc = trim(strip_tags(term_description()));
            }
            else {
                $desc = trim(strip_tags(get_the_excerpt()));
            }
            if (strlen($desc)>150) {
                $desc = substr($desc, 0, 150);
                $sdp = strrpos($desc, " ");
                if ($sdp>100) {
                    $desc = substr($desc, 0, $sdp)." …";
                }
            }
        }
        else {
            $desc = trim(strip_tags(get_option('ckm_homedesc')));    
        }
        echo '<meta name="description" content="'.esc_attr(trim($desc)).'" />'."\n";
        // og
        if (intval(get_option('ckm_og_enable'))==1) {
            if (intval(get_option('ckm_og_type_edit'))==1) {
                echo '<meta property="og:type" content="website" />'."\n";
            }
            $ckmoutput_og_title_edit = trim(get_option('ckm_og_title_edit'));
            if (intval(get_option('ckm_og_title_all'))==1 && trim(get_option('ckm_og_title_edit'))!='') {
                echo '<meta property="og:title" content="'.esc_attr(trim(get_option('ckm_og_title_edit'))).'" />'."\n";
            }
            else {
                // get page title
                echo '<meta property="og:title" content="'.get_bloginfo().'" />'."\n";
            }
            if (intval(get_option('ckm_og_url_edit'))==1) {
                // home page uri
                echo '<meta property="og:url" content="'.get_site_url().'" />'."\n";
            }
            else {
                // this page uri/permalink
                if (trim(get_permalink())!='') {
                    echo '<meta property="og:url" content="'.get_permalink().'" />'."\n";
                }
                else {
                    echo '<meta property="og:url" content="'.get_site_url().'" />'."\n";
                }
            }
            // (if page image is empty OR image all is set) AMD ckm_og_image_edit !=''
            if ((get_the_post_thumbnail_url()=='' || intval(get_option('ckm_og_image_all'))==1) && intval(get_option('ckm_og_image_edit'))>0) {
                $imagesrc = wp_get_attachment_image_src( intval(get_option('ckm_og_image_edit')), array(800,600), false );
                echo '<meta property="og:image" content="'.esc_url(trim($imagesrc[0])).'" />'."\n";
            }
            else if (get_the_post_thumbnail_url()!='') {
                echo '<meta property="og:image" content="'.get_the_post_thumbnail_url().'" />'."\n";
            }
            echo '<meta property="og:description" content="'.$desc.'" />'."\n";
        }
        // apple
        if (intval(get_option('ckm_apple_icon_edit'))>0) {
            $imagesrc = wp_get_attachment_image_src( intval(get_option('ckm_apple_icon_edit')), array(512,512), false );
            echo '<link rel="apple-touch-icon" href="'.esc_url(trim($imagesrc[0])).'" />'."\n";
        }
        // android
        if (intval(get_option('ckm_android_icon_edit'))>0) {
            $imagesrc = wp_get_attachment_image_src( intval(get_option('ckm_android_icon_edit')), array(196,196), false );
            echo '<link rel="shortcut icon" href="'.esc_url(trim($imagesrc[0])).'" sizes="196x196" />'."\n";
        }
        // viewport option
        if (intval(get_option('ckm_mobile_restrict_viewport'))==1) {
            echo '<meta name="viewport" content="width=device-width, user-scalable=no" />'."\n";
        }
        // google verify
        if (trim(get_option('ckm_google_verify_edit'))!='') {
            echo '<meta name="verify-v1" content="'.esc_attr(trim(get_option('ckm_google_verify_edit'))).'" />'."\n";
            echo '<meta name="google-site-verification" content="'.esc_attr(trim(get_option('ckm_google_verify_edit'))).'" />'."\n";
        }
    }
}

if (!(function_exists('ckm_loadtext'))) {
    function ckm_loadtext() {
        load_plugin_textdomain( 'keyword-meta', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}
}

if (!(function_exists('ckm_admin'))) {
    function ckm_admin() {
        add_options_page(__("Keywords & Meta", "keyword-meta"), __("Keywords & Meta", "keyword-meta"), 'level_8', __FILE__, 'ckm_options');
    }
}

if (!(function_exists('ckm_options'))) {
    function ckm_options() {

        // THIS is the admin page

        echo "<h1>".__('Keywords & Meta', 'keyword-meta')."</h1>";
        echo "<p>".__('Manage your Meta-Tags', 'keyword-meta')."</p>";

        add_option('ckm_hometags', '');
        add_option('ckm_homedesc', '');
        add_option('ckm_hometagsadd', 0);
        add_option('ckm_og_enable', 0);
        add_option('ckm_og_type_edit', 0);
        add_option('ckm_og_title_edit', '');
        add_option('ckm_og_title_all', 0);
        add_option('ckm_og_url_edit', 0);
        add_option('ckm_og_image_edit', '');
        add_option('ckm_og_image_all', 0);
        add_option('ckm_apple_icon_edit', '');
        add_option('ckm_android_icon_edit', '');
        add_option('ckm_mobile_restrict_viewport', 0);
        add_option('ckm_apple_homescreen_edit', 0);
        add_option('ckm_apple_homescreen_text', '');
        add_option('ckm_google_verify_edit', '');

        if (isset($_POST['keywords_edit'])): update_option('ckm_hometags', trim(" ".filter_var($_POST['keywords_edit'], FILTER_SANITIZE_STRING))); endif;
        if (isset($_POST['description_edit'])): update_option('ckm_homedesc', trim(" ".filter_var($_POST['description_edit'], FILTER_SANITIZE_STRING))); endif;
        if (isset($_POST['keywords_add'])): update_option('ckm_hometagsadd', intval($_POST['keywords_add'])); endif;

        if (isset($_POST['og_enable'])) update_option('ckm_og_enable', intval($_POST['og_enable']));
        if (isset($_POST['og_type_edit'])) update_option('ckm_og_type_edit', intval($_POST['og_type_edit']));
        if (isset($_POST['og_title_edit'])) update_option('ckm_og_title_edit', trim(" ".filter_var($_POST['og_title_edit'], FILTER_SANITIZE_STRING)));
        if (isset($_POST['og_title_all'])) update_option('ckm_og_title_all', intval($_POST['og_title_all']));
        if (isset($_POST['og_url_edit'])) update_option('ckm_og_url_edit', intval($_POST['og_url_edit']));
        if (isset($_POST['og_image_edit'])) update_option('ckm_og_image_edit', intval($_POST['og_image_edit']));
        if (isset($_POST['og_image_all'])) update_option('ckm_og_image_all', intval($_POST['og_image_all']));

        if (isset($_POST['apple_icon_edit'])): update_option('ckm_apple_icon_edit', intval($_POST['apple_icon_edit'])); endif;
        if (isset($_POST['android_icon_edit'])): update_option('ckm_android_icon_edit', intval($_POST['android_icon_edit'])); endif;
        if (isset($_POST['mobile_restrict_viewport'])): update_option('ckm_mobile_restrict_viewport', intval($_POST['mobile_restrict_viewport'])); endif;
        if (isset($_POST['apple_homescreen_edit'])): update_option('ckm_apple_homescreen_edit', intval($_POST['apple_homescreen_edit'])); endif;
        if (isset($_POST['apple_homescreen_text'])): update_option('ckm_apple_homescreen_text', trim(" ".filter_var($_POST['apple_homescreen_text'], FILTER_SANITIZE_STRING))); endif;

        if (isset($_POST['google_verify_edit'])): update_option('ckm_google_verify_edit', trim(" ".filter_var($_POST['google_verify_edit'], FILTER_SANITIZE_STRING))); endif;

        $get_ckm_keys = explode(",", get_option('ckm_hometags'));	
        $newkey = array();
        if (is_array($get_ckm_keys)):
            foreach ($get_ckm_keys AS $kk => $kv):
                if (trim($kv)!=''):
                    $newkey[] = strtolower(trim($kv));
                endif;
            endforeach;
        endif;
        // generix
        $ckmoutput_keylist = trim(implode(", ", $newkey));
        $ckmoutput_description = trim(get_option('ckm_homedesc'));
        $ckmoutput_addkeys = intval(get_option('ckm_hometagsadd'));
        // og
        $ckmoutput_og_enable = intval(get_option('ckm_og_enable'));
        $ckmoutput_og_type_edit = intval(get_option('ckm_og_type_edit'));
        $ckmoutput_og_title_edit = trim(get_option('ckm_og_title_edit'));
        $ckmoutput_og_title_all = intval(get_option('ckm_og_title_all'));
        $ckmoutput_og_url_edit = intval(get_option('ckm_og_url_edit'));
        $ckmoutput_og_image_edit = intval(get_option('ckm_og_image_edit'));
        $ckmoutput_og_image_all = trim(get_option('ckm_og_image_all'));
        $og_disable=''; $og_class=''; if($ckmoutput_og_enable==0): $ckmoutput_og_type_edit = 0; $ckmoutput_og_title_all = 0; $ckmoutput_og_url_edit = 0; $og_class='disabled'; $og_disable=' disabled="disabled" readonly="readonly" '; endif;
        // mobile
        $ckmoutput_apple_icon_edit = trim(get_option('ckm_apple_icon_edit'));
        $ckmoutput_android_icon_edit = trim(get_option('ckm_android_icon_edit'));
        $ckmoutput_mobile_restrict_viewport = intval(get_option('ckm_mobile_restrict_viewport'));
        $ckmoutput_apple_homescreen_edit = intval(get_option('ckm_apple_homescreen_edit'));
        $ckmoutput_apple_homescreen_text = trim(get_option('ckm_apple_homescreen_text'));
        $apple_disable=''; $apple_class=''; if($ckmoutput_apple_homescreen_edit==0): $apple_class='disabled'; $apple_disable=' disabled="disabled" readonly="readonly" '; endif;
        // google
        $ckmoutput_google_verify_edit = trim(get_option('ckm_google_verify_edit'));

        ?>
        <div id="ckm-tabs">
            <ul>
                <li><a href="#tabs-1"><?php echo __("Generic", "keyword-meta"); ?></a></li>
                <li><a href="#tabs-2"><?php echo __("OpenGraph", "keyword-meta"); ?></a></li>
                <li><a href="#tabs-3"><?php echo __("Mobile", "keyword-meta"); ?></a></li>
                <li><a href="#tabs-4"><?php echo __("Google", "keyword-meta"); ?></a></li>
                <li class="closetabs"></li>
            </ul>
            <form action="" method="post">
            <div class="ckm-tab" id="tabs-1">
                <h2><?php echo __("Generic Description", "keyword-meta"); ?></h2>
                <input type="hidden" name="keywords_add" value="0" /><input type="hidden" name="keywords_edit" value="" />
                <p><textarea rows="5" cols="80" style="width: 98%;" id="description_edit" name="description_edit" placeholder="<?php echo __("Description", "keyword-meta"); ?>" onkeyup="showQuality('description_edit',150,300);"><?php echo esc_textarea($ckmoutput_description); ?></textarea></p>
                <p><?php echo __("The description will be used on every page no excerpt is defined.", "keyword-meta"); ?> <?php echo __("You have used", "keyword-meta"); ?> <span id="show_description_edit_length"><?php echo strlen($ckmoutput_description); ?></span> <?php echo __("of 300 description chars.", "keyword-meta"); ?> <?php echo __("The background color will help you to assess the optimized char count.", "keyword-meta"); ?></p>
                <p><input type="submit" class="button button-primary" value="<?php echo __("Save", "keyword-meta"); ?>"></p>
            </div>
            <div id="tabs-2">
                <h2><?php echo __("OpenGraph Meta-Tags", "keyword-meta"); ?></h2>
                <p><input type="hidden" name="og_enable" value="0" /><input type="checkbox" name="og_enable" id="og_enable" value="1" <?php if($ckmoutput_og_enable==1) echo " checked='checked' "; ?> onchange="showHide('og', this.checked)" /> <label for="og_enable"><?php echo __("Enable OpenGraph", "keyword-meta"); ?></label></p>
                <p><input type="hidden" name="og_type_edit" value="0" /><input type="checkbox" id="og_type_edit" name="og_type_edit" value="1" <?php if($ckmoutput_og_type_edit==1) echo " checked='checked' "; ?> class='og <?php echo $og_class; ?>' <?php echo $og_disable; ?> /> <label for="og_type_edit"><?php echo __("Setup OpenGraph Type to 'Website'", "keyword-meta"); ?></label></p>
                <p><input type="text" name="og_title_edit" class="ckm-input og <?php echo $og_class; ?>" placeholder="<?php echo __("OpenGraph title", "keyword-meta"); ?>" <?php echo $og_disable; ?> <?php if($ckmoutput_og_title_edit!='') echo " value=\"".esc_attr($ckmoutput_og_title_edit)."\" "; ?> /></p>
                <p><input type="hidden" name="og_title_all" value="0" /><input type="checkbox" name="og_title_all" id="og_title_all_field" value="1" <?php if($ckmoutput_og_title_all==1) echo " checked='checked' "; ?> class='og <?php echo $og_class; ?>' <?php echo $og_disable; ?> /> <label for="og_title_all_field"><?php echo __("Setup this title as OpenGraph title to ALL pages", "keyword-meta"); ?></label></p>
                <p><?php echo __("OpenGraph will use the permalink of a page or an entry to show OpenGraph URL", "keyword-meta"); ?></p>
                <p><input type="hidden" name="og_url_edit" value="0" /><input type="checkbox" id="og_url_edit" name="og_url_edit" value="1" <?php if($ckmoutput_og_url_edit==1) echo " checked='checked' "; ?> class='og <?php echo $og_class; ?>' <?php echo $og_disable; ?> /> <label for="og_url_edit"><?php echo __("Setup base URL as OpenGraph URL to ALL pages", "keyword-meta"); ?></label></p>
                <h4><?php echo __("OpenGraph Image", "keyword-meta"); ?></h4>
                <?php

                if( intval( $ckmoutput_og_image_edit ) > 0 ) {
                    // Change with the image size you want to use
                    $og_image_url = wp_get_attachment_image_url( $ckmoutput_og_image_edit, 'thumbnail', false);
                    $og_image_action = __('to change', 'keyword-meta');
                } else {
                    // Some default image
                    $og_image_url = plugins_url('/images/og_image.png', __FILE__);
                    $og_image_action = __('to setup', 'keyword-meta');
                }

                ?>
                <img id="og_image_preview" class="ckm_media_edit" rel="og_image" src="<?php echo esc_url($og_image_url); ?>" />
                <input type="hidden" name="og_image_edit" id="og_image_edit" value="<?php echo intval($ckmoutput_og_image_edit); ?>" class="regular-text" />

                <p><?php echo __("If no image is set to a page, OpenGraph can show an image from your library as OpenGraph image.", "keyword-meta"); ?> <?php printf( __("Click the image %s.", 'keyword-meta'), $og_image_action); ?></p>
                <p><input type="hidden" name="og_image_all" value="0" /><input type="checkbox" name="og_image_all" id="og_image_all_field" value="1" <?php if($ckmoutput_og_image_all==1) echo " checked='checked' "; ?> class='og <?php echo $og_class; ?>' <?php echo $og_disable; ?> /> <label for="og_image_all_field"><?php echo __("Setup this image as OpenGraph Image to ALL pages", "keyword-meta"); ?></label></p>
                <p><input type="submit" class="button button-primary" value="<?php echo __("Save", "keyword-meta"); ?>"></p>
            </div>
            <div id="tabs-3">
                <h2><?php echo __("Mobile related Meta-Tags", "keyword-meta"); ?></h2>
                <p><?php echo __("Setup the touch icon URL", "keyword-meta"); ?><?php /* echo __("Setup the Apple touch icon URL and the option to give a hint to add the page to homescreen", "keyword-meta"); */ ?></p>
                <?php

                if( intval( $ckmoutput_apple_icon_edit ) > 0 ) {
                    // Change with the image size you want to use
                    $apple_icon = wp_get_attachment_image( $ckmoutput_apple_icon_edit, 'thumbnail', false, array( 'id' => 'apple_icon_preview', 'rel' => 'apple_icon', 'class' => 'ckm_media_edit' ) );
                    $apple_icon_action = __('to change', 'keyword-meta');
                } else {
                    // Some default image
                    $apple_icon = '<img id="apple_icon_preview" rel="apple_icon" class="ckm_media_edit" src="'.plugins_url('/images/apple_icon.png', __FILE__).'" />';
                    $apple_icon_action = __('to setup', 'keyword-meta');
                }
                echo $apple_icon;
        
                ?>
                <input type="hidden" name="apple_icon_edit" id="apple_icon_edit" value="<?php echo intval($ckmoutput_apple_icon_edit); ?>" />
                <p><?php printf( __("Click the image %s.", 'keyword-meta'), $apple_icon_action); ?> <em><?php echo __("Your Icon should be 512x512 pixels and not contain alpha", "keyword-meta"); ?></em></p>
                <?php

                if( intval( $ckmoutput_android_icon_edit ) > 0 ) {
                    // Change with the image size you want to use
                    $android_icon = wp_get_attachment_image( $ckmoutput_android_icon_edit, 'thumbnail', false, array( 'id' => 'android_icon_preview', 'rel' => 'android_icon', 'class' => 'ckm_media_edit' ) );
                    $android_icon_action = __('to change', 'keyword-meta');
                } else {
                    // Some default image
                    $android_icon = '<img id="android_icon_preview" rel="android_icon" class="ckm_media_edit" src="'.plugins_url('/images/android_icon.png', __FILE__).'" />';
                    $android_icon_action = __('to setup', 'keyword-meta');
                }
                echo $android_icon;
        
                ?>
                <input type="hidden" name="android_icon_edit" id="android_icon_edit" value="<?php echo intval($ckmoutput_android_icon_edit); ?>" />
                <p><?php printf( __("Click the image %s.", 'keyword-meta'), $android_icon_action); ?> <em><?php echo __("Your Icon should be 196x196 pixels and not contain alpha", "keyword-meta"); ?></em></p>
                <p><input type="hidden" name="mobile_restrict_viewport" value="0" /><input type="checkbox" name="mobile_restrict_viewport" id="mobile_restrict_viewport_field" value="1" <?php if($ckmoutput_mobile_restrict_viewport==1) echo " checked='checked' "; ?> class='og <?php echo $og_class; ?>' <?php echo $og_disable; ?> /> <label for="mobile_restrict_viewport_field"><?php echo __("Set mobile viewport and disable resizing on mobile devices if not supported by theme", "keyword-meta"); ?></label></p>
                <p><input type="submit" class="button button-primary" value="<?php echo __("Save", "keyword-meta"); ?>"></p>
            </div>
            <div id="tabs-4">
                <h2><?php echo __("Google related Meta-Tags", "keyword-meta"); ?></h2>
                <p><?php echo __("Setup the Google site verification code to show Google, this is your website", "keyword-meta"); ?></p>
                 <p><input type="text" class="ckm-input" name="google_verify_edit" placeholder="Google Verify ID" value="<?php echo esc_attr($ckmoutput_google_verify_edit); ?>" /></p>
                 <p><input type="submit" class="button button-primary" value="<?php echo __("Save", "keyword-meta"); ?>"></p>
            </div>
            </form>
        </div>
        <?php

        }
}

if (!(function_exists('ckm_set_options'))) {
    function ckm_set_options() {
        add_option('ckm_hometags', '');
        add_option('ckm_homedesc', '');
        add_option('ckm_hometagsadd', 0);
        add_option('ckm_og_enable', 0);
        add_option('ckm_og_type_edit', 0);
        add_option('ckm_og_title_edit', '');
        add_option('ckm_og_title_all', 0);
        add_option('ckm_og_url_edit', 0);
        add_option('ckm_og_image_edit', '');
        add_option('ckm_og_image_all', 0);
        add_option('ckm_apple_icon_edit', '');
        add_option('ckm_android_icon_edit', '');
        add_option('ckm_mobile_restrict_viewport', 0);
        add_option('ckm_apple_homescreen_edit', 0);
        add_option('ckm_apple_homescreen_text', '');
        add_option('ckm_google_verify_edit', '');
	}
}

if (!(function_exists('ckm_deinstall'))) {
    function ckm_deinstall() {
        delete_option('ckm_hometags');
        delete_option('ckm_homedesc');
        delete_option('ckm_hometagsadd');
        delete_option('ckm_og_enable');
        delete_option('ckm_og_type_edit');
        delete_option('ckm_og_title_edit');
        delete_option('ckm_og_title_all');
        delete_option('ckm_og_url_edit');
        delete_option('ckm_og_image_edit');
        delete_option('ckm_og_image_all');
        delete_option('ckm_apple_icon_edit');
        delete_option('ckm_android_icon_edit');
        delete_option('ckm_mobile_restrict_viewport');
        delete_option('ckm_apple_homescreen_edit');
        delete_option('ckm_apple_homescreen_text');
        delete_option('ckm_google_verify_edit');
	}
}

if (!(function_exists('ckm_script'))) {	
    function ckm_script() {
        // Enqueue WordPress media scripts
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-tabs', plugins_url('/js/jquery-tabs.min.js', __FILE__), array('jquery'), '1.12.1'.time(), true);
        wp_enqueue_script( 'keyword-meta', plugins_url('/js/keyword-meta.js', __FILE__), array('jquery-tabs'), '3.0.'.time(), true);
        wp_enqueue_style( 'keyword-meta', plugins_url('/css/keyword-meta.css', __FILE__), array(), '3.0.'.time());
    }
}

if (!(function_exists('ckm_get_image'))) {
    function ckm_get_image() {
        if(isset($_GET['id'])) {
            if (intval($_GET['id'])>0) {
                $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'thumbnail', false, array( 
                    'id' => filter_input( INPUT_GET, 'rel', FILTER_SANITIZE_STRING ).'_preview',
                    'rel' => filter_input( INPUT_GET, 'rel', FILTER_SANITIZE_STRING ),
                    'class' => 'ckm_media_edit', 
                ));
                $data = array(
                    'image' => $image,
                );
                wp_send_json_success( $data );
            }
            else {
                wp_send_json_success( array(
                    'image' => '<img src="'.plugins_url('/images/'.filter_input( INPUT_GET, 'rel', FILTER_SANITIZE_STRING ).'.png', __FILE__).'" id="'.filter_input( INPUT_GET, 'rel', FILTER_SANITIZE_STRING ).'_preview" rel="'.filter_input( INPUT_GET, 'rel', FILTER_SANITIZE_STRING ).'" class="ckm_media_edit" />',
                ) );
            }
        } else {
            wp_send_json_error();
        }
    }
}

if (function_exists('_activation_hook')) {
	register_activation_hook(__FILE__, 'ckm_set_options');
	}

if (function_exists('register_uninstall_hook')) {
	register_uninstall_hook(__FILE__, 'ckm_deinstall');
	}

if (is_admin()) {
	add_action('admin_enqueue_scripts', 'ckm_script');
	add_action('plugins_loaded', 'ckm_loadtext');
	add_action('admin_menu', 'ckm_admin');
    add_action( 'wp_ajax_ckm_get_image', 'ckm_get_image');
}

add_action('wp_head', 'createKeywordMeta');

?>