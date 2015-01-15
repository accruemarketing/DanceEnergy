<?php
/*
 * Plugin options page
 */
function abcfmlcf_add_plugin_options_page() {

    //Add sub menu page to the Settings menu.
    if(has_action('abcfmlcf_hide_plugin_options_page')) {
            //Hook to hide options page
            delete_option( 'abcfmlcf_optns' );
            do_action('abcfmlcf_hide_plugin_options_page');
        }
    else{
            add_options_page("Media Library Custom Fields", "Media Library Custom Fields", 'manage_options', basename(__FILE__), "abcfmlcf_optns_pg");
        }
}

add_action('admin_menu', 'abcfmlcf_add_plugin_options_page');


//Plugin options form
function abcfmlcf_optns_pg() {

    // Check permissions
    if (!current_user_can('manage_options')) { ?><div class="error"><p><strong><?php _e( 'You do not have sufficient permissions to edit this site.' ); ?></strong></p></div><?php wp_die();}

    //Save custom options
    if (isset($_POST['abcfmlcf_update_optns'])) {

        // Don't save if the user hasn't submitted the changes
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
        // Verify that the input is coming from the proper form
        if (!wp_verify_nonce($_POST['abcfmlc_nonce'],plugin_basename( __FILE__ )) ) { return; }

            $cap2_l = '';
            $cap3_l = '';
            $cap4_l = '';
            $custom_url_l = '';
            $custom_url_target_l = '';
            $set_no_l = '';
            $onclick_js_l = '';

            $_POST['abcfmlcf_cap2_h'] = (isset($_POST['abcfmlcf_cap2_h'])) ? 1 : 0;
            $cap2_h = $_POST['abcfmlcf_cap2_h'];
            if(!$cap2_h) { $cap2_l = esc_attr($_POST['abcfmlcf_cap2_l']); }

            $_POST['abcfmlcf_cap3_h'] = (isset($_POST['abcfmlcf_cap3_h'])) ? 1 : 0;
            $cap3_h = $_POST['abcfmlcf_cap3_h'];
            if(!$cap3_h) { $cap3_l = esc_attr($_POST['abcfmlcf_cap3_l']); }

            $_POST['abcfmlcf_cap4_h'] = (isset($_POST['abcfmlcf_cap4_h'])) ? 1 : 0;
            $cap4_h = $_POST['abcfmlcf_cap4_h'];
            if(!$cap4_h) { $cap4_l = esc_attr($_POST['abcfmlcf_cap4_l']); }

            $_POST['abcfmlcf_custom_url_h'] = (isset($_POST['abcfmlcf_custom_url_h'])) ? 1 : 0;
            $custom_url_h = $_POST['abcfmlcf_custom_url_h'];
            if(!$custom_url_h) { $custom_url_l = esc_attr($_POST['abcfmlcf_custom_url_l']); }

            $_POST['abcfmlcf_custom_url_target_h'] = (isset($_POST['abcfmlcf_custom_url_target_h'])) ? 1 : 0;
            $custom_url_target_h = $_POST['abcfmlcf_custom_url_target_h'];
            if(!$custom_url_target_h) { $custom_url_target_l = esc_attr($_POST['abcfmlcf_custom_url_target_l']); }

            $_POST['abcfmlcf_set_no_h'] = (isset($_POST['abcfmlcf_set_no_h'])) ? 1 : 0;
            $set_no_h = $_POST['abcfmlcf_set_no_h'];
            if(!$set_no_h) { $set_no_l = esc_attr($_POST['abcfmlcf_set_no_l']); }

            $_POST['abcfmlcf_onclick_js_h'] = (isset($_POST['abcfmlcf_onclick_js_h'])) ? 1 : 0;
            $onclick_js_h = $_POST['abcfmlcf_onclick_js_h'];
            if(!$onclick_js_h) { $onclick_js_l = esc_attr($_POST['abcfmlcf_onclick_js_l']); }


            $save_optns = array(
                'cap2' => array(
                    'hide' => $cap2_h,
                    'lbl' => $cap2_l
                ),
                'cap3' => array(
                    'hide' => $cap3_h,
                    'lbl' => $cap3_l
                ),
                'cap4' => array(
                    'hide' => $cap4_h,
                    'lbl' => $cap4_l
                ),
                'custom_url' => array(
                    'hide' => $custom_url_h,
                    'lbl' => $custom_url_l
                ),
                'custom_url_target' => array(
                    'hide' => $custom_url_target_h,
                    'lbl' => $custom_url_target_l
                ),
                'set_no' => array(
                    'hide' => $set_no_h,
                    'lbl' => $set_no_l
                ),
                'onclick_js' => array(
                    'hide' => $onclick_js_h,
                    'lbl' => $onclick_js_l
                )
            );

            update_option( 'abcfmlcf_optns', $save_optns ) ;

            ?><div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div><?php
    }

    //Delete custom options
    if (isset($_POST['abcfmlcf_reset_optns'])) {

        // Don't save if the user hasn't submitted the changes
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

        // Verify that the input is coming from the proper form
        if (!wp_verify_nonce($_POST['abcfmlc_nonce'],plugin_basename( __FILE__ )) ) { return; }

        delete_option( 'abcfmlcf_optns' );
        ?><div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div><?php
    }

    $optns = abcfmlcf_get_plugin_options();?>

    <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <?php    echo "<h2>" . __( 'Media Library Custom Fields', 'abcfmlcf-td' ) . "</h2>";
    echo '<div style="margin-top:.5em;">' . __( 'Documentation & Tutorials: ', 'abcfmlcf-td' ) . '<a target="_blank" href=" http://abcfolio.com/help/wordpress-media-library-custom-fields-overview/."> http://abcfolio.com/help/wordpress-media-library-custom-fields-overview/.</a></div>';
    echo '<h3 style="margin-bottom:.05em;margin-top:2em;">' . __( 'Options. Hide fields or customize field labels.', 'abcfmlcf-td' ) . '</h3>';
    ?>

    <form name="abcfmlcf-tdform" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="abcfmlcf_update_optns" value="Y">

<table class="form-table" style="width:500px; margin-top:0;">
    <tbody>
        <?php
        $capC = __( 'Caption ', 'abcfmlcf-td' );
        $capL = __( 'Label', 'abcfmlcf-td' );
        $capUrl = __( 'Custom URL', 'abcfmlcf-td' );
        $capUrlT = __( 'URL Target', 'abcfmlcf-td' );
        $set = __( 'Set Number', 'abcfmlcf-td' );
        $onclickLbl = __( 'onclick JavaScript', 'abcfmlcf-td' );
        $lblHide = __( ' Hide', 'abcfmlcf-td' );
        $n = 'abcfmlcf_';

        echo abcfmlcf_tr_hdivider2();
        echo abcfmlcf_tr_lbl($capC . '2 - ' . $capL, $optns['cap2']['lbl'], '', $n . 'cap2_l');
        echo abcfmlcf_tr_chk($capC . '2', $optns['cap2']['hide'], '', $n . 'cap2_h', $lblHide);
        echo abcfmlcf_tr_hdivider();

        echo abcfmlcf_tr_lbl($capC . '3 - ' . $capL, $optns['cap3']['lbl'], '', $n . 'cap3_l');
        echo abcfmlcf_tr_chk($capC . '3', $optns['cap3']['hide'], '', $n . 'cap3_h', $lblHide);
        echo abcfmlcf_tr_hdivider();

        echo abcfmlcf_tr_lbl($capC . '4 - ' . $capL, $optns['cap4']['lbl'], '', $n . 'cap4_l');
        echo abcfmlcf_tr_chk($capC . '4', $optns['cap4']['hide'], '', $n . 'cap4_h', $lblHide);
        echo abcfmlcf_tr_hdivider();

        echo abcfmlcf_tr_lbl($capUrl . ' - ' . $capL, $optns['custom_url']['lbl'], '', $n . 'custom_url_l');
        echo abcfmlcf_tr_chk($capUrl, $optns['custom_url']['hide'], '', $n . 'custom_url_h', $lblHide);
        echo abcfmlcf_tr_hdivider();

        echo abcfmlcf_tr_lbl($capUrlT . ' - ' . $capL, $optns['custom_url_target']['lbl'], '', $n . 'custom_url_target_l');
        echo abcfmlcf_tr_chk($capUrlT, $optns['custom_url_target']['hide'], '', $n . 'custom_url_target_h', $lblHide);
        echo abcfmlcf_tr_hdivider();

        echo abcfmlcf_tr_lbl($set . ' - ' . $capL, $optns['set_no']['lbl'], '', $n . 'set_no_l');
        echo abcfmlcf_tr_chk($set, $optns['set_no']['hide'], '', $n . 'set_no_h', $lblHide);
        echo abcfmlcf_tr_hdivider();

        echo abcfmlcf_tr_lbl($onclickLbl . ' - ' . $capL, $optns['onclick_js']['lbl'], '', $n . 'onclick_js_l');
        echo abcfmlcf_tr_chk($onclickLbl, $optns['onclick_js']['hide'], '', $n . 'onclick_js_h', $lblHide);
        echo abcfmlcf_tr_hdivider2();

        ?>
    </tbody>
</table>

 <?php wp_nonce_field(plugin_basename( __FILE__ ),'abcfmlc_nonce'); ?>

 <p class="submit"><input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'abcfmlcf-td' ) ?>" /></p>
