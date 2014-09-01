<?php
// action and filter Hooks
register_activation_hook(__FILE__, 'gf_custom_styles_add_defaults');
register_uninstall_hook(__FILE__, 'gf_custom_styles_delete_plugin_options');
add_action('admin_init', 'gf_custom_styles_init' );
add_filter( 'plugin_action_links', 'gf_custom_styles_plugin_action_links', 10, 2 );

// delete options entries when plugin deactivated AND deleted
function gf_custom_styles_delete_plugin_options() {
	delete_option('gf_custom_styles_options');
}

// define default options
function gf_custom_styles_add_defaults() {
	$tmp = get_option('gf_custom_styles_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('gf_custom_styles_options');
		$arr = array(	"chk_button1" => "1",
						"chk_button3" => "1",
						"textarea_one" => "GF custpm styles textarea one.",
						"txt_one" => "Enter whatever you like here..",
						"drp_select_box" => "four",
						"chk_default_options_db" => "",
						"input_border_color" => "",
						"submit_button_bg_color" => "",
						"submit_button_border_color" => "",
						"ajax_spinner_url" => $ajax_spinner_url

		);
		update_option('gf_custom_styles_options', $arr);
	}
}

function gf_custom_styles_init(){
	register_setting( 'gf_custom_styles_plugin_options', 'gf_custom_styles_options', 'gf_custom_styles_validate_options' );
}

	//Define custom gforms ajax spinner url

	add_filter("gform_ajax_spinner_url", "gf_custom_styles_ajax_spinner", 10, 2);
function gf_custom_styles_ajax_spinner($image_src, $form){

    return $ajax_spinner_url;

}

