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
						"custom_css" => "GF custom CSS here.",
						"drp_select_box" => "four",
						"chk_default_options_db" => "",
						"input_border_color" => "",
						"input_color" => "",
						"input_border_radius" => "",
						"submit_button_bg_color" => "",
						"submit_button_color" => "",
						"submit_button_border_color" => "",
						"submit_button_border_size" => "",
						"submit_button_border_radius" => "",
						"submit_button_bbg_color" => "",
						"ajax_spinner_url" => null

		);
		update_option('gf_custom_styles_options', $arr );
	}
}

function gf_custom_styles_init(){
	register_setting( 'gf_custom_styles_plugin_options', 'gf_custom_styles_options', 'gf_custom_styles_validate_options' );
}

	add_filter("gform_ajax_spinner_url", "gf_custom_styles_ajax_spinner", 10, 2);

	/**
	 * Define custom gforms ajax spinner url
	 *
	 * @access public
	 * @param mixed $image_src
	 * @param mixed $form
	 * @return void
	 */
	function gf_custom_styles_ajax_spinner( $image_src, $form ) {

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

				<!-- General input colors -->

				<tr>
					<th scope="row"><?php _e('Input color'); ?></th>
					<td>
						<input name="gf_custom_styles_options[input_color]" type="text" value="<?php echo $options['input_color']; ?>" class="wp-color-picker-field" data-default-color="#000000" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Input border color'); ?></th>
					<td>
						<input name="gf_custom_styles_options[input_border_color]" type="text" value="<?php echo $options['input_border_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Input background color'); ?></th>
					<td>
						<input name="gf_custom_styles_options[input_background_color]" type="text" value="<?php echo $options['input_background_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Input border size'); ?></th>
					<td>
						<select name='gf_custom_styles_options[input_border_size]'>
							<option value='0px' <?php selected('0px', $options['input_border_size']); ?>>0px</option>
							<option value='1px' <?php selected('1px', $options['input_border_size']); ?>>1px</option>
							<option value='2px' <?php selected('2px', $options['input_border_size']); ?>>2px</option>
							<option value='3px' <?php selected('3px', $options['input_border_size']); ?>>3px</option>
							<option value='4px' <?php selected('4px', $options['input_border_size']); ?>>4px</option>
							<option value='5px' <?php selected('5px', $options['input_border_size']); ?>>5px</option>
							<option value='6px' <?php selected('6px', $options['input_border_size']); ?>>6px</option>
							<option value='7px' <?php selected('7px', $options['input_border_size']); ?>>7px</option>
							<option value='8px' <?php selected('8px', $options['input_border_size']); ?>>8px</option>
							<option value='9px' <?php selected('9px', $options['input_border_size']); ?>>9px</option>
							<option value='10px' <?php selected('10px', $options['input_border_size']); ?>>10px</option>
							<option value='11px' <?php selected('11px', $options['input_border_size']); ?>>11px</option>
							<option value='12px' <?php selected('12px', $options['input_border_size']); ?>>12px</option>
							<option value='13px' <?php selected('13px', $options['input_border_size']); ?>>13px</option>
							<option value='14px' <?php selected('14px', $options['input_border_size']); ?>>14px</option>
							<option value='15px' <?php selected('15px', $options['input_border_size']); ?>>15px</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Input border radius'); ?></th>
					<td>
						<select name='gf_custom_styles_options[input_border_radius]'>
							<option value='0px' <?php selected('0px', $options['input_border_radius']); ?>>0px</option>
							<option value='1px' <?php selected('1px', $options['input_border_radius']); ?>>1px</option>
							<option value='2px' <?php selected('2px', $options['input_border_radius']); ?>>2px</option>
							<option value='3px' <?php selected('3px', $options['input_border_radius']); ?>>3px</option>
							<option value='4px' <?php selected('4px', $options['input_border_radius']); ?>>4px</option>
							<option value='5px' <?php selected('5px', $options['input_border_radius']); ?>>5px</option>
							<option value='6px' <?php selected('6px', $options['input_border_radius']); ?>>6px</option>
							<option value='7px' <?php selected('7px', $options['input_border_radius']); ?>>7px</option>
							<option value='8px' <?php selected('8px', $options['input_border_radius']); ?>>8px</option>
							<option value='9px' <?php selected('9px', $options['input_border_radius']); ?>>9px</option>
							<option value='10px' <?php selected('10px', $options['input_border_radius']); ?>>10px</option>
							<option value='11px' <?php selected('11px', $options['input_border_radius']); ?>>11px</option>
							<option value='12px' <?php selected('12px', $options['input_border_radius']); ?>>12px</option>
							<option value='13px' <?php selected('13px', $options['input_border_radius']); ?>>13px</option>
							<option value='14px' <?php selected('14px', $options['input_border_radius']); ?>>14px</option>
							<option value='15px' <?php selected('15px', $options['input_border_radius']); ?>>15px</option>
						</select>
					</td>
				</tr>

				<!--Submit button styles-->

				<tr>

					<th scope="row">
					<?php _e('Submit button text color'); ?></th>
					<td>
						<input name="gf_custom_styles_options[submit_button_color]" type="text" value="<?php echo $options['submit_button_color']; ?>" class="wp-color-picker-field" data-default-color="#000000" />
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
					<th scope="row"><?php _e('Submit button border size'); ?></th>
					<td>
						<select name='gf_custom_styles_options[submit_button_border_size]'>
							<option value='0px' <?php selected('0px',   $options['submit_button_border_size']); ?>>0px</option>
							<option value='1px' <?php selected('1px',   $options['submit_button_border_size']); ?>>1px</option>
							<option value='2px' <?php selected('2px',   $options['submit_button_border_size']); ?>>2px</option>
							<option value='3px' <?php selected('3px',   $options['submit_button_border_size']); ?>>3px</option>
							<option value='4px' <?php selected('4px',   $options['submit_button_border_size']); ?>>4px</option>
							<option value='5px' <?php selected('5px',   $options['submit_button_border_size']); ?>>5px</option>
							<option value='6px' <?php selected('6px',   $options['submit_button_border_size']); ?>>6px</option>
							<option value='7px' <?php selected('7px',   $options['submit_button_border_size']); ?>>7px</option>
							<option value='8px' <?php selected('8px',   $options['submit_button_border_size']); ?>>8px</option>
							<option value='9px' <?php selected('9px',   $options['submit_button_border_size']); ?>>9px</option>
							<option value='10px' <?php selected('10px', $options['submit_button_border_size']); ?>>10px</option>
							<option value='11px' <?php selected('11px', $options['submit_button_border_size']); ?>>11px</option>
							<option value='12px' <?php selected('12px', $options['submit_button_border_size']); ?>>12px</option>
							<option value='13px' <?php selected('13px', $options['submit_button_border_size']); ?>>13px</option>
							<option value='14px' <?php selected('14px', $options['submit_button_border_size']); ?>>14px</option>
							<option value='15px' <?php selected('15px', $options['submit_button_border_size']); ?>>15px</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Submit button border radius'); ?></th>
					<td>
						<select name='gf_custom_styles_options[submit_button_border_radius]'>
							<option value='0px' <?php selected('0px',   $options['submit_button_border_radius']); ?>>0px</option>
							<option value='1px' <?php selected('1px',   $options['submit_button_border_radius']); ?>>1px</option>
							<option value='2px' <?php selected('2px',   $options['submit_button_border_radius']); ?>>2px</option>
							<option value='3px' <?php selected('3px',   $options['submit_button_border_radius']); ?>>3px</option>
							<option value='4px' <?php selected('4px',   $options['submit_button_border_radius']); ?>>4px</option>
							<option value='5px' <?php selected('5px',   $options['submit_button_border_radius']); ?>>5px</option>
							<option value='6px' <?php selected('6px',   $options['submit_button_border_radius']); ?>>6px</option>
							<option value='7px' <?php selected('7px',   $options['submit_button_border_radius']); ?>>7px</option>
							<option value='8px' <?php selected('8px',   $options['submit_button_border_radius']); ?>>8px</option>
							<option value='9px' <?php selected('9px',   $options['submit_button_border_radius']); ?>>9px</option>
							<option value='10px' <?php selected('10px', $options['submit_button_border_radius']); ?>>10px</option>
							<option value='11px' <?php selected('11px', $options['submit_button_border_radius']); ?>>11px</option>
							<option value='12px' <?php selected('12px', $options['submit_button_border_radius']); ?>>12px</option>
							<option value='13px' <?php selected('13px', $options['submit_button_border_radius']); ?>>13px</option>
							<option value='14px' <?php selected('14px', $options['submit_button_border_radius']); ?>>14px</option>
							<option value='15px' <?php selected('15px', $options['submit_button_border_radius']); ?>>15px</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Custom AJAX spinner'); ?></th>
					<td>
						<input type="text" size="10" name="gf_custom_styles_options[ajax_spinner_url]" value="<?php if ( isset($options['ajax_spinner_url']) ) { echo $options['ajax_spinner_url']; } ?>" />
						<?php _e('Paste the url of a custom loader/spinner image here.'); ?>
					</td>
				</tr>

				<!-- Global options for this form -->
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
					<th scope="row"><?php _e('Optional custom CSS'); ?></th>
					<td>
						<textarea name="gf_custom_styles_options[custom_css]" rows="7" cols="50" type='textarea'><?php echo $options['custom_css']; ?></textarea><br /><span style="color:#666666;margin-left:2px;"><em><?php _e('This CSS will only load on the selected form'); ?></em>.</span>
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


	/**
	 * gf_custom_styles_validate_options function.
	 *
	 * @access private
	 * @param mixed $input
	 * @return void
	 */
function gf_custom_styles_validate_options($input) {

	 // strip html from textboxes

	$input['custom_css'] =  wp_filter_nohtml_kses($input['custom_css']);

	// Sanitize textbox input (strip html tags, and escape characters)

	return $input;
}

	/**
	 *  Display settings link
	 *
	 * @access public
	 * @param mixed $links
	 * @param mixed $file
	 * @return void
	 */
function gf_custom_styles_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$gf_custom_styles_links = '<a href="'.get_admin_url().'admin.php?page=gf-custom-styles">'. __('Settings').'</a>';

		// Make the 'Settings' link appear first

		array_unshift( $links, $gf_custom_styles_links );

	}

	return $links;
}


