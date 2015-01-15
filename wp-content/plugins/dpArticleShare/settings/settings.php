<?php // Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'dpArticleShare_settings');
  add_action('admin_init', 'dpArticleShare_register_mysettings'); 
} 

// function for adding settings page to wp-admin
function dpArticleShare_settings() {
	global $dpArticleShare, $current_user;

	if(!is_array($dpArticleShare['user_roles'])) { $dpArticleShare['user_roles'] = array(); }
	if(!in_array(dpArticleShare_get_user_role(), $dpArticleShare['user_roles']) && dpArticleShare_get_user_role() != "administrator" && !is_super_admin($current_user->ID)) { return; }
    // Add a new submenu under Options:
	add_menu_page( 'Article Share', 'Article Share', 'edit_posts','dpArticleShare-settings', 'dpArticleShare_settings_page', dpArticleShare_plugin_url( 'images/dpArticleShare_icon.gif' ), 138 );
}

function dpArticleShare_get_user_role() {
	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	return $user_role;
}

include(dirname(__FILE__) . '/article-share-meta.php');

// This function displays the page content for the Settings submenu
function dpArticleShare_settings_page() {
global $dpArticleShare, $wpdb;

include(dirname(__FILE__) . '/../classes/base.class.php');
?>
<script type="text/javascript">
	function DP_ChangeMenu(id, menu) {
		jQuery('#menu li a').removeClass('active');
		jQuery(menu).addClass('active');
		
		createCookie('dpArticleShare_last_option', jQuery(menu).attr('class').replace("active", ""));
		
		jQuery('#rightSide').children().each(function(i) {
			if(jQuery(this).css('display') != 'none') {
				jQuery(this).fadeOut('fast', function() { 
					jQuery('#'+id).fadeIn('fast');
				});
			}
		});
		
	}
	
	function createCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}
	
	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
	
	function eraseCookie(name) {
		createCookie(name,"",-1);
	}
	
	jQuery(document).ready(function() {
		
		if(readCookie('dpArticleShare_last_option')) {
			jQuery('.'+readCookie('dpArticleShare_last_option')).trigger('click');
		}
	});
</script>
<div class="wrap" style="clear:both;" id="dp_options">

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#sort-table").tableDnD();
	});
</script>

<h2></h2>
<?php $url = dpArticleShare_admin_url( array( 'page' => 'dpArticleShare-admin' ) );?>