</form>

 <form name="abcfmlcf-tdform" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="abcfmlcf_reset_optns" value="Y">
    <?php wp_nonce_field(plugin_basename( __FILE__ ),'abcfmlc_nonce'); ?>
    <p class="submit">
        <input class="button button-primary" type="submit" name="abcfmlcf-reset" value="<?php _e('Reset Options', 'abcfmlcf-td' ) ?>" />
    </p>
 </form>
</div><?php
}


//Merge default and custom options.
function abcfmlcf_get_plugin_options() {

    //$x = get_option( 'abcfmlcf_optns', array() );
     //delete_option('abcfmlcf_optns');
     //$x = get_option( 'abcfmlcf_optns', array() );

//print '<pre>';
//var_dump($x);
//print '</pre>';
//die;

    $optns = array_merge(abcfmlcf_default_optns(), get_option( 'abcfmlcf_optns', array() ));
    if(has_filter('abcfmlcf_plugin_options')) {  $optns = apply_filters('abcfmlcf_plugin_options', $optns); }

//print '<pre>';
//var_dump($optns);
//print '</pre>';
//die;


    return wp_parse_args($optns);
}

//Default options.
function abcfmlcf_default_optns(){

            return array(
            'cap2' => array(
                'hide' => false,
                'lbl' => ''
            ),
            'cap3' => array(
                'hide' => true,
                'lbl' => ''
            ),
            'cap4' => array(
                'hide' => true,
                'lbl' => ''
            ),
            'custom_url' => array(
                'hide' => false,
                'lbl' => ''
            ),
            'custom_url_target' => array(
                'hide' => false,
                'lbl' => ''
            ),
            'set_no' => array(
                'hide' => true,
                'lbl' => ''
            ),
            'onclick_js' => array(
                'hide' => true,
                'lbl' => ''
            )
        );
}

//HTML. Horizontal line.
function abcfmlcf_tr_hdivider(){
  return '<tr valign="top"><td colspan="2"><div style="border-top-style: solid; border-width: 2px; border-color: #999999; width: 500px;"></div></td></tr>';
}

function abcfmlcf_tr_hdivider2(){
  return '<tr valign="top"><td colspan="2"><div style="border-top-style: solid; border-width: 2px; border-color: #999999; width: 500px;"></div></td></tr>';
}

//HTML. Label.
function abcfmlcf_tr_lbl($lbl, $value, $id, $name){

  return '<tr valign="top"><th scope="row"><label>' . $lbl . '</label></th><td><input type="text" class="regular-text" value="' . $value .
          '" id="' . $id . '" name="' . $name . '" /></td></tr>';
}

//HTML. Checkbox with label.
function abcfmlcf_tr_chk($lbl1, $value, $id, $name, $lblHide){

  $checked = '';
  if($value) {$checked = " checked=\"checked\" ";}

  return '<tr valign="top"><th scope="row">' . $lbl1 . '</th><td><fieldset><label><input type="checkbox" value="' . $value .
          '" id="' . $id . '" name="' . $name . '"' . $checked . ' />' . $lblHide . '</label></fieldset></tr>';
}