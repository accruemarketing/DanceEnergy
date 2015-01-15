<!-- Technical Settings -->

<?php
	
$jsoutput = $this -> get_option('jsoutput');	
	
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="jsoutput_perslideshow"><?php _e('Javascript Output', $this -> plugin_name); ?></label></th>
			<td>
				<label><input <?php echo (empty($jsoutput) || (!empty($jsoutput) && $jsoutput == "perslideshow")) ? 'checked="checked"' : ''; ?> type="radio" name="jsoutput" value="perslideshow" id="jsoutput_perslideshow" /> <?php _e('Per Slideshow', $this -> plugin_name); ?></label>
				<label><input <?php echo (!empty($jsoutput) && $jsoutput == "footerglobal") ? 'checked="checked"' : ''; ?> type="radio" name="jsoutput" value="footerglobal" id="jsoutput_footerglobal" /> <?php _e('All in Footer', $this -> plugin_name); ?></label>
			</td>
		</tr>
	</tbody>
</table>