<form method="post" action="options.php" enctype="multipart/form-data">
<?php settings_fields('dpArticleShare-group'); ?>
<div style="clear:both;"></div>
 <!--end of poststuff --> 
	
    <div id="dp_ui_content_article_share" class="dp_admin_article_share">
    	<input type="hidden" name="dpArticleShare_options[icons_name]" value="1" />
        <div id="leftSide">
        	<div id="dp_logo"></div>
            <p>
                Version: <?php echo DP_ARTICLE_SHARE_VER?><br />
            </p>
            <ul id="menu" class="nav">
                <li><a href="javascript:void(0);" class="active menu_general_settings" title="" onclick="DP_ChangeMenu('menu_general_settings', this);"><span><?php _e('General Settings','dpArticleShare'); ?></span></a></li>
                <li><a href="javascript:void(0);" title="" class="menu_icons" onclick="DP_ChangeMenu('menu_icons', this);"><span><?php _e('Icons','dpArticleShare'); ?></span></a></li>
                <li><a href="javascript:void(0);" title="" class="menu_translations" onclick="DP_ChangeMenu('menu_translations', this);"><span><?php _e('Translations','dpArticleShare'); ?></span></a></li>
                <li><a href="javascript:void(0);" title="" class="menu_stats" onclick="DP_ChangeMenu('menu_stats', this);"><span><?php _e('Stats','dpArticleShare'); ?></span></a></li>
                <li><a href="javascript:void(0);" title="" class="menu_help" onclick="DP_ChangeMenu('menu_help', this);"><span><?php _e('Help','dpArticleShare'); ?></span></a></li>
            </ul>
            
            <div class="clear"></div>
		</div>     
        
        <div id="rightSide">
        	<div id="menu_general_settings">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h5><?php _e('General Settings','dpArticleShare'); ?></h5>
                            <span></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper">
                
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('User Roles:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name='dpArticleShare_options[user_roles][]' multiple="multiple" class="multiple">
                                    	<option value=""><?php _e('None','dpArticleShare'); ?></option>
                                       <?php 
									   $user_roles = '';
                                       $editable_roles = get_editable_roles();

								       foreach ( $editable_roles as $role => $details ) {
								           $name = translate_user_role($details['name'] );
								           if(esc_attr($role) == "administrator" || esc_attr($role) == "subscriber") { continue; }
										   if ( in_array($role, $dpArticleShare['user_roles']) ) // preselect specified role
								               $user_roles .= "\n\t<option selected='selected' value='" . esc_attr($role) . "'>$name</option>";
								           else
								               $user_roles .= "\n\t<option value='" . esc_attr($role) . "'>$name</option>";
								       }
									   echo $user_roles;
									   ?>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the user role that will manage the plugin.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Scope:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name='dpArticleShare_options[scope][]' multiple="multiple" class="multiple">
                                        <option value="home" <?php echo (is_array($dpArticleShare['scope']) && in_array('home', $dpArticleShare['scope']) ? 'selected="selected"' : '') ?>><?php _e('Homepage','dpArticleShare'); ?></option>
                                       <?php 
									   $ptype_options = '';
                                       $post_types = get_post_types( array('public' => true), 'names' ); 

								       foreach ( $post_types as $ptype ) {
										   if(esc_attr($ptype) == "attachment") { continue; }
										   if ( is_array($dpArticleShare['scope']) && in_array($ptype, $dpArticleShare['scope']) ) // preselect specified role
								               $ptype_options .= "\n\t<option selected='selected' value='" . esc_attr($ptype) . "'>$ptype</option>";
								           else
								               $ptype_options .= "\n\t<option value='" . esc_attr($ptype) . "'>$ptype</option>";
								       }
									   echo $ptype_options;
									   ?>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the sections and post types to display the plugin.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Position:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name='dpArticleShare_options[position]'>
                                    	<option value="vertical" <?php echo ($dpArticleShare['position'] == 'vertical' ? 'selected="selected"' : '')?>><?php _e('Vertical','dpArticleShare'); ?></option>
                                        <option value="vertical-inside" <?php echo ($dpArticleShare['position'] == 'vertical-inside' ? 'selected="selected"' : '')?>><?php _e('Vertical Inside Content','dpArticleShare'); ?></option>
                                        <option value="horizontal-top" <?php echo ($dpArticleShare['position'] == 'horizontal-top' ? 'selected="selected"' : '')?>><?php _e('Horizontal Top','dpArticleShare'); ?></option>
                                        <option value="horizontal-bottom" <?php echo ($dpArticleShare['position'] == 'horizontal-bottom' ? 'selected="selected"' : '')?>><?php _e('Horizontal Bottom','dpArticleShare'); ?></option>
                                        <option value="horizontal-top-bottom" <?php echo ($dpArticleShare['position'] == 'horizontal-top-bottom' ? 'selected="selected"' : '')?>><?php _e('Horizontal Top and Bottom','dpArticleShare'); ?></option>
                                        <option value="fixed-left" <?php echo ($dpArticleShare['position'] == 'fixed-left' ? 'selected="selected"' : '')?>><?php _e('Fixed Left','dpArticleShare'); ?></option>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Position for the icons bar inside the post/page content.','dpArticleShare'); ?></div>
                                
                                <div class="forminp">
                                    <?php _e('Vertical Offset: ','dpArticleShare'); ?> <input type="number" min="-1000" max="1000" maxlength="4" style="width: 70px;" name="dpArticleShare_options[vertical_offset]" value="<?php echo $dpArticleShare['vertical_offset']?>" />px <?php _e('(optional)','dpArticleShare'); ?>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set the offset for the vertical position.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Skin:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name='dpArticleShare_options[skin]'>
                                    	<option value="light" <?php echo ($dpArticleShare['skin'] == 'light' ? 'selected="selected"' : '')?>><?php _e('Light','dpArticleShare'); ?></option>
                                        <option value="dark" <?php echo ($dpArticleShare['skin'] == 'dark' ? 'selected="selected"' : '')?>><?php _e('Dark','dpArticleShare'); ?></option>
                                        <option value="color" <?php echo ($dpArticleShare['skin'] == 'color' ? 'selected="selected"' : '')?>><?php _e('Color','dpArticleShare'); ?></option>
                                        <option value="compact" <?php echo ($dpArticleShare['skin'] == 'compact' ? 'selected="selected"' : '')?>><?php _e('Compact','dpArticleShare'); ?></option>
                                        <option value="flat" <?php echo ($dpArticleShare['skin'] == 'flat' ? 'selected="selected"' : '')?>><?php _e('Flat','dpArticleShare'); ?></option>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the skin.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show Counter:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="checkbox" value="1" name='dpArticleShare_options[show_counter]' class="checkbox" <?php echo ($dpArticleShare['show_counter'] ? 'checked="checked"' : '')?> />
                                </div>
                                <div class="desc"><?php _e('Enables counter for the social buttons. (Some buttons don\'t have this feature available)','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Counter Position:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <select name='dpArticleShare_options[counter_position]'>
                                    	<option value="bottom" <?php echo ($dpArticleShare['counter_position'] == 'bottom' ? 'selected="selected"' : '')?>><?php _e('Bottom','dpArticleShare'); ?></option>
                                        <option value="right" <?php echo ($dpArticleShare['counter_position'] == 'right' ? 'selected="selected"' : '')?>><?php _e('Right','dpArticleShare'); ?></option>
                                    </select>
                                </div>
                                <div class="desc"><?php _e('Select the counter position','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show Tooltips:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="checkbox" value="1" name='dpArticleShare_options[show_tooltips]' class="checkbox" <?php echo ($dpArticleShare['show_tooltips'] ? 'checked="checked"' : '')?> />
                                </div>
                                <div class="desc"><?php _e('Enables Tooltips.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Limit Icons:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="checkbox" value="1" name='dpArticleShare_options[limit_icons]' class="checkbox" <?php echo ($dpArticleShare['limit_icons'] ? 'checked="checked"' : '')?> /> 
                                    <input type="number" min="0" max="99" name="dpArticleShare_options[limit_icons_number]" style="width: 50px;" value="<?php echo $dpArticleShare['limit_icons_number']?>" />
                                </div>
                                <div class="desc"><?php _e('Limits the number of icons to be displayed in the bar and adds a "plus" button to see the rest of the icons in a modal.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Twitter Handle:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="text" name='dpArticleShare_options[twitter_handle]' value="<?php echo $dpArticleShare['twitter_handle']?>" />
                                </div>
                                <div class="desc"><?php _e('Twitter username to use with the share button','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-checkbox no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Enable Bit.ly:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="checkbox" value="1" name='dpArticleShare_options[bitly_enabled]' class="checkbox" <?php echo ($dpArticleShare['bitly_enabled'] ? 'checked="checked"' : '')?> />
                                </div>
                                <div class="desc"><?php _e('Enables bit.ly as url shortener service. If disabled, goo.gl will be used instead.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-w no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Bit.ly API Key:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="text" name='dpArticleShare_options[bitly_api_key]' value="<?php echo $dpArticleShare['bitly_api_key']?>" />
                                </div>
                                <div class="desc"><?php _e('Login in bit.ly, go to https://bitly.com/a/settings/advanced and click "Show legacy API key" to see the API key','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Bit.ly Login:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[bitly_login]' value="<?php echo $dpArticleShare['bitly_login']?>" />
                                </div>
                                <div class="desc"><?php _e('Your bit.ly username','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-checkbox no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Disqus:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="checkbox" value="1" name='dpArticleShare_options[disqus_enabled]' class="checkbox" <?php echo ($dpArticleShare['disqus_enabled'] ? 'checked="checked"' : '')?> />
                                </div>
                                <div class="desc"><?php _e('Enables Disqus counter for the comments icon','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-w no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Disqus API Key:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                                <input type="text" name='dpArticleShare_options[disqus_api_key]' value="<?php echo $dpArticleShare['disqus_api_key']?>" />
                                </div>
                                <div class="desc"><?php _e('Requires a registered DISQUS API application. Create one (free) at http://disqus.com/api/applications/','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Disqus Shortname:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[disqus_shortname]' value="<?php echo $dpArticleShare['disqus_shortname']?>" />
                                </div>
                                <div class="desc"><?php _e('Your shortname can be found on your forum\'s Settings > General page','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Custom CSS:','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <textarea name='dpArticleShare_options[custom_css]' rows="10"><?php echo $dpArticleShare['custom_css']?></textarea>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Add your custom CSS code.','dpArticleShare'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            
            <div id="menu_icons" style="display:none;">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h5><?php _e('Icons','dpArticleShare'); ?></h5>
                            <span><?php _e('Drag & Drop the rows to set the order of the icons in the bar.','dpArticleShare'); ?></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper"  style="padding-top:20px;">
                	<table class="widefat" cellpadding="0" cellspacing="0" id="sort-table">
                        <thead>
                            <tr style="cursor:default !important;">
                                <th><?php _e('Active','dpArticleShare'); ?></th>
                                <th><?php _e('Name','dpArticleShare'); ?></th>
                             </tr>
                        </thead>
                        <tbody>
                            <?php 
							$social_icons = array(
						   		"twitter" => array( "active" => 1, "name" => "twitter" ),
								"facebook" => array( "active" => 1, "name" => "facebook" ),
								"google" => array( "active" => 1, "name" => "google" ),
								"linkedin" => array( "active" => 1, "name" => "linkedin" ),
								"pinterest" => array( "active" => 1, "name" => "pinterest" ),
								"delicious" => array( "active" => 0, "name" => "delicious" ),
								"stumbleupon" => array( "active" => 0, "name" => "stumbleupon" ),
								"digg" => array( "active" => 0, "name" => "digg" ),
								"tumblr" => array( "active" => 0, "name" => "tumblr" ),
								"reddit" => array( "active" => 0, "name" => "reddit" ),
								"blogger" => array( "active" => 0, "name" => "blogger" ),
								"buffer" => array( "active" => 0, "name" => "buffer" ),
								"vk" => array( "active" => 0, "name" => "vk" ),
								"email" => array( "active" => 0, "name" => "email" ),
								"print" => array( "active" => 1, "name" => "print" ),
								"comments" => array( "active" => 1, "name" => "comments" )
						   );
							foreach(array_merge($dpArticleShare['social_icons_arr'], $social_icons) as $key=>$value) { ?>
                            <tr id="<?php echo $key?>" class="order-tableDnD">
                            	<input type="hidden" name="dpArticleShare_options[social_icons_arr][<?php echo $key?>][name]" value="<?php echo $dpArticleShare['social_icons_arr'][$key]['name']?>" />
                                <td width="10"><input type="checkbox" name="dpArticleShare_options[social_icons_arr][<?php echo $key?>][active]" value="1" <?php if($dpArticleShare['social_icons_arr'][$key]['active']) {?> checked="checked" <?php }?> /></td>
                                <td class="social_btn_drag"><span class="btn-<?php echo $key?>"><?php echo ucwords(str_replace("_", " ", $key))?></span></td>
                            </tr>
                            <?php }?>
                        </tbody>
                        <tfoot>
                            <tr style="cursor:default !important;">
                                <th><?php echo __( 'Active', 'dpArticleShare' )?></th>
                                <th><?php echo __( 'Name', 'dpArticleShare' )?></th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="preview-text">
                    <?php
					if($dpArticleShare['position'] == 'vertical') {
						$dpArticleShare['position'] = 'vertical-inside';	
					}
					if($dpArticleShare['position'] != 'horizontal-bottom') {
						echo do_shortcode('[dpArticleShare]');
					}
					?>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pellentesque nisl ut mollis fermentum. Etiam condimentum sem tellus, vel tincidunt ipsum porttitor nec. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae lectus massa. Phasellus in aliquet mauris. In et est congue, laoreet sem sit amet, gravida enim. Nunc vel lacus congue, hendrerit purus id, dignissim arcu. Fusce malesuada hendrerit accumsan. Proin et dui vitae libero laoreet viverra sit amet id libero. Phasellus tortor velit, convallis lacinia pharetra sed, ullamcorper facilisis risus.</p>
                    <p>Maecenas non risus sit amet arcu faucibus elementum scelerisque id eros. Vestibulum malesuada vestibulum augue, eget rutrum velit luctus sit amet. Suspendisse non lectus vulputate, pretium orci quis, tempus nibh. Nulla sit amet est ut libero sagittis venenatis id eu lorem. Aliquam sed fermentum diam, eu egestas sapien. Fusce aliquam pellentesque enim, ut scelerisque neque tempor ut. Nam pretium eros vel lacinia vestibulum. Curabitur sagittis mi vel libero viverra interdum. Integer vel lorem consectetur, pellentesque est ac, eleifend tellus. Maecenas eleifend orci sed iaculis blandit. Maecenas eros odio, auctor a est vel, interdum convallis mi. Maecenas metus dui, congue accumsan augue et, rhoncus eleifend lacus. Vivamus ut magna ut nibh laoreet interdum ac in arcu. Ut semper, sem ut dignissim interdum, mauris risus tristique urna, malesuada viverra metus arcu quis lectus.</p>
                    <p>Nam nec augue porta, tempor eros faucibus, aliquet dolor. Proin et ligula vel mi porttitor aliquam non vitae tellus. Aenean sed neque velit. Sed quam erat, lacinia a mattis vel, dictum et enim. Nullam malesuada vulputate quam, ut hendrerit augue. Nullam malesuada, libero vitae dignissim rhoncus, ante tellus laoreet neque, et malesuada velit arcu et diam. Vestibulum tempus pulvinar erat at convallis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
                    <p>Aenean sagittis magna nec porttitor mollis. Proin consectetur sed ipsum id rutrum. Nam eleifend ut massa sit amet feugiat. Etiam semper gravida justo, vel luctus augue bibendum quis. Proin in leo elit. In sem est, viverra quis tellus id, malesuada interdum mi. Aliquam sagittis consequat gravida. Etiam blandit pharetra pretium. Maecenas porttitor semper nisl et auctor. Integer eu dapibus purus.</p>
                    <p>Nam nec augue porta, tempor eros faucibus, aliquet dolor. Proin et ligula vel mi porttitor aliquam non vitae tellus. Aenean sed neque velit. Sed quam erat, lacinia a mattis vel, dictum et enim. Nullam malesuada vulputate quam, ut hendrerit augue. Nullam malesuada, libero vitae dignissim rhoncus, ante tellus laoreet neque, et malesuada velit arcu et diam. Vestibulum tempus pulvinar erat at convallis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
                    <p>Aenean sagittis magna nec porttitor mollis. Proin consectetur sed ipsum id rutrum. Nam eleifend ut massa sit amet feugiat. Etiam semper gravida justo, vel luctus augue bibendum quis. Proin in leo elit. In sem est, viverra quis tellus id, malesuada interdum mi. Aliquam sagittis consequat gravida. Etiam blandit pharetra pretium. Maecenas porttitor semper nisl et auctor. Integer eu dapibus purus.</p>
                    <?php
					if($dpArticleShare['position'] == 'horizontal-bottom') {
						echo do_shortcode('[dpArticleShare]');
					}
					?>
                    </div>

                </div>
                
            </div>
            
            <div id="menu_translations" style="display:none;">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h5><?php _e('Translations','dpArticleShare'); ?></h5>
                            <span></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper">
                	<div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Share on','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_share_on]' value="<?php echo $dpArticleShare['i18n_share_on']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Print','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_print]' value="<?php echo $dpArticleShare['i18n_print']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email]' value="<?php echo $dpArticleShare['i18n_email']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email Body Text','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_body]' value="<?php echo $dpArticleShare['i18n_email_body']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Comments','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_comments]' value="<?php echo $dpArticleShare['i18n_comments']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('More','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_more]' value="<?php echo $dpArticleShare['i18n_more']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email Sent','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_sent]' value="<?php echo $dpArticleShare['i18n_email_sent']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email Required Fields','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_required]' value="<?php echo $dpArticleShare['i18n_email_required']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Your Name','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_your_name]' value="<?php echo $dpArticleShare['i18n_email_your_name']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Your Email','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_your_email]' value="<?php echo $dpArticleShare['i18n_email_your_email']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email To','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_to]' value="<?php echo $dpArticleShare['i18n_email_to']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email Subject','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_subject]' value="<?php echo $dpArticleShare['i18n_email_subject']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email Message','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_message]' value="<?php echo $dpArticleShare['i18n_email_message']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email Send','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_send]' value="<?php echo $dpArticleShare['i18n_email_send']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="option option-select option-w no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Email Sent By','dpArticleShare'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
	                               <input type="text" name='dpArticleShare_options[i18n_email_email_sent_by]' value="<?php echo $dpArticleShare['i18n_email_email_sent_by']?>" />
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
        	</div>
            <div id="menu_stats" style="display:none;">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h5><?php _e('Stats','dpArticleShare'); ?></h5>
                            <span><?php _e('Check the share stats for the selected post types.','dpArticleShare'); ?></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper">
                	<p>Some share stats can be cached, so in case that you want to force the update, you should use the button below. It can take some time depending the number of posts/pages that you have in WP.</p>
                	<button class="button button-large" id="share_update_counter">Update Share Counters</button>
                    <img src="<?php echo dpArticleShare_plugin_url()?>/images/loader.gif" class="dpArticleShare_Loader" alt="" />
                	<?php
					$scope_list = $dpArticleShare['scope'];
					if(in_array('home', $scope_list)) {
						$indexCompleted = array_search('home', $scope_list);
						unset($scope_list[$indexCompleted]);
					}
					
					if(!is_array($scope_list) || count($scope_list) == 0) {
						$scope_list = array('post', 'page');
					}
					
					foreach($scope_list as $scope) {
						if($scope == 'home') continue;
						$post_type_name = get_post_type_object($scope);
					?>
                	<h3><?php _e('Most Shared '.$post_type_name->labels->name,'dpArticleShare'); ?></h3>
                    
                    <table class="widefat" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr style="cursor:default !important;">
                                <th><?php _e('ID','dpArticleShare'); ?></th>
                                <th width="30%"><?php _e('Title','dpArticleShare'); ?></th>
                                <?php
                                foreach($dpArticleShare['social_icons_arr'] as $key=>$value) {
									if(!$value['active'] || $key == 'print' || $key == 'comments' || $key == 'email' || $key == 'blogger' || $key == 'digg' || $key == 'tumblr') { continue; }
								?>
                                <th><?php echo ucfirst($key)?></th>
                                <?php }?>
                                <th><?php _e('Total','dpArticleShare'); ?></th>
                             </tr>
                        </thead>
                        <tbody>
                            <?php 
							$args = array( 
								'posts_per_page' => 5, 
								'post_type'=> $scope,
								'meta_key' => 'share_total',
								'orderby' => 'meta_value_num', 
								'order' => 'DESC'
							);

							$the_query = new WP_Query($args);
							if ($the_query->have_posts()) {
								while ( $the_query->have_posts() ):
									$the_query->the_post();
							?>
                            <tr id="<?php echo $key?>" class="order-tableDnD">
                                <td width="10"><?php echo get_the_ID()?></td>
                                <td><a href="<?php echo get_permalink()?>" target="_blank"><?php the_title()?></a></td>
                                <?php
                                foreach($dpArticleShare['social_icons_arr'] as $key=>$value) {
									if(!$value['active'] || $key == 'print' || $key == 'comments' || $key == 'email' || $key == 'blogger' || $key == 'digg' || $key == 'tumblr') { continue; }
									$count = dpArticleShare_get_post_shares(get_the_ID());
								?>
                                <td><?php echo ($key == 'google' ? $count['gplus'] : $count[$key])?></td>
                                <?php }?>
                                <td><?php echo get_post_meta(get_the_ID(), 'share_total', true)?></td>
                            </tr>
                            <?php 
								endwhile;
							}?>
                        </tbody>
                        <tfoot>
                            <tr style="cursor:default !important;">
                                <th><?php echo __( 'ID', 'dpArticleShare' )?></th>
                                <th><?php echo __( 'Title', 'dpArticleShare' )?></th>
                                <?php
                                foreach($dpArticleShare['social_icons_arr'] as $key=>$value) {
									if(!$value['active'] || $key == 'print' || $key == 'comments' || $key == 'email' || $key == 'blogger' || $key == 'digg' || $key == 'tumblr') { continue; }
								?>
                                <th><?php echo ucfirst($key)?></th>
                                <?php }?>
                                <th><?php _e('Total','dpArticleShare'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <?php 
					}?>
                    
                </div>
        	</div>
            <div id="menu_help" style="display:none;">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h5><?php _e('Help','dpArticleShare'); ?></h5>
                            <span></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper">
                	<p>In case that you are having issues with this plugin, please take a look to the recommendations below or contact me directly using the contact form in <a href="http://codecanyon.net/user/DPereyra">CodeCanyon</a></p>
                    
                    <p>You can use this shortcode in your pages/posts <strong>[dpArticleShare]</strong>, especially if the icons aren't displayed automatically because some themes use a different method to display the posts content than the standard.</p>
                    <p>Another workaround would be to edit the <strong>page.php</strong> (for pages) and <strong>single.php</strong> (for posts) file in your theme and insert manually this code inside the div that you want to place the icons:</p>
                    <code>&lt;?php echo do_shortcode('[dpArticleShare]');?&gt;</code>
                    
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
function dpArticleShare_register_mysettings() { // whitelist options
  register_setting( 'dpArticleShare-group', 'dpArticleShare_options' );
}

?>