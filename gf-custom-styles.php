<?php
/*
Plugin Name: Gravity Forms Custom Styles
Plugin URI: http://www.maintainn.com/gravity-forms-custom-styles
Description: A gravity forms add-on that allows for the addition of user-defined styles per Gravity Forms form.
Version: 1.0.1
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

function gf_custom_styles_missing_gf_notice() {

  if ( ! is_plugin_active( 'gravityforms/gravityforms.php' ) ) {

    $message = 'Uh oh! You need to activate the <a href="http://gravityforms.com" target="_blank">Gravity Forms</a> plugin to use the Gravity Forms Custom Styles add-on!';

    echo '<div class="error"> <p>' . $message . '</p></div>';
    deactivate_plugins( plugin_basename( __FILE__ ) );
  }
}

add_action( 'admin_notices', 'gf_custom_styles_missing_gf_notice' );


// get admin settings

	require( plugin_dir_path( __FILE__ ) . 'admin.php');

// Extend GF add-on class

if ( class_exists("GFForms") ) {

    GFForms::include_addon_framework();

    class GFCustomStyles extends GFAddOn {

        protected $_version = "1.0";
        protected $_min_gravityforms_version = "1.7";
        protected $_slug = "gf-custom-styles";
        protected $_path = "gf-custom-styles/gf-custom-styles.php";
        protected $_full_path = __FILE__;
        protected $_title = "Gravity Forms Custom Styles";
        protected $_short_title = "Custom Styles";

        public function init() {

            parent::init();
            add_action( 'admin_enqueue_scripts', array($this, 'plugin_page_scripts' ));
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