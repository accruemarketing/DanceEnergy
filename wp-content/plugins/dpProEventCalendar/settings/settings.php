<?php // Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'dpProEventCalendar_settings');
  add_action('admin_init', 'dpProEventCalendar_register_mysettings'); 
} 

// function for adding settings page to wp-admin
function dpProEventCalendar_settings() {
global $dpProEventCalendar, $current_user;

	if(!is_array($dpProEventCalendar['user_roles'])) { $dpProEventCalendar['user_roles'] = array(); }
	if(!in_array(dpProEventCalendar_get_user_role(), $dpProEventCalendar['user_roles']) && dpProEventCalendar_get_user_role() != "administrator" && !is_super_admin($current_user->ID)) { return; }
    // Add a new submenu under Options:
	add_menu_page( 'Event Calendar', __('Event Calendar', 'dpProEventCalendar'), 'edit_posts','dpProEventCalendar-admin', 'dpProEventCalendar_calendars_page', dpProEventCalendar_plugin_url( 'images/dpProEventCalendar_icon.gif' ), 139 );
	add_submenu_page('dpProEventCalendar-admin', __('Categories', 'dpProEventCalendar'), __('Categories', 'dpProEventCalendar'), 'edit_posts', 'edit-tags.php?taxonomy=pec_events_category');
	add_submenu_page('dpProEventCalendar-admin', __('Calendars', 'dpProEventCalendar'), __('Calendars', 'dpProEventCalendar'), 'edit_posts', 'dpProEventCalendar-admin', 'dpProEventCalendar_calendars_page');
	add_submenu_page('dpProEventCalendar-admin', __('Special Dates', 'dpProEventCalendar'), __('Special Dates', 'dpProEventCalendar'), 'edit_posts', 'dpProEventCalendar-special', 'dpProEventCalendar_special_page');
	add_submenu_page('dpProEventCalendar-admin', __('Settings', 'dpProEventCalendar'), __('Settings', 'dpProEventCalendar'), 'edit_posts', 'dpProEventCalendar-settings', 'dpProEventCalendar_settings_page');
	add_submenu_page('dpProEventCalendar-admin', __('Custom Shortcodes', 'dpProEventCalendar'), __('Custom Shortcodes', 'dpProEventCalendar'), 'edit_posts', 'dpProEventCalendar-custom-shortcodes', 'dpProEventCalendar_custom_shortcodes_page');
	//add_submenu_page('dpProEventCalendar-admin', __('Display Data in Event Page', 'dpProEventCalendar'), __('Display Data in Event Page', 'dpProEventCalendar'), 'edit_posts', 'dpProEventCalendar-eventdata', 'dpProEventCalendar_eventdata_page');
}

function dpProEventCalendar_get_user_role() {
	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	return $user_role;
}

include(dirname(__FILE__) . '/calendars.php');
include(dirname(__FILE__) . '/events-meta.php');
include(dirname(__FILE__) . '/special.php');
include(dirname(__FILE__) . '/custom_shortcodes.php');
include(dirname(__FILE__) . '/eventdata.php');

