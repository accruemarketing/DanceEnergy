<?php
// This function displays the admin page content
function dpProEventCalendar_eventdata_page() {
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
	                <li><a href="admin.php?page=dpProEventCalendar-custom-shortcodes" title=""><span><?php _e('Custom Shortcodes','dpProEventCalendar'); ?></span></a></li>
                    <li><a href="javascript:void(0);" title="" class="active"><span><?php _e('Display Data in Event Page','dpProEventCalendar'); ?></span></a></li>
	            </ul>
                
                <div class="clear"></div>
            </div>     
            
            <div id="rightSide">
                <div id="menu_general_settings">
                    <div class="titleArea">
                        <div class="wrapper">
                            <div class="pageTitle">
                                <h2><?php _e('Display Data in Event Page','dpProEventCalendar'); ?></h2>
                                <span><?php _e('If you want to display the event data on the event pages, you will need to edit the theme and duplicate the file single.php, then rename it to single-pec-events.php and add the code snippet in the desired place.','dpProEventCalendar'); ?></span>
                            </div>
                            
                            <div class="clear"></div>
                        </div>
                    </div>
                    
                    <div class="wrapper">
                    	<div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Date','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <span class="pec_custom_shortcode pec_small">&lt;?php echo do_shortcode('[dpProEventCalendar get="date"]') ?&gt;</span> 
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Get the event start date','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Frequency','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <span class="pec_custom_shortcode pec_small">&lt;?php echo do_shortcode('[dpProEventCalendar get="frequency"]') ?&gt;</span> 
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Get the event frequency','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Link','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <span class="pec_custom_shortcode pec_small">&lt;?php echo do_shortcode('[dpProEventCalendar get="link"]') ?&gt;</span> 
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Get the event link','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Location','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <span class="pec_custom_shortcode pec_small">&lt;?php echo do_shortcode('[dpProEventCalendar get="location"]') ?&gt;</span> 
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Get the event location','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Google Map','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <span class="pec_custom_shortcode pec_small">&lt;?php echo do_shortcode('[dpProEventCalendar get="map"]') ?&gt;</span> 
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Get the event Google Map','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="option option-select">
                            <div class="option-inner">
                                <label class="titledesc"><?php _e('Rating','dpProEventCalendar'); ?></label>
                                <div class="formcontainer">
                                    <div class="forminp">
                                        <span class="pec_custom_shortcode pec_small">&lt;?php echo do_shortcode('[dpProEventCalendar get="rating"]') ?&gt;</span> 
                                        <br>
                                    </div>
                                    <div class="desc"><?php _e('Get the event Rating','dpProEventCalendar'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>           
            </div>
        </div>
                    
</div> <!--end of float wrap -->

<script type="text/javascript">
</script>
<?php	
}
?>