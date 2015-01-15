<?php
// This function displays the admin page content
function dpProEventCalendar_custom_shortcodes_page() {
	global $wpdb, $table_prefix;
	$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
	?>

    <div class="wrap" style="clear:both;" id="dp_options">
    
    <h2></h2>
    <div style="clear:both;"></div>
     <!--end of poststuff --> 
        <div id="dp_ui_content">
            
            <div id="leftSide">
                <div id="dp_logo"></div>
                <p>
                    Version: <?php echo DP_PRO_EVENT_CALENDAR_VER?><br />
                </p>
                <ul id="menu" class="nav">
                	<li><a href="admin.php?page=dpProEventCalendar-settings" title=""><span><?php _e('General Settings','dpProEventCalendar'); ?></span></a></li>
                    <li><a href="admin.php?page=dpProEventCalendar-admin" title=""><span><?php _e('Calendars','dpProEventCalendar'); ?></span></a></li>
                    <li><a href="edit.php?post_type=pec-events" title=""><span><?php _e('Events','dpProEventCalendar'); ?></span></a></li>
                    <li><a href="admin.php?page=dpProEventCalendar-special" title=""><span><?php _e('Special Dates','dpProEventCalendar'); ?></span></a></li>
	                <li><a href="javascript:void(0);" title="" class="active"><span><?php _e('Custom Shortcodes','dpProEventCalendar'); ?></span></a></li>
	            </ul>
                
                <div class="clear"></div>
            </div>     
            
            <div id="rightSide">
                <div id="menu_general_settings">
                    <div class="titleArea">
                        <div class="wrapper">
                            <div class="pageTitle">
                                <h2><?php _e('Custom Shortcodes','dpProEventCalendar'); ?></h2>
                                <span><?php _e('Get a calendar custom shortcode.','dpProEventCalendar'); ?></span>
                            </div>
                            
                            <div class="clear"></div>
                        </div>
                    </div>
                    
                    <div class="wrapper">
                    	<div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Calendar','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <select name="pec_custom_shortcode_calendar" id="pec_custom_shortcode_calendar" onchange="pec_updateShortcode();">
											<?php
                                            $querystr = "
                                            SELECT *
                                            FROM $table_name_calendars
                                            ORDER BY title ASC
                                            ";
                                            $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                                            foreach($calendars_obj as $calendar_key) {
                                            ?>
                                                <option value="<?php echo $calendar_key->id?>"><?php echo $calendar_key->title?></option>
                                            <?php }?>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Select a calendar','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Layout','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <select name="pec_custom_shortcode_layout" id="pec_custom_shortcode_layout" onchange="pec_updateShortcode();">
											<option value="">Default</option>
                                            <option value="upcoming">Upcoming Events</option>
                                            <option value="accordion">Accordion List</option>
                                            <option value="accordion-upcoming">Accordion Upcoming Events</option>
                                            <option value="add-event">Add Event</option>
                                            <option value="list-author">List Events by Author</option>
                                            <option value="today-events">Today Events</option>
                                            <option value="gmap-upcoming">Google Map Upcoming Events</option>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Select a layout type.','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select" id="list-authors" style="display:none;">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Authors','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <select name="pec_custom_shortcode_authors" id="pec_custom_shortcode_authors" onchange="pec_updateShortcode();">
                                            <?php 
											$blogusers = get_users('who=authors');
											foreach ($blogusers as $user) {
												echo '<option value="'.$user->ID.'">' . $user->display_name . ' ('.$user->user_nicename.')</option>';
											}?>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Select an author.','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select" id="limit-param" style="display:none;">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Limit','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <input type="number" min="1" max="99" name="pec_custom_shortcode_limit" id="pec_custom_shortcode_limit" value="5" onchange="pec_updateShortcode();" />
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Select a limit of posts to display.','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="submit">
                        
                            <span class="pec_custom_shortcode"></span> 

                            <div class="clear"></div>
                            
                            <p class="pec_custom_shortcode_help"></p>
                            
                            
                            <!--<input type="button" class="button button-large" id="pec_custom_shortcode_preview_btn" value="<?php echo __( 'Get Preview', 'dpProEventCalendar' )?>" />-->
                            
                            <div id="pec_custom_shortcode_preview"></div>
                        </div>
                    </div>
                </div>           
            </div>
        </div>
                    
</div> <!--end of float wrap -->

<script type="text/javascript">
	function pec_updateShortcode() {
		var shortcode = '[dpProEventCalendar';
		
		jQuery('#list-authors').hide();
		jQuery('#limit-param').hide();
		
		if(jQuery('#pec_custom_shortcode_calendar').val() != "") {
			shortcode += ' id="'+jQuery('#pec_custom_shortcode_calendar').val()+'"';
		}
		
		if(jQuery('#pec_custom_shortcode_layout').val() != "") {
			shortcode += ' type="'+jQuery('#pec_custom_shortcode_layout').val()+'"';
		}
		
		if(jQuery('#pec_custom_shortcode_layout').val() == "list-author") {
			jQuery('#list-authors').show();
			shortcode += ' author="'+jQuery('#pec_custom_shortcode_authors').val()+'"';
			//jQuery('.pec_custom_shortcode_help').text('<?php echo __( 'This shortcode should be implemented inside the author template of your theme.', 'dpProEventCalendar' )?>');
		} else {
			jQuery('.pec_custom_shortcode_help').text('');
		}
		
		if(jQuery('#pec_custom_shortcode_layout').val() == "upcoming" || jQuery('#pec_custom_shortcode_layout').val() == "accordion-upcoming") {
			jQuery('#limit-param').show();
			shortcode += ' limit="'+jQuery('#pec_custom_shortcode_limit').val()+'"';
		}

		shortcode += ']';
		
		jQuery('.pec_custom_shortcode').text(shortcode);
	};
	
	pec_updateShortcode();
</script>
<?php	
}
?>