// This function displays the page content for the Settings submenu
function dpProEventCalendar_settings_page() {
global $dpProEventCalendar, $wpdb;
?>

<div class="wrap" style="clear:both;" id="dp_options">

<h2></h2>
<?php $url = dpProEventCalendar_admin_url( array( 'page' => 'dpProEventCalendar-admin' ) );?>

<form method="post" action="options.php" enctype="multipart/form-data">
<?php settings_fields('dpProEventCalendar-group'); ?>
<div style="clear:both;"></div>
 <!--end of poststuff --> 
	
    <div id="dp_ui_content">
    	
        <div id="leftSide">
        	<div id="dp_logo"></div>
            <p>
                Version: <?php echo DP_PRO_EVENT_CALENDAR_VER?><br />
            </p>
            <ul id="menu" class="nav">
                <li><a href="javascript:void(0);" class="active" title=""><span><?php _e('General Settings','dpProEventCalendar'); ?></span></a></li>
                <li><a href="admin.php?page=dpProEventCalendar-admin" title=""><span><?php _e('Calendars','dpProEventCalendar'); ?></span></a></li>
                <li><a href="edit.php?post_type=pec-events" title=""><span><?php _e('Events','dpProEventCalendar'); ?></span></a></li>
                <li><a href="admin.php?page=dpProEventCalendar-special" title=""><span><?php _e('Special Dates','dpProEventCalendar'); ?></span></a></li>
                <li><a href="admin.php?page=dpProEventCalendar-custom-shortcodes" title=""><span><?php _e('Custom Shortcodes','dpProEventCalendar'); ?></span></a></li>
            </ul>
            
            <div class="clear"></div>
		</div>     
        
        <div id="rightSide">
        	<div id="menu_general_settings">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h5><?php _e('General Settings','dpProEventCalendar'); ?></h5>
                            <span></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper">
                
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('User Roles:','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name='dpProEventCalendar_options[user_roles][]' multiple="multiple" class="multiple">
                                    	<option value=""><?php _e('None','dpProEventCalendar'); ?></option>
                                       <?php 
									   $user_roles = '';
                                       $editable_roles = get_editable_roles();

								       foreach ( $editable_roles as $role => $details ) {
								           $name = translate_user_role($details['name'] );
								           if(esc_attr($role) == "administrator" || esc_attr($role) == "subscriber") { continue; }
										   if ( in_array($role, $dpProEventCalendar['user_roles']) ) // preselect specified role
								               $user_roles .= "\n\t<option selected='selected' value='" . esc_attr($role) . "'>$name</option>";
								           else
								               $user_roles .= "\n\t<option value='" . esc_attr($role) . "'>$name</option>";
								       }
									   echo $user_roles;
									   ?>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the user role that will manage the plugin.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Events Slug:','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" value="<?php echo $dpProEventCalendar['events_slug']?>" name='dpProEventCalendar_options[events_slug]' class="large-text"/>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Introduce the events URL slug. Be sure that there is not any other post type using it already. <br>(Default: pec-events)','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Custom CSS:','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <textarea name='dpProEventCalendar_options[custom_css]' rows="10"><?php echo $dpProEventCalendar['custom_css']?></textarea>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Add your custom CSS code.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('RTL Support','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" value="1" <?php echo ($dpProEventCalendar['rtl_support'] ? "checked='checked'" : "")?> name='dpProEventCalendar_options[rtl_support]' class="checkbox"/>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Add RTL support for the calendars.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Exclude Events From Search?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" value="1" <?php echo ($dpProEventCalendar['exclude_from_search'] ? "checked='checked'" : "")?> name='dpProEventCalendar_options[exclude_from_search]' class="checkbox"/>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Whether to exclude events from front end search results.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>

                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
	
    <p align="right">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>

                    
</div> <!--end of float wrap -->


<?php	
}
function dpProEventCalendar_register_mysettings() { // whitelist options
  register_setting( 'dpProEventCalendar-group', 'dpProEventCalendar_options', 'dpProEventCalendar_validate' );
}

function dpProEventCalendar_validate($input) {
	global $dpProEventCalendar;
	
	//if ( isset($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'dpProEventCalendar-settings') > 0) ) {
		//return $input;
	//}
	//die(print_r($input));
	//die(print_r($input));
	if(!$input['rtl_support']) 
		$input['rtl_support'] = 0;
	
	if(!$input['exclude_from_search']) 
		$input['exclude_from_search'] = 0;
		
	$input = dpProEventCalendar_array_merge($dpProEventCalendar, $input);
    return $input;
}

function dpProEventCalendar_array_merge($paArray1, $paArray2)
{
    if (!is_array($paArray1) or !is_array($paArray2)) { return $paArray2; }
    foreach ($paArray2 AS $sKey2 => $sValue2)
    {
		if($sKey2 == "user_roles") {
			$paArray1[$sKey2] = array(); 	
		}
        $paArray1[$sKey2] = dpProEventCalendar_array_merge(@$paArray1[$sKey2], $sValue2);
    }
    return $paArray1;
}
?>