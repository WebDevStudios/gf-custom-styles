<?php
/*
Plugin Name: Gravity Forms Custom Styles
Plugin URI: http://www.maintainn.com/gravity-forms-custom-styles
Description: A gravity forms add-on that allows for the addition of user-defined styles per Gravity Forms form.
Version: 1.0
Author: Maintainn
Author URI: http://www.maintainn.com
License: GPL v2 or later

------------------------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

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
						"textarea_one" => "This type of control allows a large amount of information to be entered all at once. Set the 'rows' and 'cols' attributes to set the width and height.",
						"txt_one" => "Enter whatever you like here..",
						"drp_select_box" => "four",
						"chk_default_options_db" => "",
						"input_border_color" => "",
						"submit_button_bg_color" => "",
						"submit_button_border_color" => ""

		);
		update_option('gf_custom_styles_options', $arr);
	}
}

function gf_custom_styles_init(){
	register_setting( 'gf_custom_styles_plugin_options', 'gf_custom_styles_options', 'gf_custom_styles_validate_options' );
}


// Render the Plugin options form
function gf_custom_styles_render_form() {
	?>
	<div class="wrap">

		<div class="icon32" id="icon-options-general"><br></div>
		<p></p>

		<form method="post" action="options.php">
			<?php settings_fields('gf_custom_styles_plugin_options'); ?>
			<?php $options = get_option('gf_custom_styles_options'); ?>

			<table class="form-table">

			<!-- TODO: get gforms here -->
				<tr>
					<th scope="row">Select your form</th>
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

				<!-- Textbox Control -->
				<tr>
					<th scope="row"><?php _e('Custom class'); ?></th>
					<td>
						<input type="text" size="10" name="gf_custom_styles_options[txt_one]" value="<?php echo $options['txt_one']; ?>" />
					</td>
				</tr>

				<!-- Checkbox Buttons -->
				<tr valign="top">
					<th scope="row"><?php _e('General form styling options'); ?></th>
					<td>
						<!-- First checkbox button -->
						<label><input name="gf_custom_styles_options[chk_button1]" type="checkbox" value="1" <?php if (isset($options['chk_button1'])) { checked('1', $options['chk_button1']); } ?> /><?php _e('Show input borders'); ?></label><br />

						<!-- Second checkbox button -->
						<label><input name="gf_custom_styles_options[chk_button2]" type="checkbox" value="1" <?php if (isset($options['chk_button2'])) { checked('1', $options['chk_button2']); } ?> /><?php _e('Enable form background'); ?></label><br />

						<!-- Third checkbox button -->
						<label><input name="gf_custom_styles_options[chk_button3]" type="checkbox" value="1" <?php if (isset($options['chk_button3'])) { checked('1', $options['chk_button3']); } ?> /><?php _e('Disable styles on mobile devices'); ?></label><br />

					</td>
				</tr>

						<!-- custom css -->
				<tr>
					<th scope="row"><?php _e('Custom CSS'); ?></th>
					<td>
						<textarea name="gf_custom_styles_options[textarea_one]" rows="7" cols="50" type='textarea'><?php echo $options['textarea_one']; ?></textarea><br /><span style="color:#666666;margin-left:2px;"><?php _e('This CSS will only load on the selected form'); ?>.</span>
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
	$input['textarea_one'] =  wp_filter_nohtml_kses($input['textarea_one']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']); // Sanitize textbox input (strip html tags, and escape characters)
	return $input;
}

// Display a Settings link on the main Plugins page
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
	$text = "<p style=\"color: #777;border:1px dashed #999; padding: 6px;\">Select box Plugin option is: {$select}</p>{$text}";
	return $text;
}


//GF add-on

//------------------------------------------
if (class_exists("GFForms")) {
    GFForms::include_addon_framework();

    class GFCustomStyles extends GFAddOn {

        protected $_version = "1.0";
        protected $_min_gravityforms_version = "1.7";
        protected $_slug = "gf-custom-styles";
        protected $_path = "gf-custom-styles/gf-custom-styles.php";
        protected $_full_path = __FILE__;
        protected $_title = "Gravity Forms Custom Styles";
        protected $_short_title = "Custom Styles";

        public function init(){
            parent::init();
            add_filter("gform_submit_button", array($this, "form_submit_button"), 10, 2);

            add_action( 'admin_enqueue_scripts', array($this, 'plugin_page_scripts' ));
        }

        // Add the text in the plugin settings to the bottom of the form if enabled for this form
        function form_submit_button($button, $form){
            $settings = $this->get_form_settings($form);
            if(isset($settings["enabled"]) && true == $settings["enabled"]){

                $button = "<div>{$text}</div>" . $button;

            }
            return $button;
        }

		public function plugin_page_scripts() {

        // Add wp-color-picker scripts

        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script( 'gf-custom-styles', plugins_url( 'js/gf-custom-styles.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

}



        public function plugin_page() {

        gf_custom_styles_render_form();

         }


        public function form_settings_fields($form) {
            return array(
                array(
                    "title"  => "Custom styles for this form",
                    "fields" => array(

							array(
                            "label"   => "Enable custom styles",
                            "type"    => "checkbox",
                            "name"    => "custom_styles_enabled",
                            "tooltip" => "Select this checkbox to turn on custom styles for this form",
                            "choices" => array(
                                array(
                                    "label" => "Enabled",
                                    "name"  => "enabled"
                                )
                            )
                        ),
                   )
                )
            );


        }

        public function scripts() {
            $scripts = array(
                array("handle"  => "gf_custom_styles_js",
                      "src"     => $this->get_base_url() . "/js/gf_custom_styles.js",
                      "version" => $this->_version,
                      "deps"    => array("jquery"),
                      "strings" => array(
                          'first'  => __("First Choice", "gf-custom-styles"),
                          'second' => __("Second Choice", "gf-custom-styles"),
                          'third'  => __("Third Choice", "gf-custom-styles")
                      ),
                      "enqueue" => array(
                          array(
                              "admin_page" => array("form_settings"),
                              "tab"        => "gf-custom-styles"
                          )
                      )
                ),

            );

            return array_merge(parent::scripts(), $scripts);
        }

        public function styles() {

            $styles = array(
                array("handle"  => "gf_custom_styles_css",
                      "src"     => $this->get_base_url() . "/css/gf_custom_styles.css",
                      "version" => $this->_version,
                      "enqueue" => array(
                          array("field_types" => array("poll"))
                      )
                )
            );

            return array_merge(parent::styles(), $styles);
        }



    }

    new GFCustomStyles();
}