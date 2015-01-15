<?php
/**
 * Plugin Name: Media Library Custom Fields
 * Plugin URI: http://abcfolio.com/help/wordpress-media-library-custom-fields/
 * Description: Add custom URLs and other fields to images in WordPress media library.
 * Author: abcFolio WordPress Plugins
 * Author URI: http://www.abcfolio.com
 * Version: 1.1.6
 * Text Domain: abcfmlcf-td
 * Domain Path: /languages
 *
 * Media Library Custom Fields is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Media Library Custom Fields is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Media Library Custom Fields. If not, see <http://www.gnu.org/licenses/>.
 *
 */

add_action( 'init', array( 'ABCFMLCF_Media_Lib_Custom_Flds', 'init' ) );

class ABCFMLCF_Media_Lib_Custom_Flds {

    public static function init() {

        require_once plugin_dir_path( __FILE__ ) . '/get-images.php';

        if ( is_admin() ) {

            //Load translations.
            load_plugin_textdomain( 'abcfmlcf-td', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            require_once plugin_dir_path( __FILE__ ) . '/options.php';
	}

        // Add custom fields.
        add_filter( 'attachment_fields_to_edit', array( 'ABCFMLCF_Media_Lib_Custom_Flds', 'get_attachment_fields' ), null, 2);

        // Save custom fields.
        add_filter( 'attachment_fields_to_save', array( 'ABCFMLCF_Media_Lib_Custom_Flds', 'save_attachment_fields' ), null , 2);

    }

    //Return HTML for all custom fields
    public static function get_attachment_fields( $form_fields, $post ) {

        //Get custom options
        $optns = abcfmlcf_get_plugin_options();
        $lblCap = __( 'Caption', 'abcfmlcf-td' );

        //Build custom fields HTML
        if(!$optns['cap2']['hide']){

            $lblCap2 = $optns['cap2']['lbl'];
            if(empty($lblCap2)){ $lblCap2 = $lblCap . ' 2';}

            $cap2 = get_post_meta( $post->ID, '_abcfmlcf_caption2', true );
            $form_fields['abcfmlcf_caption2']['tr'] = '<tr><td colspan="2" style="width:800px;"><p>
                <label for="abcfmlcf_caption2"><strong>' . $lblCap2 . '</strong></label><br>
                <input type="text" value="' . $cap2 . '" id="attachments-' . $post->ID . '-abcfmlcf_caption2" name="attachments[' . $post->ID . '][abcfmlcf_caption2]"  class="widefat" />
                </p></td></tr>';
        }

        if(!$optns['cap3']['hide']){

            $lblCap3 = $optns['cap3']['lbl'];
            if(empty($lblCap3)){ $lblCap3 = $lblCap . ' 3';}

        $cap3 = get_post_meta( $post->ID, '_abcfmlcf_caption3', true );
        $form_fields['abcfmlcf_caption3']['tr'] = '<tr><td colspan="2" style="width:800px;"><p>
            <label for="abcfmlcf_caption3"><strong>' . $lblCap3 . '</strong></label><br>
            <input type="text" value="' . $cap3 . '" id="attachments-' . $post->ID . '-abcfmlcf_caption3" name="attachments[' . $post->ID . '][abcfmlcf_caption3]"  class="widefat" />
            </p></td></tr>';
        }

        if(!$optns['cap4']['hide']){

            $lblCap4 = $optns['cap4']['lbl'];
            if(empty($lblCap4)){ $lblCap4 = $lblCap . ' 4';}

            $cap4 = get_post_meta( $post->ID, '_abcfmlcf_caption4', true );
            $form_fields['abcfmlcf_caption4']['tr'] = '<tr><td colspan="2" style="width:800px;"><p>
                <label for="abcfmlcf_caption4"><strong>' . $lblCap4 . '</strong></label><br>
                <input type="text" value="' . $cap4 . '" id="attachments-' . $post->ID . '-abcfmlcf_caption4" name="attachments[' . $post->ID . '][abcfmlcf_caption4]"  class="widefat" />
                </p></td></tr>';
        }



        if(!$optns['custom_url']['hide']){

            $lblUrl = $optns['custom_url']['lbl'];
            if(empty($lblUrl)){ $lblUrl = __( 'Custom Link URL', 'abcfmlcf-td' );}

            $custUrl = get_post_meta( $post->ID, '_abcfmlcf_custom_url', true );
            $form_fields['abcfmlcf_custom_url']['tr'] = '<tr><td colspan="2" style="width:800px;">
                <label for="abcfmlcf_custom_url"><strong>' . $lblUrl . '</strong></label><br>
                    <input type="text" value="' . $custUrl . '" id="attachments-' . $post->ID . '-abcfmlcf_custom_url" name="attachments[' . $post->ID . '][abcfmlcf_custom_url]"  class="widefat" />
                    </td></tr>';
        }

        if(!$optns['custom_url_target']['hide']){

            $lblUrlT = $optns['custom_url_target']['lbl'];
            if(empty($lblUrlT)){ $lblUrlT = __( 'Custom Link Target', 'abcfmlcf-td' );}

            $custUrlTarget = get_post_meta( $post->ID, '_abcfmlcf_custom_url_target', true );
            $form_fields['abcfmlcf_custom_url_target']['tr'] = '<tr><td colspan="2"><label for="abcfmlcf_custom_url_target"><strong>' . $lblUrlT . '</strong></label><br>
            <select name="attachments[' . $post->ID. '][abcfmlcf_custom_url_target]" id="attachments-' . $post->ID . '-abcfmlcf_custom_url_target">
                                <option value="">' . __( 'Same Window', 'abcfmlcf-td' ) . '</option>
                                <option value="_blank"' . ( $custUrlTarget == '_blank' ? ' selected="selected"' : '') . '>' . __( 'New Window or Tab', 'abcfmlcf-td' ) . '</option>
                        </select></td></tr>';
        }

       if(!$optns['onclick_js']['hide']){

            $lblJs = $optns['onclick_js']['lbl'];
            if(empty($lblJs)){ $lblJs = __( 'JavaScript', 'abcfmlcf-td' );}

            $jsValue = get_post_meta( $post->ID, '_abcfmlcf_onclick_js', true );
            $form_fields['abcfmlcf_lnk_js']['tr'] = '<tr><td colspan="2" style="width:800px;"><p>
                <label for="abcfmlcf_lnk_js"><strong>' . $lblJs . '</strong></label><br>
                <input type="text" value="' . $jsValue . '" id="attachments-' . $post->ID . '-abcfmlcf_lnk_js" name="attachments[' . $post->ID . '][abcfmlcf_lnk_js]"  class="widefat" />
                </p></td></tr>';
        }

        if(!$optns['set_no']['hide']){

            $lblSetNo = $optns['set_no']['lbl'];
            if(empty($lblSetNo)){ $lblSetNo = __( 'Set Number', 'abcfmlcf-td' );}

            $set = get_post_meta( $post->ID, '_abcfmlcf_set_no', true );
            $form_fields['abcfmlcf_set_no']['tr'] = '<tr><td colspan="2" style="width:800px;"><p>
                <label for="abcfmlcf_set_no"><strong>' . $lblSetNo . '</strong></label><br>
                <input type="text" value="' . $set . '" id="attachments-' . $post->ID . '-abcfmlcf_set_no" name="attachments[' . $post->ID . '][abcfmlcf_set_no]"  class="widefat" />
                </p></td></tr>';
        }

        return $form_fields;
    }

    //Save custom fields data esc_textarea
    public static function save_attachment_fields( $post, $attachment ) {

        if( isset( $attachment['abcfmlcf_caption2'] ) ) {
                update_post_meta( $post['ID'], '_abcfmlcf_caption2', $attachment['abcfmlcf_caption2'] );
        }
        if( isset( $attachment['abcfmlcf_caption3'] ) ) {
                update_post_meta( $post['ID'], '_abcfmlcf_caption3', $attachment['abcfmlcf_caption3'] );
        }
        if( isset( $attachment['abcfmlcf_caption4'] ) ) {
                update_post_meta( $post['ID'], '_abcfmlcf_caption4', $attachment['abcfmlcf_caption4'] );
        }
        if( isset( $attachment['abcfmlcf_set_no'] ) ) {
                update_post_meta( $post['ID'], '_abcfmlcf_set_no', $attachment['abcfmlcf_set_no'] );
        }
        if( isset( $attachment['abcfmlcf_custom_url'] ) ) {
                update_post_meta( $post['ID'], '_abcfmlcf_custom_url', htmlspecialchars($attachment['abcfmlcf_custom_url']) );
        }

//        if( isset( $attachment['abcfmlcf_custom_url'] ) ) {
//                update_post_meta( $post['ID'], '_abcfmlcf_custom_url', $attachment['abcfmlcf_custom_url'] );
//        }


        if( isset( $attachment['abcfmlcf_custom_url_target'] ) ) {
                update_post_meta( $post['ID'], '_abcfmlcf_custom_url_target', $attachment['abcfmlcf_custom_url_target'] );
        }
        if( isset( $attachment['abcfmlcf_lnk_js'] ) ) {
                update_post_meta( $post['ID'], '_abcfmlcf_onclick_js', $attachment['abcfmlcf_lnk_js'] );
        }
        return $post;
    }

}