// render options form
function gf_custom_styles_render_form() {
	?>
	<div class="wrap">

		<div class="icon32" id="icon-options-general"><br></div>
		<p></p>

		<form method="post" action="options.php">
			<?php settings_fields('gf_custom_styles_plugin_options'); ?>
			<?php $options = get_option('gf_custom_styles_options'); ?>

			<table class="form-table">

			<!-- TODO: get gforms here and return array -->
				<tr>
					<th scope="row"><?php _e('Select your form'); ?></th>
					<td>
						<select name='gf_custom_styles_options[drp_select_box]'>
							<option value='some form' <?php selected('one', $options['drp_select_box']); ?>>some form</option>
							<option value='another form' <?php selected('two', $options['drp_select_box']); ?>>another form</option>
							<option value='third form' <?php selected('three', $options['drp_select_box']); ?>>third form</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Input border color'); ?></th>
					<td>
						<input name="gf_custom_styles_options[input_border_color]" type="text" value="<?php echo $options['input_border_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Input border radius'); ?></th>
					<td>
						<select name='gf_custom_styles_options[drp_select_box]'>
							<option value='0px' <?php selected('0px', $options['drp_select_box']); ?>>0px</option>
							<option value='1px' <?php selected('1px', $options['drp_select_box']); ?>>1px</option>
							<option value='2px' <?php selected('2px', $options['drp_select_box']); ?>>2px</option>
							<option value='3px' <?php selected('3px', $options['drp_select_box']); ?>>3px</option>
							<option value='4px' <?php selected('4px', $options['drp_select_box']); ?>>4px</option>
							<option value='5px' <?php selected('5px', $options['drp_select_box']); ?>>5px</option>
							<option value='6px' <?php selected('6px', $options['drp_select_box']); ?>>6px</option>
							<option value='7px' <?php selected('7px', $options['drp_select_box']); ?>>7px</option>
							<option value='8px' <?php selected('8px', $options['drp_select_box']); ?>>8px</option>
							<option value='9px' <?php selected('9px', $options['drp_select_box']); ?>>9px</option>
							<option value='10px' <?php selected('10px', $options['drp_select_box']); ?>>10px</option>
							<option value='11px' <?php selected('11px', $options['drp_select_box']); ?>>11px</option>
							<option value='12px' <?php selected('12px', $options['drp_select_box']); ?>>12px</option>
							<option value='13px' <?php selected('13px', $options['drp_select_box']); ?>>13px</option>
							<option value='14px' <?php selected('14px', $options['drp_select_box']); ?>>14px</option>
							<option value='15px' <?php selected('15px', $options['drp_select_box']); ?>>15px</option>
						</select>
					</td>
				</tr>

				<tr>

					<th scope="row">
					<?php _e('Submit button background color'); ?></th>
					<td>
						<input name="gf_custom_styles_options[submit_button_bg_color]" type="text" value="<?php echo $options['submit_button_bg_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Submit button border color'); ?></th>
					<td>
						<input name="gf_custom_styles_options[submit_button_border_color]" type="text" value="<?php echo $options['submit_button_border_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Submit button border radius'); ?></th>
					<td>
						<select name='gf_custom_styles_options[drp_select_box]'>
							<option value='0px' <?php selected('0px',   $options['drp_select_box']); ?>>0px</option>
							<option value='1px' <?php selected('1px',   $options['drp_select_box']); ?>>1px</option>
							<option value='2px' <?php selected('2px',   $options['drp_select_box']); ?>>2px</option>
							<option value='3px' <?php selected('3px',   $options['drp_select_box']); ?>>3px</option>
							<option value='4px' <?php selected('4px',   $options['drp_select_box']); ?>>4px</option>
							<option value='5px' <?php selected('5px',   $options['drp_select_box']); ?>>5px</option>
							<option value='6px' <?php selected('6px',   $options['drp_select_box']); ?>>6px</option>
							<option value='7px' <?php selected('7px',   $options['drp_select_box']); ?>>7px</option>
							<option value='8px' <?php selected('8px',   $options['drp_select_box']); ?>>8px</option>
							<option value='9px' <?php selected('9px',   $options['drp_select_box']); ?>>9px</option>
							<option value='10px' <?php selected('10px', $options['drp_select_box']); ?>>10px</option>
							<option value='11px' <?php selected('11px', $options['drp_select_box']); ?>>11px</option>
							<option value='12px' <?php selected('12px', $options['drp_select_box']); ?>>12px</option>
							<option value='13px' <?php selected('13px', $options['drp_select_box']); ?>>13px</option>
							<option value='14px' <?php selected('14px', $options['drp_select_box']); ?>>14px</option>
							<option value='15px' <?php selected('15px', $options['drp_select_box']); ?>>15px</option>
						</select>
					</td>
				</tr>

				<!-- textboxes -->
				<tr>
					<th scope="row"><?php _e('Custom class'); ?></th>
					<td>
						<input type="text" size="10" name="gf_custom_styles_options[txt_one]" value="<?php echo $options['txt_one']; ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Custom AJAX spinner'); ?></th>
					<td>
						<input type="text" size="10" name="gf_custom_styles_options[ajax_spinner_url]" value="<?php if ( isset($options['ajax_spinner_url']) ) { echo $options['ajax_spinner_url']; } ?>" />
						<?php _e('Paste the url of a custom loader/spinner image here.'); ?>
					</td>
				</tr>

				<!-- checkbox options -->
				<tr valign="top">
					<th scope="row"><?php _e('General form styling options'); ?></th>
					<td>
						<!-- First checkbox button -->
						<label><input name="gf_custom_styles_options[chk_button1]" type="checkbox" value="1" <?php if (isset($options['chk_button1'])) { checked('1', $options['chk_button1']); } ?> /><?php _e('Show input field borders'); ?></label><br />

						<!-- Second checkbox button -->
						<label><input name="gf_custom_styles_options[chk_button2]" type="checkbox" value="1" <?php if (isset($options['chk_button2'])) { checked('1', $options['chk_button2']); } ?> /><?php _e('Enable form background styles'); ?></label><br />

						<!-- Third checkbox button -->
						<label><input name="gf_custom_styles_options[chk_button3]" type="checkbox" value="1" <?php if (isset($options['chk_button3'])) { checked('1', $options['chk_button3']); } ?> /><?php _e('Disable all custom styles on mobile devices'); ?></label><br />

					</td>
				</tr>

						<!-- custom css -->
				<tr>
					<th scope="row"><?php _e('Custom CSS'); ?></th>
					<td>
						<textarea name="gf_custom_styles_options[textarea_one]" rows="7" cols="50" type='textarea'><?php echo $options['textarea_one']; ?></textarea><br /><span style="color:#666666;margin-left:2px;"><em><?php _e('This CSS will only load on the selected form'); ?></em>.</span>
					</td>
				</tr>

			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

	</div>
	<?php
}

// Sanitize and validate input.
function gf_custom_styles_validate_options($input) {

	 // strip html from textboxes

	$input['textarea_one'] =  wp_filter_nohtml_kses($input['textarea_one']);

	// Sanitize textarea input (strip html tags, and escape characters)

	$input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']);

	// Sanitize textbox input (strip html tags, and escape characters)

	return $input;
}

// Display settings link

function gf_custom_styles_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$gf_custom_styles_links = '<a href="'.get_admin_url().'admin.php?page=gf-custom-styles">'.__('Settings').'</a>';

		// make the 'Settings' link appear first

		array_unshift( $links, $gf_custom_styles_links );

	}

	return $links;
}

// Just here for reference/example

add_filter( "the_content", "gf_custom_styles_add_content" );

function gf_custom_styles_add_content($text) {

	$options = get_option('gf_custom_styles_options');
	$select = $options['drp_select_box'];
	$text = "<p style=\"color: #777;border:1px dashed #999; padding: 6px;\">Select box plugin option is: {$select}</p>{$text}";

	return $text;

}