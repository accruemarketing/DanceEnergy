<div class="wrap <?php echo $this -> pre; ?> slideshow">
	<h2><?php _e('Save Multiple Slides', $this -> plugin_name); ?></h2>
	
	<?php if (!empty($errors)) : ?>
		<div class="slideshow_error">
			<ul>
				<?php foreach ($errors as $error) : ?>
					<li><?php echo $error; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="Slide_mediaupload"><?php _e('Choose Images', $this -> plugin_name); ?></label></th>
					<td>
						<input class="button button-secondary" type="button" name="Slide_mediaupload" value="<?php _e('Choose Files', $this -> plugin_name); ?>" id="Slide_mediaupload" />
						<span class="howto"><?php _e('Upload/choose images from the media gallery. Ctrl/Shift + Click to choose multiple.', $this -> plugin_name); ?></span>
						
						<div id="Slide_mediaslides" style="display:none;">
							<!-- Slides go here -->
							<table class="form-table" id="Slide_mediaslides_table">
								<tbody>
								
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for=""><?php _e('Galleries', $this -> plugin_name); ?></label></th>
					<td>
						<?php if ($galleries = $this -> Gallery -> select()) : ?>
							<label style="font-weight:bold"><input onclick="jqCheckAll(this,'','Slide[galleries]');" type="checkbox" name="checkboxall" value="checkboxall" id="checkboxall" /> <?php _e('Select All', $this -> plugin_name); ?></label><br/>
							<?php foreach ($galleries as $gallery_id => $gallery_title) : ?>
								<label><input type="checkbox" name="Slide[galleries][]" value="<?php echo $gallery_id; ?>" id="Slide_galleries_<?php echo $gallery_id; ?>" /> <?php echo __($gallery_title); ?></label><br/>
							<?php endforeach; ?>
						<?php else : ?>
							<span class="error"><?php _e('No galleries are available.', $this -> plugin_name); ?></span>
						<?php endif; ?>
						<span class="howto"><?php _e('Choose the galleries to add these slides to.', $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
	
		<p class="submit">
			<input type="submit" name="save" value="<?php _e('Save Multiple Slides', $this -> plugin_name); ?>" class="button button-primary" />
		</p>
	</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	var file_frame;
	
	jQuery('#Slide_mediaupload').live('click', function( event ){
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if (file_frame) {
			file_frame.open();
			return;
		}
		
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: '<?php _e('Upload Slides', $this -> plugin_name); ?>',
			button: {
				text: '<?php _e('Select Images as Slides', $this -> plugin_name); ?>',
			},
			multiple: true  // Set to true to allow multiple files to be selected
		});
		
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		
			var selection = file_frame.state().get('selection');
			
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				
				var attachment_html = '<tr id="Slide_mediaupload_row_' + attachment.id + '">';
				attachment_html += '<th style="width:100px; vertical-align:top;"><a href="" class="colorbox" onclick="jQuery.colorbox({href:\'' + attachment.url + '\'}); return false;"><img style="width:100px;" class="dropshadow" src="' + attachment.sizes.thumbnail.url + '" /></th>';
				attachment_html += '<td>';
				attachment_html += '<label><?php _e('Title:', $this -> plugin_name); ?> <input class="widefat" type="text" value="' + attachment.title + '" name="Slide[slides][' + attachment.id + '][title]" /></label>';
				attachment_html += '<label><?php _e('Description:', $this -> plugin_name); ?> <textarea class="widefat" rows="3" cols="100%" name="Slide[slides][' + attachment.id + '][description]" /></label>';
				attachment_html += '<input class="widefat" readonly="readonly" type="text" value="' + attachment.url + '" name="Slide[slides][' + attachment.id + '][url]" />';
				attachment_html += '<input type="hidden" value="' + attachment.id + '" name="Slide[slides][' + attachment.id + '][attachment_id]" />';
				attachment_html += '</td>';
				attachment_html += '<td style="vertical-align:bottom;"><input onclick="if (confirm(\'<?php echo __('Are you sure you want to remove this slide?', $this -> plugin_name); ?>\')) { jQuery(\'#Slide_mediaupload_row_' + attachment.id + '\').remove(); } return false;" class="button button-secondary button-small" type="button" name="remove" value="<?php echo __('Remove', $this -> plugin_name); ?>" id="remove' + attachment.id + '" /></td>';
				attachment_html += '</tr>';
				
				jQuery('#Slide_mediaslides').show();
				jQuery('#Slide_mediaslides_table tbody').append(attachment_html);
			});
		});
		
		// Finally, open the modal
		file_frame.open();
	});
});
</script>