add_action( "wp_head", "gf_custom_styles_add_content", 9999 );

	/**
	 * Append inline styles to wp_head in lieu of generating an additional css file.
	 *
	 * @access public
	 * @param mixed $text
	 * @return void
	 */
function gf_custom_styles_add_content( $css ) {

	// get the selected gravity form ID
	$form_ID = '';

	$options = get_option('gf_custom_styles_options');

		// General input style options

		$input_background_color 	 = $options['input_background_color'];

		$input_color 				 = $options['input_color'];

		$input_border_size 			 = $options['input_border_size'];

		$input_border_color 		 = $options['input_border_color'];

		$input_border_radius 		 = $options['input_border_radius'];

		// Submit button style options

		$submit_button_color 		 = $options['submit_button_color'];

		$submit_button_bg_color 	 = $options['submit_button_bg_color'];

		$submit_button_border_color  = $options['submit_button_border_color'];

		$submit_button_border_size  = $options['submit_button_border_size'];

		$submit_button_border_radius = $options['submit_button_border_radius'];

		// Custom CSS textarea

		$custom_css 	= $options['custom_css'];

		// Generate and inject the css

		$css  = '<style>';

		// General input styles

		$css .= '#gform_wrapper_' . $form_ID . ' input' . ' { border:' . $input_border_size . ' solid ' . $input_border_color . '; background:' .$input_background_color . '; border-radius:' . $input_border_radius . '; color:' . $input_color . ';}';

		// Submit button styles

		$css .= '#gform_wrapper_' . $form_ID . ' #gform_submit_button_' . $form_ID . ' { border:' . $submit_button_border_size . ' solid ' . $submit_button_border_color . '; background:' .$submit_button_bg_color . '; border-radius:' . $submit_button_border_radius . '; color:' . $submit_button_color . ';}';

		// Custom css

		$css .= $custom_css;

		$css .= '</style>';

	echo $css;

		do_action('gf_custom_styles_inject_css');

}