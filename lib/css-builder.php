<?php

	/*
	*
	*	CSS builder class
	*	Written by Andrew Norcross
	*	for the Design Palette Pro plugin, which you should buy
	*	@author norcross
	*	https://genesisdesignpro.com/
	*/

class GP_Pro_Builder {

	/**
	 * run checks for field data in CSS builder
	 *
	 * @return
	 */

	static function build_check( $data, $field ) {

		if ( ! isset( $data ) )
			return false;

		if ( ! isset( $data[ $field ] ) )
			return false;

		// run an empty check on non-numeric values
		if ( empty( $data[ $field ] ) && ! is_numeric( $data[ $field ] ) )
			return false;

		// run a comparison check on non-numeric values
		if ( $data[ $field ] === GP_Pro_Helper::get_default( $field ) && ! is_numeric( $data[ $field ] ) )
			return false;

		// run a comparison check on numeric values
		if ( is_numeric( $data[ $field ] ) ) :

			$default	= GP_Pro_Helper::get_default( $field );

			if ( intval( $data[ $field ] ) === intval( $default ) )
				return false;

		endif;

		return true;

	}

	/**
	 * small helper to get CSS values from font stack array
	 *
	 * @return
	 */

	static function stack_css( $selector, $value, $important = false ) {

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// fetch our list of stacks
		$stacks	= GP_Pro_Helper::stacks();

		// merge our stack types
		$serif	= isset( $stacks['serif'] ) ? $stacks['serif']	: array();
		$sans	= isset( $stacks['sans'] )	? $stacks['sans']	: array();
		$mono	= isset( $stacks['mono'] )	? $stacks['mono']	: array();

		// filter for new types
		$other	= apply_filters( 'gppro_stack_css_array', array() );

		$stacks	= array_merge( $serif, $sans, $mono, $other );

		if ( ! isset( $stacks[$value] ) )
			return false;

		if ( ! isset( $stacks[$value]['css'] ) )
			return false;

		// make sure we don't have an extra semicolon
		$stack	= str_replace( ';', '', $stacks[$value]['css'] );

		return esc_attr( $selector ).': '.$stack.$exmark.'; ';

	}

	/**
	 * small helper to make sure hex CSS values are done correctly
	 *
	 * @return
	 */

	static function hexcolor_css( $selector, $value, $important = false ) {

		// first remove possible duplicate hash
		$value	= preg_replace( '/#+/', '#', $value );

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// check if there is a single hash
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) //hex color is valid
			return esc_attr( $selector ).': '.$value.$exmark.'; ';

		// check for missing hash
		if ( preg_match( '/^[a-f0-9]{6}$/i', $value ) ) //hex color is valid
			return esc_attr( $selector ).': #'.$value.$exmark.'; ';

		// send back false if it failed
		return false;

	}

	/**
	 * small helper to make sure numeric CSS values are done correctly
	 *
	 * @return
	 */

	static function number_css( $selector, $value, $important = false ) {

		// first strip it
		$numval	= GP_Pro_Helper::number_check( $value );

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		return esc_attr( $selector ).': '.intval( $numval ).$exmark.'; ';

	}

	/**
	 * small helper to make sure text CSS values are done correctly
	 *
	 * @return
	 */

	static function text_css( $selector, $value, $important = false ) {

		// first strip it
		$textval	= GP_Pro_Helper::text_check( $value );

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// send back both
		return esc_attr( $selector ).': '.esc_attr( strtolower( $textval ) ).$exmark.'; ';

	}


	/**
	 * small helper to make sure PX and REM values in CSS are done correctly
	 *
	 * @return
	 */

	static function px_rem_css( $selector, $value, $important = false ) {

		// first strip it
		$numval		= GP_Pro_Helper::number_check( $value );

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// bypass calcs and return without suffix for zeros
		if ( intval( $numval ) === 0 )
			return esc_attr( $selector ).': '.$numval.$exmark.'; '.esc_attr( $selector ).': '.$numval.$exmark.'; ';

		// make both calculations
		$pix_val	= intval( $numval );
		$rem_val	= intval( $numval ) / 10;

		// send back both
		return esc_attr( $selector ).': '.$pix_val.'px'.$exmark.'; '.esc_attr( $selector ).': '.$rem_val.'rem'.$exmark.'; ';

	}


	/**
	 * small helper to make sure PX (without REM ) values in CSS are done correctly
	 *
	 * @return
	 */

	static function px_css( $selector, $value, $important = false ) {

		// first strip it
		$numval	= GP_Pro_Helper::number_check( $value );

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// bypass and return without suffix for zero
		if ( intval( $numval ) === 0 )
			return esc_attr( $selector ).': '.intval( $numval ).$exmark.'; ';

		// send back
		return esc_attr( $selector ).': '.intval( $numval ).'px'.$exmark.'; ';

	}


	/**
	 * small helper to make sure numeric CSS percentage values are done correctly
	 *
	 * @return
	 */

	static function pct_css( $selector, $value, $important = false ) {

		// first strip it
		$numval	= GP_Pro_Helper::number_check( $value );

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// bypass and return without suffix for zero
		if ( intval( $numval ) === 0 )
			return esc_attr( $selector ).': '.intval( $number ).$exmark.'; ';

		return esc_attr( $selector ).': '.intval( $number ).'%'.$exmark.'; ';

	}

	/**
	 * small helper to handle image CSS build
	 *
	 * @return
	 */

	static function image_css( $selector, $value, $position, $important = false ) {

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// send back image with proper position
		return esc_attr( $selector ).': url( "'.esc_url( $value ).'") '.esc_attr( $position ).$exmark.'; ';

	}

	/**
	 * small helper to handle generic CSS build
	 *
	 * @return
	 */

	static function generic_css( $selector, $value, $important = false ) {

		// check and set important flag
		$exmark	= $important === true ? '!important' : '';

		// send back both
		return esc_attr( $selector ).': '.esc_attr( $value ).$exmark.'; ';

	}


	/**
	 * begin building out CSS blocks
	 *
	 * @return
	 */

	function general_body( $data, $class ) {

		$css	= '/* general body */'."\n";

		// wrapper for all items attributed to body
		$css	.= $class.' { ';

			if ( self::build_check( $data, 'body-color-back-main' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['body-color-back-main'] );

			if ( self::build_check( $data, 'body-color-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['body-color-text'] );

			if ( self::build_check( $data, 'body-type-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['body-type-stack'] );

			if ( self::build_check( $data, 'body-type-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['body-type-size'] );

			if ( self::build_check( $data, 'body-type-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['body-type-weight'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'body-color-link' ) )
			$css	.= $class.' a { '.self::hexcolor_css( 'color', $data['body-color-link'] ).'}'."\n";

		if ( self::build_check( $data, 'body-color-link-hov' ) )
			$css	.= $class.' a:hover, '.$class.' a:focus { '.self::hexcolor_css( 'color', $data['body-color-link-hov'] ).'}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_general_body', $css, $data, $class );

		return $css;

	}

	function header_area( $data, $class ) {

		$css	= '/* site header */'."\n";

		if ( self::build_check( $data, 'site-header-img-standard' ) )
			$css	.= 'body.gppro-header-image .site-header .wrap { '.self::image_css( 'background', $data['site-header-img-standard'], 'no-repeat left' ).'}'."\n";

		if ( self::build_check( $data, 'header-color-back' ) )
			$css	.= $class.' .site-header { '.self::hexcolor_css( 'background-color', $data['header-color-back'] ).'}'."\n";

		// header wrap padding setup
		$css	.= $class.' .site-header .wrap { ';

			if ( self::build_check( $data, 'header-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['header-padding-top'] );

			if ( self::build_check( $data, 'header-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['header-padding-bottom'] );

			if ( self::build_check( $data, 'header-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['header-padding-left'] );

			if ( self::build_check( $data, 'header-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['header-padding-right'] );

		$css	.= '}'."\n";

		// title area padding
		$css	.= $class.' .site-header .title-area { ';

			if ( self::build_check( $data, 'site-title-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['site-title-padding-top'] );

			if ( self::build_check( $data, 'site-title-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['site-title-padding-bottom'] );

			if ( self::build_check( $data, 'site-title-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['site-title-padding-left'] );

			if ( self::build_check( $data, 'site-title-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['site-title-padding-right'] );

		$css	.= '}'."\n";

		// site title color and font setup
		$css	.= $class.' .site-header .site-title { ';

			if ( self::build_check( $data, 'site-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['site-title-text'] );

			if ( self::build_check( $data, 'site-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['site-title-stack'] );

			if ( self::build_check( $data, 'site-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['site-title-size'] );

			if ( self::build_check( $data, 'site-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['site-title-weight'] );

			if ( self::build_check( $data, 'site-title-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['site-title-transform'] );

			if ( self::build_check( $data, 'site-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['site-title-align'] );

		$css	.= '}'."\n";

		// wrapper for multiple items
		$css	.= $class.' .site-header .site-title a { ';

			if ( self::build_check( $data, 'site-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['site-title-text'] );

			if ( self::build_check( $data, 'site-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['site-title-weight'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'site-title-text' ) )
			$css	.= $class.' .site-header .site-title a:hover, '.$class.' .site-header .site-title a:focus { '.self::hexcolor_css( 'color', $data['site-title-text'] ).'}'."\n";

		// site description setup
		$css	.= $class.' .site-header .site-description { ';

			if ( self::build_check( $data, 'site-desc-display' ) )
				$css	.= self::text_css( 'display', $data['site-desc-display'] );

			if ( self::build_check( $data, 'site-desc-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['site-desc-text'] );

			if ( self::build_check( $data, 'site-desc-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['site-desc-stack'] );

			if ( self::build_check( $data, 'site-desc-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['site-desc-size'] );

			if ( self::build_check( $data, 'site-desc-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['site-desc-weight'] );

			if ( self::build_check( $data, 'site-desc-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['site-desc-transform'] );

			if ( self::build_check( $data, 'site-desc-align' ) )
				$css	.= self::text_css( 'text-align', $data['site-desc-align'] );

		$css	.= '}'."\n";

		// general header widgets

		// header widget navigation
		$css	.= $class.' .site-header .nav-header a { ';

			if ( self::build_check( $data, 'header-nav-item-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['header-nav-item-back'] );

			if ( self::build_check( $data, 'header-nav-item-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['header-nav-item-link'] );

			if ( self::build_check( $data, 'header-nav-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['header-nav-stack'] );

			if ( self::build_check( $data, 'header-nav-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['header-nav-size'] );

			if ( self::build_check( $data, 'header-nav-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['header-nav-weight'] );

			if ( self::build_check( $data, 'header-nav-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['header-nav-transform'] );

			if ( self::build_check( $data, 'header-nav-item-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['header-nav-item-padding-top'] );

			if ( self::build_check( $data, 'header-nav-item-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['header-nav-item-padding-bottom'] );

			if ( self::build_check( $data, 'header-nav-item-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['header-nav-item-padding-left'] );

			if ( self::build_check( $data, 'header-nav-item-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['header-nav-item-padding-right'] );

		$css	.= '}'."\n";

		// hover state of header nav items
		$css	.= $class.' .site-header .nav-header a:hover, '.$class.' .site-header .nav-header a:focus { ';

			if ( self::build_check( $data, 'header-nav-item-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['header-nav-item-back-hov'] );

			if ( self::build_check( $data, 'header-nav-item-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['header-nav-item-link-hov'] );

		$css	.= '}'."\n";

		// general header widget titles
		$css	.= $class.' .header-widget-area .widget .widget-title { ';

			if ( self::build_check( $data, 'header-widget-title-color' ) )
				$css	.= self::hexcolor_css( 'color', $data['header-widget-title-color'] );

			if ( self::build_check( $data, 'header-widget-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['header-widget-title-stack'] );

			if ( self::build_check( $data, 'header-widget-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['header-widget-title-size'] );

			if ( self::build_check( $data, 'header-widget-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['header-widget-title-weight'] );

			if ( self::build_check( $data, 'header-widget-title-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['header-widget-title-transform'] );

			if ( self::build_check( $data, 'header-widget-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['header-widget-title-align'] );

			if ( self::build_check( $data, 'header-widget-title-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['header-widget-title-margin-bottom'] );

		$css	.= '}'."\n";

		// general header widget content
		$css	.= $class.' .header-widget-area .widget { ';

			if ( self::build_check( $data, 'header-widget-content-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['header-widget-content-text'] );

			if ( self::build_check( $data, 'header-widget-content-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['header-widget-content-stack'] );

			if ( self::build_check( $data, 'header-widget-content-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['header-widget-content-size'] );

			if ( self::build_check( $data, 'header-widget-content-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['header-widget-content-weight'] );

			if ( self::build_check( $data, 'header-widget-content-align' ) )
				$css	.= self::text_css( 'text-align', $data['header-widget-content-align'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'header-widget-content-link' ) )
			$css	.= $class.' .header-widget-area .widget a { '.self::hexcolor_css( 'color', $data['header-widget-content-link'] ).'}'."\n";

		if ( self::build_check( $data, 'header-widget-content-link-hov' ) )
			$css	.= $class.' .header-widget-area .widget a:hover, '.$class.' .header-widget-area .widget a:focus { '.self::hexcolor_css( 'color', $data['header-widget-content-link-hov'] ).'}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_header_area', $css, $data, $class );

		return $css;

	}

	function navigation( $data, $class ) {

		$css	= '/* primary navigation */'."\n";

		if ( self::build_check( $data, 'primary-nav-area-back' ) )
			$css	.= $class.' .nav-primary { '.self::hexcolor_css( 'background-color', $data['primary-nav-area-back'] ).'}'."\n";


		// typography setup for primary nav
		$css	.= $class.' .menu-primary { ';

			if ( self::build_check( $data, 'primary-nav-top-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['primary-nav-top-stack'] );

			if ( self::build_check( $data, 'primary-nav-top-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['primary-nav-top-size'] );

			if ( self::build_check( $data, 'primary-nav-top-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['primary-nav-top-weight'] );

			if ( self::build_check( $data, 'primary-nav-top-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['primary-nav-top-transform'] );

			if ( self::build_check( $data, 'primary-nav-top-align' ) )
				$css	.= self::text_css( 'text-align', $data['primary-nav-top-align'] );

		$css	.= '}'."\n";

		// primary nav item links
		$css	.= $class.' .menu-primary a { ';

			if ( self::build_check( $data, 'primary-nav-top-item-base-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-top-item-base-back'] );

			if ( self::build_check( $data, 'primary-nav-top-item-base-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-top-item-base-link'] );

			if ( self::build_check( $data, 'primary-nav-top-item-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['primary-nav-top-item-padding-top'] );

			if ( self::build_check( $data, 'primary-nav-top-item-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['primary-nav-top-item-padding-bottom'] );

			if ( self::build_check( $data, 'primary-nav-top-item-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['primary-nav-top-item-padding-left'] );

			if ( self::build_check( $data, 'primary-nav-top-item-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['primary-nav-top-item-padding-right'] );

		$css	.= '}'."\n";

		// hover state of  primary nav item links
		$css	.= $class.' .menu-primary a:hover, '.$class.' .menu-primary a:focus { ';

			if ( self::build_check( $data, 'primary-nav-top-item-base-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-top-item-base-back-hov'] );

			if ( self::build_check( $data, 'primary-nav-top-item-base-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-top-item-base-link-hov'] );

		$css	.= '}'."\n";

		// primary nav active item links
		$css	.= $class.' .menu-primary .current-menu-item a { ';

			if ( self::build_check( $data, 'primary-nav-top-item-active-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-top-item-active-back'] );

			if ( self::build_check( $data, 'primary-nav-top-item-active-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-top-item-active-link'] );

		$css	.= '}'."\n";

		// hover state of active primary nav item links
		$css	.= $class.' .menu-primary .current-menu-item a:hover, '.$class.' .menu-primary .current-menu-item a:focus { ';

			if ( self::build_check( $data, 'primary-nav-top-item-active-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-top-item-active-back-hov'] );

			if ( self::build_check( $data, 'primary-nav-top-item-active-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-top-item-active-link-hov'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'primary-nav-drop-align' ) )
			$css	.= $class.' .menu-primary ul.sub-menu .menu-item { '.self::text_css( 'text-align', $data['primary-nav-drop-align'] ).'}'."\n";

		// primary nav item dropdown stack setup
		$css	.= $class.' .menu-primary ul.sub-menu { ';

			if ( self::build_check( $data, 'primary-nav-drop-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['primary-nav-drop-stack'] );

			if ( self::build_check( $data, 'primary-nav-drop-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['primary-nav-drop-size'] );

			if ( self::build_check( $data, 'primary-nav-drop-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['primary-nav-drop-weight'] );

			if ( self::build_check( $data, 'primary-nav-drop-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['primary-nav-drop-transform'] );

			if ( self::build_check( $data, 'primary-nav-drop-align' ) )
				$css	.= self::text_css( 'text-align', $data['primary-nav-drop-align'] );

		$css	.= '}'."\n";

		// primary nav item dropdown links
		$css	.= $class.' .menu-primary ul.sub-menu a { ';

			if ( self::build_check( $data, 'primary-nav-drop-item-base-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-drop-item-base-back'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-base-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-drop-item-base-link'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['primary-nav-drop-item-padding-top'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['primary-nav-drop-item-padding-bottom'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['primary-nav-drop-item-padding-left'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['primary-nav-drop-item-padding-right'] );

			if ( self::build_check( $data, 'primary-nav-drop-border-color' ) )
				$css	.= self::hexcolor_css( 'border-color', $data['primary-nav-drop-border-color'] );

			if ( self::build_check( $data, 'primary-nav-drop-border-style' ) )
				$css	.= self::text_css( 'border-style', $data['primary-nav-drop-border-style'] );

			if ( self::build_check( $data, 'primary-nav-drop-border-width' ) )
				$css	.= self::px_css( 'border-width', $data['primary-nav-drop-border-width'] );

		$css	.= '}'."\n";

		// hover state of  primary nav item dropdown links
		$css	.= $class.' .menu-primary ul.sub-menu a:hover, '.$class.' .menu-primary ul.sub-menu a:focus { ';

			if ( self::build_check( $data, 'primary-nav-drop-item-base-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-drop-item-base-back-hov'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-base-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-drop-item-base-link-hov'] );

		$css	.= '}'."\n";

		// primary nav active item dropdown links
		$css	.= $class.' .menu-primary ul.sub-menu .current-menu-item a { ';

			if ( self::build_check( $data, 'primary-nav-drop-item-active-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-drop-item-active-back'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-active-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-drop-item-active-link'] );

		$css	.= '}'."\n";

		// hover state of active primary nav item dropdown links
		$css	.= $class.' .menu-primary ul.sub-menu .current-menu-item a:hover, '.$class.' .menu-primary ul.sub-menu .current-menu-item a:focus { ';

			if ( self::build_check( $data, 'primary-nav-drop-item-active-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['primary-nav-drop-item-active-back-hov'] );

			if ( self::build_check( $data, 'primary-nav-drop-item-active-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['primary-nav-drop-item-active-link-hov'] );

		$css	.= '}'."\n";


		$css	.= '/* secondary navigation */'."\n";

		if ( self::build_check( $data, 'secondary-nav-area-back' ) )
			$css	.= $class.' .nav-secondary { '.self::hexcolor_css( 'background-color', $data['secondary-nav-area-back'] ).'}'."\n";

		// typography setup for secondary nav
		$css	.= $class.' .menu-secondary { ';

			if ( self::build_check( $data, 'secondary-nav-top-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['secondary-nav-top-stack'] );

			if ( self::build_check( $data, 'secondary-nav-top-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['secondary-nav-top-size'] );

			if ( self::build_check( $data, 'secondary-nav-top-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['secondary-nav-top-weight'] );

			if ( self::build_check( $data, 'secondary-nav-top-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['secondary-nav-top-transform'] );

			if ( self::build_check( $data, 'secondary-nav-top-align' ) )
				$css	.= self::text_css( 'text-align', $data['secondary-nav-top-align'] );

		$css	.= '}'."\n";

		// secondary nav item links
		$css	.= $class.' .menu-secondary a { ';

			if ( self::build_check( $data, 'secondary-nav-top-item-base-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-top-item-base-back'] );

			if ( self::build_check( $data, 'secondary-nav-top-item-base-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-top-item-base-link'] );

			if ( self::build_check( $data, 'secondary-nav-item-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['secondary-nav-item-padding-top'] );

			if ( self::build_check( $data, 'secondary-nav-item-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['secondary-nav-item-padding-bottom'] );

			if ( self::build_check( $data, 'secondary-nav-item-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['secondary-nav-item-padding-left'] );

			if ( self::build_check( $data, 'secondary-nav-item-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['secondary-nav-item-padding-right'] );

		$css	.= '}'."\n";

		// hover state of  secondary nav item links
		$css	.= $class.' .menu-secondary a:hover, '.$class.' .menu-secondary a:focus { ';

			if ( self::build_check( $data, 'secondary-nav-top-item-base-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-top-item-base-back-hov'] );

			if ( self::build_check( $data, 'secondary-nav-top-item-base-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-top-item-base-link-hov'] );

		$css	.= '}'."\n";

		// secondary nav active item links
		$css	.= $class.' .menu-secondary .current-menu-item a { ';

			if ( self::build_check( $data, 'secondary-nav-top-item-active-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-top-item-active-back'] );

			if ( self::build_check( $data, 'secondary-nav-top-item-active-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-top-item-active-link'] );

		$css	.= '}'."\n";

		// hover state of active secondary nav item links
		$css	.= $class.' .menu-secondary .current-menu-item a:hover, '.$class.' .menu-secondary .current-menu-item a:focus { ';

			if ( self::build_check( $data, 'secondary-nav-top-item-active-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-top-item-active-back-hov'] );

			if ( self::build_check( $data, 'secondary-nav-top-item-active-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-top-item-active-link-hov'] );

		$css	.= '}'."\n";

		// secondary nav item dropdown stack setup
		$css	.= $class.' .menu-secondary ul.sub-menu { ';

			if ( self::build_check( $data, 'secondary-nav-drop-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['secondary-nav-drop-stack'] );

			if ( self::build_check( $data, 'secondary-nav-drop-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['secondary-nav-drop-size'] );

			if ( self::build_check( $data, 'secondary-nav-drop-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['secondary-nav-drop-weight'] );

			if ( self::build_check( $data, 'secondary-nav-drop-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['secondary-nav-drop-transform'] );

			if ( self::build_check( $data, 'secondary-nav-drop-align' ) )
				$css	.= self::text_css( 'text-align', $data['secondary-nav-drop-align'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'secondary-nav-drop-align' ) )
			$css	.= $class.' .menu-secondary ul.sub-menu .menu-item { '.self::text_css( 'text-align', $data['secondary-nav-drop-align'] ).'}'."\n";

		// secondary nav item dropdown links
		$css	.= $class.' .menu-secondary ul.sub-menu a { ';

			if ( self::build_check( $data, 'secondary-nav-drop-item-base-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-drop-item-base-back'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-base-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-drop-item-base-link'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['secondary-nav-drop-item-padding-top'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['secondary-nav-drop-item-padding-bottom'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['secondary-nav-drop-item-padding-left'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['secondary-nav-drop-item-padding-right'] );

			if ( self::build_check( $data, 'secondary-nav-drop-border-color' ) )
				$css	.= self::hexcolor_css( 'border-color', $data['secondary-nav-drop-border-color'] );

			if ( self::build_check( $data, 'secondary-nav-drop-border-style' ) )
				$css	.= self::text_css( 'border-style', $data['secondary-nav-drop-border-style'] );

			if ( self::build_check( $data, 'secondary-nav-drop-border-width' ) )
				$css	.= self::px_css( 'border-width', $data['secondary-nav-drop-border-width'] );

		$css	.= '}'."\n";

		// hover state of  secondary nav item dropdown links
		$css	.= $class.' .menu-secondary ul.sub-menu a:hover, '.$class.' .menu-secondary ul.sub-menu a:focus { ';

			if ( self::build_check( $data, 'secondary-nav-drop-item-base-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-drop-item-base-back-hov'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-base-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-drop-item-base-link-hov'] );

		$css	.= '}'."\n";

		// secondary nav active item dropdown links
		$css	.= $class.' .menu-secondary ul.sub-menu .current-menu-item a { ';

			if ( self::build_check( $data, 'secondary-nav-drop-item-active-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-drop-item-active-back'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-active-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-drop-item-active-link'] );

		$css	.= '}'."\n";

		// hover state of active secondary nav item dropdown links
		$css	.= $class.' .menu-secondary ul.sub-menu .current-menu-item a:hover, '.$class.' .menu-secondary ul.sub-menu .current-menu-item a:focus { ';

			if ( self::build_check( $data, 'secondary-nav-drop-item-active-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['secondary-nav-drop-item-active-back-hov'] );

			if ( self::build_check( $data, 'secondary-nav-drop-item-active-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['secondary-nav-drop-item-active-link-hov'] );

		$css	.= '}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_navigation', $css, $data, $class );

		return $css;

	}

	function home_content( $data, $class ) {

		$css	= '';

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_home_content', $css, $data, $class );

		return $css;
	}

	function post_content( $data, $class ) {

		$css	= '/* site inner wrapper */'."\n";

		// top level padding
		if ( self::build_check( $data, 'site-inner-padding-top' ) )
			$css	.= $class.' .site-inner { '.self::px_rem_css( 'padding-top', $data['site-inner-padding-top'] ).'}'."\n";


		$css	.= '/* main entry */'."\n";

		$css	.= $class.' .entry { ';

			if ( self::build_check( $data, 'main-entry-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['main-entry-back'] );

			if ( self::build_check( $data, 'main-entry-border-radius' ) )
				$css	.= self::px_rem_css( 'border-radius', $data['main-entry-border-radius'] );

			if ( self::build_check( $data, 'main-entry-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['main-entry-padding-top'] );

			if ( self::build_check( $data, 'main-entry-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['main-entry-padding-bottom'] );

			if ( self::build_check( $data, 'main-entry-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['main-entry-padding-left'] );

			if ( self::build_check( $data, 'main-entry-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['main-entry-padding-right'] );

			if ( self::build_check( $data, 'main-entry-margin-top' ) )
				$css	.= self::px_rem_css( 'margin-top', $data['main-entry-margin-top'] );

			if ( self::build_check( $data, 'main-entry-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['main-entry-margin-bottom'] );

			if ( self::build_check( $data, 'main-entry-margin-left' ) )
				$css	.= self::px_rem_css( 'margin-left', $data['main-entry-margin-left'] );

			if ( self::build_check( $data, 'main-entry-margin-right' ) )
				$css	.= self::px_rem_css( 'margin-right', $data['main-entry-margin-right'] );

		$css	.= '}'."\n";

		$css	.= '/* post title */'."\n";

		// wrapper for multiple items
		$css	.= $class.' .entry-header .entry-title { ';

			if ( self::build_check( $data, 'post-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['post-title-text'] );

			if ( self::build_check( $data, 'post-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['post-title-stack'] );

			if ( self::build_check( $data, 'post-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['post-title-size'] );

			if ( self::build_check( $data, 'post-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['post-title-weight'] );

			if ( self::build_check( $data, 'post-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['post-title-align'] );

			if ( self::build_check( $data, 'post-title-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['post-title-margin-bottom'] );


		$css	.= '}'."\n";

		if ( self::build_check( $data, 'post-title-link' ) )
			$css	.= $class.' .entry-header .entry-title a { '.self::hexcolor_css( 'color', $data['post-title-link'] ).'}'."\n";

		if ( self::build_check( $data, 'post-title-link-hov' ) )
			$css	.= $class.' .entry-header .entry-title a:hover, '.$class.' .entry-header .entry-title a:focus { '.self::hexcolor_css( 'color', $data['post-title-link-hov'] ).'}'."\n";

		$css	.= '/* post meta */'."\n";

		// top end meta setup
		$css	.= $class.' .entry-header .entry-meta { ';

			if ( self::build_check( $data, 'post-header-meta-text-color' ) )
				$css	.= self::hexcolor_css( 'color', $data['post-header-meta-text-color'] );

			if ( self::build_check( $data, 'post-header-meta-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['post-header-meta-stack'] );

			if ( self::build_check( $data, 'post-header-meta-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['post-header-meta-size'] );

			if ( self::build_check( $data, 'post-header-meta-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['post-header-meta-weight'] );

			if ( self::build_check( $data, 'post-header-meta-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['post-header-meta-transform'] );

			if ( self::build_check( $data, 'post-header-meta-align' ) )
				$css	.= self::text_css( 'text-align', $data['post-header-meta-align'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'post-header-meta-link-border' ) )
			$css	.= $class.' .entry-header .entry-meta a, '.$class.' .entry-header .entry-meta a:hover, '.$class.' .entry-header .entry-meta a:focus { '.self::text_css( 'border-bottom-style', $data['post-header-meta-link-border'] ).'}'."\n";

		if ( self::build_check( $data, 'post-header-meta-date-color' ) )
			$css	.= $class.' .entry-meta .entry-time { '.self::hexcolor_css( 'color', $data['post-header-meta-date-color'] ).'}'."\n";

		if ( self::build_check( $data, 'post-header-meta-author-link' ) )
			$css	.= $class.' .entry-header .entry-meta .entry-author a { '.self::hexcolor_css( 'color', $data['post-header-meta-author-link'] ).'}'."\n";

		if ( self::build_check( $data, 'post-header-meta-author-link-hov' ) )
			$css	.= $class.' .entry-header .entry-meta .entry-author a:hover, '.$class.' .entry-header .entry-meta .entry-author a:focus { '.self::hexcolor_css( 'color', $data['post-header-meta-author-link-hov'] ).'}'."\n";

		if ( self::build_check( $data, 'post-header-meta-comment-link' ) )
			$css	.= $class.' .entry-header .entry-meta .entry-comments-link a { '.self::hexcolor_css( 'color', $data['post-header-meta-comment-link'] ).'}'."\n";

		if ( self::build_check( $data, 'post-header-meta-comment-link-hov' ) )
			$css	.= $class.' .entry-header .entry-meta .entry-comments-link a:hover, '.$class.' .entry-header .entry-meta .entry-comments-link a:focus { '.self::hexcolor_css( 'color', $data['post-header-meta-comment-link-hov'] ).'}'."\n";


		$css	.= '/* post content */'."\n";

		// post content text setup
		$css	.= $class.' .entry .entry-content { ';

			if ( self::build_check( $data, 'post-entry-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['post-entry-text'] );

			if ( self::build_check( $data, 'post-entry-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['post-entry-stack'] );

			if ( self::build_check( $data, 'post-entry-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['post-entry-size'] );

			if ( self::build_check( $data, 'post-entry-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['post-entry-weight'] );

		$css	.= '}'."\n";

		// post content links
		$css	.= $class.' .content .entry-content a { ';

			if ( self::build_check( $data, 'post-entry-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['post-entry-link'] );

			if ( self::build_check( $data, 'post-entry-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['post-entry-link-border'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'post-entry-link-hov' ) )
			$css	.= $class.' .content .entry-content a:hover, '.$class.' .content .entry-content a:focus { '.self::hexcolor_css( 'color', $data['post-entry-link-hov'] ).'}'."\n";

		if ( self::build_check( $data, 'post-entry-caption-text' ) )
			$css	.= $class.' .wp-caption-text { '.self::hexcolor_css( 'color', $data['post-entry-caption-text'] ).'}'."\n";

		if ( self::build_check( $data, 'post-entry-caption-link' ) )
			$css	.= $class.' .wp-caption-text a { '.self::hexcolor_css( 'color', $data['post-entry-caption-link'] ).'}'."\n";

		if ( self::build_check( $data, 'post-entry-caption-link-hov' ) )
			$css	.= $class.' .wp-caption-text a:hover, '.$class.' .wp-caption-text a:focus { '.self::hexcolor_css( 'color', $data['post-entry-caption-link-hov'] ).'}'."\n";

		if ( self::build_check( $data, 'post-entry-list-ol' ) )
			$css	.= $class.' .content .entry-content ol, '.$class.' .content .entry-content ol li { '.self::text_css( 'list-style-type', $data['post-entry-list-ol'] ).'}'."\n";

		if ( self::build_check( $data, 'post-entry-list-ul' ) )
			$css	.= $class.' .content .entry-content ul, '.$class.' .content .entry-content ul li { '.self::text_css( 'list-style-type', $data['post-entry-list-ul'] ).'}'."\n";


		$css	.= '/* post footer */'."\n";

		// post entry footer wrapper
		$css	.= $class.' .entry-footer .entry-meta { ';

			if ( self::build_check( $data, 'post-footer-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['post-footer-stack'] );

			if ( self::build_check( $data, 'post-footer-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['post-footer-size'] );

			if ( self::build_check( $data, 'post-footer-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['post-footer-weight'] );

			if ( self::build_check( $data, 'post-footer-divider-color' ) )
				$css	.= self::hexcolor_css( 'border-top-color', $data['post-footer-divider-color'] );

			if ( self::build_check( $data, 'post-footer-divider-style' ) )
				$css	.= self::text_css( 'border-top-style', $data['post-footer-divider-style'] );

			if ( self::build_check( $data, 'post-footer-divider-width' ) )
				$css	.= self::px_css( 'border-top-width', $data['post-footer-divider-width'] );

			if ( self::build_check( $data, 'post-footer-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['post-footer-transform'] );

			if ( self::build_check( $data, 'post-footer-align' ) )
				$css	.= self::text_css( 'text-align', $data['post-footer-align'] );

		$css	.= '}'."\n";

		if ( self::build_check( $data, 'post-footer-link-border' ) )
			$css	.= $class.' .entry-footer .entry-meta a, '.$class.' .entry-footer .entry-meta a:hover, '.$class.' .entry-footer .entry-meta a:focus { '.self::text_css( 'border-bottom-style', $data['post-footer-link-border'] ).'}'."\n";

		if ( self::build_check( $data, 'post-footer-category-text' ) )
			$css	.= $class.' .entry-footer .entry-categories { '.self::hexcolor_css( 'color', $data['post-footer-category-text'] ).'}'."\n";

		if ( self::build_check( $data, 'post-footer-category-link' ) )
			$css	.= $class.' .entry-footer .entry-categories a { '.self::hexcolor_css( 'color', $data['post-footer-category-link'] ).'}'."\n";

		if ( self::build_check( $data, 'post-footer-category-link-hov' ) )
			$css	.= $class.' .entry-footer .entry-categories a:hover, '.$class.' .entry-footer .entry-categories a:focus { '.self::hexcolor_css( 'color', $data['post-footer-category-link-hov'] ).'}'."\n";

		if ( self::build_check( $data, 'post-footer-tag-text' ) )
			$css	.= $class.' .entry-footer .entry-tags { '.self::hexcolor_css( 'color', $data['post-footer-tag-text'] ).'}'."\n";

		if ( self::build_check( $data, 'post-footer-tag-link' ) )
			$css	.= $class.' .entry-footer .entry-tags a { '.self::hexcolor_css( 'color', $data['post-footer-tag-link'] ).'}'."\n";

		if ( self::build_check( $data, 'post-footer-tag-link-hov' ) )
			$css	.= $class.' .entry-footer .entry-tags a:hover, '.$class.' .entry-footer .entry-tags a:focus { '.self::hexcolor_css( 'color', $data['post-footer-tag-link-hov'] ).'}'."\n";


		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_post_content', $css, $data, $class );

		return $css;

	}

	function content_extras( $data, $class ) {

		$css	= '/* read more link */'."\n";

		// read more link setup
		$css	.= $class.' .entry-content a.more-link { ';

			if ( self::build_check( $data, 'extras-read-more-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-read-more-link'] );

			if ( self::build_check( $data, 'extras-read-more-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['extras-read-more-stack'] );

			if ( self::build_check( $data, 'extras-read-more-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['extras-read-more-size'] );

			if ( self::build_check( $data, 'extras-read-more-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['extras-read-more-weight'] );

			if ( self::build_check( $data, 'extras-read-more-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['extras-read-more-link-border'] );

		$css	.= '}'."\n";

		// read more link hover state setup
		$css	.= $class.' .entry-content a.more-link:hover, '.$class.' .entry-content a.more-link:focus { ';

			if ( self::build_check( $data, 'extras-read-more-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-read-more-link-hov'] );

			if ( self::build_check( $data, 'extras-read-more-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['extras-read-more-link-border'] );

		$css	.= '}'."\n";

		$css	.= '/* breadcrumbs */'."\n";

		// breadcrumb text
		$css	.= $class.' .breadcrumb { ';

			if ( self::build_check( $data, 'extras-breadcrumb-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-breadcrumb-text'] );

			if ( self::build_check( $data, 'extras-breadcrumb-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['extras-breadcrumb-stack'] );

			if ( self::build_check( $data, 'extras-breadcrumb-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['extras-breadcrumb-size'] );

			if ( self::build_check( $data, 'extras-breadcrumb-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['extras-breadcrumb-weight'] );

		$css	.= '}'."\n";

		// breadcrumb link
		if ( self::build_check( $data, 'extras-breadcrumb-link' ) )
			$css	.= $class.' .breadcrumb a { '.self::hexcolor_css( 'color', $data['extras-breadcrumb-link'] ).'}'."\n";

		// breadcrumb link hover state setup
		if ( self::build_check( $data, 'extras-breadcrumb-link-hov' ) )
			$css	.= $class.' .breadcrumb a:hover, '.$class.' .breadcrumb a:focus { '.self::hexcolor_css( 'color', $data['extras-breadcrumb-link-hov'] ).'}'."\n";


		$css	.= '/* pagination */'."\n";

		// typography for pagination
		$css	.= $class.' .pagination a { ';

			if ( self::build_check( $data, 'extras-pagination-text-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-pagination-text-link'] );

			if ( self::build_check( $data, 'extras-pagination-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['extras-pagination-stack'] );

			if ( self::build_check( $data, 'extras-pagination-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['extras-pagination-size'] );

			if ( self::build_check( $data, 'extras-pagination-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['extras-pagination-weight'] );

		$css	.= '}'."\n";

		$css	.= '/* text pagination */'."\n";

		// breadcrumb link hover state setup
		if ( self::build_check( $data, 'extras-pagination-text-link-hov' ) )
			$css	.= $class.' .pagination a:hover, '.$class.' .pagination a:focus { '.self::hexcolor_css( 'color', $data['extras-pagination-text-link-hov'] ).'}'."\n";

		$css	.= '/* numeric pagination */'."\n";

		// numeric pagination wrap
		$css	.= $class.' .archive-pagination li a { ';

			if ( self::build_check( $data, 'extras-pagination-numeric-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['extras-pagination-numeric-back'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-pagination-numeric-link'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-border-radius' ) )
				$css	.= self::px_css( 'border-radius', $data['extras-pagination-numeric-border-radius'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['extras-pagination-numeric-padding-top'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['extras-pagination-numeric-padding-bottom'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['extras-pagination-numeric-padding-left'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['extras-pagination-numeric-padding-right'] );

		$css	.= '}'."\n";

		// numeric pagination hover states wrap
		$css	.= $class.' .archive-pagination li a:hover, '.$class.' .archive-pagination li a:focus { ';

			if ( self::build_check( $data, 'extras-pagination-numeric-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['extras-pagination-numeric-back-hov'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-pagination-numeric-link-hov'] );

		$css	.= '}'."\n";

		// numeric pagination active item wrap
		$css	.= $class.' .archive-pagination li.active a { ';

			if ( self::build_check( $data, 'extras-pagination-numeric-active-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['extras-pagination-numeric-active-back'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-active-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-pagination-numeric-active-link'] );

		$css	.= '}'."\n";

		// numeric pagination active item hover state wrap
		$css	.= $class.' .archive-pagination li.active a:hover, '.$class.' .archive-pagination li.active a:focus { ';

			if ( self::build_check( $data, 'extras-pagination-numeric-active-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['extras-pagination-numeric-active-back-hov'] );

			if ( self::build_check( $data, 'extras-pagination-numeric-active-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-pagination-numeric-active-link-hov'] );

		$css	.= '}'."\n";

		$css	.= '/* author box */'."\n";

		// author box wrap
		$css	.= $class.' .author-box { ';

			if ( self::build_check( $data, 'extras-author-box-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['extras-author-box-back'] );

			if ( self::build_check( $data, 'extras-author-box-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['extras-author-box-padding-top'] );

			if ( self::build_check( $data, 'extras-author-box-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['extras-author-box-padding-bottom'] );

			if ( self::build_check( $data, 'extras-author-box-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['extras-author-box-padding-left'] );

			if ( self::build_check( $data, 'extras-author-box-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['extras-author-box-padding-right'] );

			if ( self::build_check( $data, 'extras-author-box-margin-top' ) )
				$css	.= self::px_rem_css( 'margin-top', $data['extras-author-box-margin-top'] );

			if ( self::build_check( $data, 'extras-author-box-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['extras-author-box-margin-bottom'] );

			if ( self::build_check( $data, 'extras-author-box-margin-left' ) )
				$css	.= self::px_rem_css( 'margin-left', $data['extras-author-box-margin-left'] );

			if ( self::build_check( $data, 'extras-author-box-margin-right' ) )
				$css	.= self::px_rem_css( 'margin-right', $data['extras-author-box-margin-right'] );

		$css	.= '}'."\n";

		// author name in author box
		$css	.= $class.' .author-box-title { ';

			if ( self::build_check( $data, 'extras-author-box-name-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-author-box-name-text'] );

			if ( self::build_check( $data, 'extras-author-box-name-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['extras-author-box-name-stack'] );

			if ( self::build_check( $data, 'extras-author-box-name-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['extras-author-box-name-size'] );

			if ( self::build_check( $data, 'extras-author-box-name-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['extras-author-box-name-weight'] );

			if ( self::build_check( $data, 'extras-author-box-name-align' ) )
				$css	.= self::text_css( 'text-align', $data['extras-author-box-name-align'] );

			if ( self::build_check( $data, 'extras-author-box-name-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['extras-author-box-name-transform'] );

		$css	.= '}'."\n";

		// author bio content in author box
		$css	.= $class.' .author-box-content { ';

			if ( self::build_check( $data, 'extras-author-box-bio-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-author-box-bio-text'] );

			if ( self::build_check( $data, 'extras-author-box-bio-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['extras-author-box-bio-stack'] );

			if ( self::build_check( $data, 'extras-author-box-bio-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['extras-author-box-bio-size'] );

			if ( self::build_check( $data, 'extras-author-box-bio-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['extras-author-box-bio-weight'] );

		$css	.= '}'."\n";


		// links in author bio
		$css	.= $class.' .author-box-content a { ';

			if ( self::build_check( $data, 'extras-author-box-bio-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-author-box-bio-link'] );

			if ( self::build_check( $data, 'extras-author-box-bio-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['extras-author-box-bio-link-border'] );

		$css	.= '}'."\n";

		// link hover state in author bio
		$css	.= $class.' .author-box-content a:hover, '.$class.' .author-box-content a:focus { ';

			if ( self::build_check( $data, 'extras-author-box-bio-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['extras-author-box-bio-link-hov'] );

			if ( self::build_check( $data, 'extras-author-box-bio-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['extras-author-box-bio-link-border'] );

		$css	.= '}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_content_extras', $css, $data, $class );

		// send it back
		return $css;

	}


	function comments_area( $data, $class ) {

		$css	= '/* comment list */'."\n";

		// wrapper for comment list
		$css	.= $class.' .entry-comments { ';

			if ( self::build_check( $data, 'comment-list-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['comment-list-back'] );

			if ( self::build_check( $data, 'comment-list-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['comment-list-padding-top'] );

			if ( self::build_check( $data, 'comment-list-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['comment-list-padding-bottom'] );

			if ( self::build_check( $data, 'comment-list-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['comment-list-padding-left'] );

			if ( self::build_check( $data, 'comment-list-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['comment-list-padding-right'] );

			if ( self::build_check( $data, 'comment-list-margin-top' ) )
				$css	.= self::px_rem_css( 'margin-top', $data['comment-list-margin-top'] );

			if ( self::build_check( $data, 'comment-list-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['comment-list-margin-bottom'] );

			if ( self::build_check( $data, 'comment-list-margin-left' ) )
				$css	.= self::px_rem_css( 'margin-left', $data['comment-list-margin-left'] );

			if ( self::build_check( $data, 'comment-list-margin-right' ) )
				$css	.= self::px_rem_css( 'margin-right', $data['comment-list-margin-right'] );

		$css	.= '}'."\n";

		// wrapper for multiple items
		$css	.= $class.' .entry-comments h3 { ';

			if ( self::build_check( $data, 'comment-list-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-list-title-text'] );

			if ( self::build_check( $data, 'comment-list-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-list-title-stack'] );

			if ( self::build_check( $data, 'comment-list-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-list-title-size'] );

			if ( self::build_check( $data, 'comment-list-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-list-title-weight'] );

			if ( self::build_check( $data, 'comment-list-title-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['comment-list-title-transform'] );

			if ( self::build_check( $data, 'comment-list-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['comment-list-title-align'] );

			if ( self::build_check( $data, 'comment-list-title-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['comment-list-title-margin-bottom'] );

		$css	.= '}'."\n";

		$css	.= '/* single comment */'."\n";

		// margins and padding for individual comments
		$css	.= $class.' .comment-list li { ';

			if ( self::build_check( $data, 'single-comment-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['single-comment-back'] );

			if ( self::build_check( $data, 'single-comment-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['single-comment-padding-top'] );

			if ( self::build_check( $data, 'single-comment-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['single-comment-padding-bottom'] );

			if ( self::build_check( $data, 'single-comment-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['single-comment-padding-left'] );

			if ( self::build_check( $data, 'single-comment-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['single-comment-padding-right'] );

			if ( self::build_check( $data, 'single-comment-margin-top' ) )
				$css	.= self::px_rem_css( 'margin-top', $data['single-comment-margin-top'] );

			if ( self::build_check( $data, 'single-comment-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['single-comment-margin-bottom'] );

			if ( self::build_check( $data, 'single-comment-margin-left' ) )
				$css	.= self::px_rem_css( 'margin-left', $data['single-comment-margin-left'] );

			if ( self::build_check( $data, 'single-comment-margin-right' ) )
				$css	.= self::px_rem_css( 'margin-right', $data['single-comment-margin-right'] );

		$css	.= '}'."\n";

		// wrapper for standard (non-author) comments - note the right side border is excluded.
		$css	.= $class.' li.comment { ';

			if ( self::build_check( $data, 'single-comment-standard-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['single-comment-standard-back'] );

			if ( self::build_check( $data, 'single-comment-standard-border-color' ) ) {
				$css	.= self::hexcolor_css( 'border-top-color', $data['single-comment-standard-border-color'] );
				$css	.= self::hexcolor_css( 'border-bottom-color', $data['single-comment-standard-border-color'] );
				$css	.= self::hexcolor_css( 'border-left-color', $data['single-comment-standard-border-color'] );
			}

			if ( self::build_check( $data, 'single-comment-standard-border-style' ) ) {
				$css	.= self::text_css( 'border-top-style', $data['single-comment-standard-border-style'] );
				$css	.= self::text_css( 'border-bottom-style', $data['single-comment-standard-border-style'] );
				$css	.= self::text_css( 'border-left-style', $data['single-comment-standard-border-style'] );
			}

			if ( self::build_check( $data, 'single-comment-standard-border-width' ) ) {
				$css	.= self::px_css( 'border-top-width', $data['single-comment-standard-border-width'] );
				$css	.= self::px_css( 'border-bottom-width', $data['single-comment-standard-border-width'] );
				$css	.= self::px_css( 'border-left-width', $data['single-comment-standard-border-width'] );
			}

		$css	.= '}'."\n";

		// wrapper for author comments - note the right side border is excluded.
		$css	.= $class.' li.bypostauthor { ';

			if ( self::build_check( $data, 'single-comment-author-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['single-comment-author-back'] );

			if ( self::build_check( $data, 'single-comment-author-border-color' ) ) {
				$css	.= self::hexcolor_css( 'border-top-color', $data['single-comment-author-border-color'] );
				$css	.= self::hexcolor_css( 'border-bottom-color', $data['single-comment-author-border-color'] );
				$css	.= self::hexcolor_css( 'border-left-color', $data['single-comment-author-border-color'] );
			}

			if ( self::build_check( $data, 'single-comment-author-border-style' ) ) {
				$css	.= self::text_css( 'border-top-style', $data['single-comment-author-border-style'] );
				$css	.= self::text_css( 'border-bottom-style', $data['single-comment-author-border-style'] );
				$css	.= self::text_css( 'border-left-style', $data['single-comment-author-border-style'] );
			}

			if ( self::build_check( $data, 'single-comment-author-border-width' ) ) {
				$css	.= self::px_css( 'border-top-width', $data['single-comment-author-border-width'] );
				$css	.= self::px_css( 'border-bottom-width', $data['single-comment-author-border-width'] );
				$css	.= self::px_css( 'border-left-width', $data['single-comment-author-border-width'] );
			}

		$css	.= '}'."\n";

		// setup for author name (base)
		$css	.= $class.' .comment-author { ';

			if ( self::build_check( $data, 'comment-element-name-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-name-text'] );

			if ( self::build_check( $data, 'comment-element-name-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-element-name-stack'] );

			if ( self::build_check( $data, 'comment-element-name-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-element-name-size'] );

			if ( self::build_check( $data, 'comment-element-name-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-element-name-weight'] );

		$css	.= '}'."\n";

		// setup for author name with link (base)
		$css	.= $class.' .comment-author a { ';

			if ( self::build_check( $data, 'comment-element-name-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-name-link'] );

			if ( self::build_check( $data, 'comment-element-name-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-name-link-border'] );

		$css	.= '}'."\n";

		// setup for author name with link hover (base)
		$css	.= $class.' .comment-author a:hover, '.$class.' .comment-author a:focus { ';

			if ( self::build_check( $data, 'comment-element-name-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-name-link-hov'] );

			if ( self::build_check( $data, 'comment-element-name-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-name-link-border'] );

		$css	.= '}'."\n";

		// setup for comment meta (base)
		$css	.= $class.' .comment-meta { ';

			if ( self::build_check( $data, 'comment-element-date-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-date-link'] );

			if ( self::build_check( $data, 'comment-element-date-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-element-date-stack'] );

			if ( self::build_check( $data, 'comment-element-date-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-element-date-size'] );

			if ( self::build_check( $data, 'comment-element-date-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-element-date-weight'] );

		$css	.= '}'."\n";

		// setup for comment meta (base)
		$css	.= $class.' .comment-meta a { ';

			if ( self::build_check( $data, 'comment-element-date-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-date-link'] );

			if ( self::build_check( $data, 'comment-element-date-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-date-link-border'] );

		$css	.= '}'."\n";

		// setup for comment meta link hover
		$css	.= $class.' .comment-meta a:hover, '.$class.' .comment-meta a:focus { ';

			if ( self::build_check( $data, 'comment-element-date-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-date-link-hov'] );

			if ( self::build_check( $data, 'comment-element-date-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-date-link-border'] );

		$css	.= '}'."\n";

		// comment body setup
		$css	.= $class.' .comment-content { ';

			if ( self::build_check( $data, 'comment-element-body-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-body-text'] );

			if ( self::build_check( $data, 'comment-element-body-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-element-body-stack'] );

			if ( self::build_check( $data, 'comment-element-body-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-element-body-size'] );

			if ( self::build_check( $data, 'comment-element-body-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-element-body-weight'] );

		$css	.= '}'."\n";

		// link setup for comment body
		$css	.= $class.' .comment-content a { ';

			if ( self::build_check( $data, 'comment-element-body-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-body-link'] );

			if ( self::build_check( $data, 'comment-element-body-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-body-link-border'] );

		$css	.= '}'."\n";

		// link hover setup for comment body
		$css	.= $class.' .comment-content a:hover, '.$class.' .comment-content a:focus { ';

			if ( self::build_check( $data, 'comment-element-body-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-body-link-hov'] );

			if ( self::build_check( $data, 'comment-element-body-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-body-link-border'] );

		$css	.= '}'."\n";

		// font alignment for comment reply
		if ( self::build_check( $data, 'comment-element-reply-align' ) )
			$css	.= $class.' .comment-reply { '.self::text_css( 'text-align', $data['comment-element-reply-align'] ).'}'."\n";

		// link setup for comment reply link
		$css	.= $class.' a.comment-reply-link { ';

			if ( self::build_check( $data, 'comment-element-reply-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-reply-link'] );

			if ( self::build_check( $data, 'comment-element-reply-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-element-reply-stack'] );

			if ( self::build_check( $data, 'comment-element-reply-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-element-reply-size'] );

			if ( self::build_check( $data, 'comment-element-reply-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-element-reply-weight'] );

			if ( self::build_check( $data, 'comment-element-reply-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-reply-link-border'] );

		$css	.= '}'."\n";

		// link hover setup for comment reply
		$css	.= $class.' a.comment-reply-link:hover, '.$class.' a.comment-reply-link:focus { ';

			if ( self::build_check( $data, 'comment-element-reply-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-element-reply-link-hov'] );

			if ( self::build_check( $data, 'comment-element-reply-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-element-reply-link-border'] );

		$css	.= '}'."\n";


		$css	.= '/* trackbacks */'."\n";

		// wrapper for trackback list
		$css	.= $class.' .entry-pings { ';

			if ( self::build_check( $data, 'trackback-list-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['trackback-list-back'] );

			if ( self::build_check( $data, 'trackback-list-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['trackback-list-padding-top'] );

			if ( self::build_check( $data, 'trackback-list-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['trackback-list-padding-bottom'] );

			if ( self::build_check( $data, 'trackback-list-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['trackback-list-padding-left'] );

			if ( self::build_check( $data, 'trackback-list-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['trackback-list-padding-right'] );

			if ( self::build_check( $data, 'trackback-list-margin-top' ) )
				$css	.= self::px_rem_css( 'margin-top', $data['trackback-list-margin-top'] );

			if ( self::build_check( $data, 'trackback-list-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['trackback-list-margin-bottom'] );

			if ( self::build_check( $data, 'trackback-list-margin-left' ) )
				$css	.= self::px_rem_css( 'margin-left', $data['trackback-list-margin-left'] );

			if ( self::build_check( $data, 'trackback-list-margin-right' ) )
				$css	.= self::px_rem_css( 'margin-right', $data['trackback-list-margin-right'] );

		$css	.= '}'."\n";

		// wrapper for multiple items
		$css	.= $class.' .entry-pings h3 { ';

			if ( self::build_check( $data, 'trackback-list-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-list-title-text'] );

			if ( self::build_check( $data, 'trackback-list-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['trackback-list-title-stack'] );

			if ( self::build_check( $data, 'trackback-list-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['trackback-list-title-size'] );

			if ( self::build_check( $data, 'trackback-list-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['trackback-list-title-weight'] );

			if ( self::build_check( $data, 'trackback-list-title-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['trackback-list-title-transform'] );

			if ( self::build_check( $data, 'trackback-list-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['trackback-list-title-align'] );

			if ( self::build_check( $data, 'trackback-list-title-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['trackback-list-title-margin-bottom'] );

		$css	.= '}'."\n";

		// setup for author name (base)
		$css	.= $class.' .entry-pings .comment-author { ';

			if ( self::build_check( $data, 'trackback-element-name-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-element-name-text'] );

			if ( self::build_check( $data, 'trackback-element-name-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['trackback-element-name-stack'] );

			if ( self::build_check( $data, 'trackback-element-name-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['trackback-element-name-size'] );

			if ( self::build_check( $data, 'trackback-element-name-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['trackback-element-name-weight'] );

		$css	.= '}'."\n";

		// setup for author name with link (base)
		$css	.= $class.' .entry-pings .comment-author a { ';

			if ( self::build_check( $data, 'trackback-element-name-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-element-name-link'] );

		$css	.= '}'."\n";

		// setup for author name with link hover (base)
		$css	.= $class.' .entry-pings .comment-author a:hover, '.$class.' .entry-pings .comment-author a:focus { ';

			if ( self::build_check( $data, 'trackback-element-name-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-element-name-link-hov'] );

		$css	.= '}'."\n";

		// setup for trackback meta (base)
		$css	.= $class.' .entry-pings .comment-metadata { ';

			if ( self::build_check( $data, 'trackback-element-date-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-element-date-link'] );

			if ( self::build_check( $data, 'trackback-element-date-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['trackback-element-date-stack'] );

			if ( self::build_check( $data, 'trackback-element-date-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['trackback-element-date-size'] );

			if ( self::build_check( $data, 'trackback-element-date-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['trackback-element-date-weight'] );

		$css	.= '}'."\n";

		// setup for trackback meta (base)
		$css	.= $class.' .entry-pings .comment-metadata a { ';

			if ( self::build_check( $data, 'trackback-element-date-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-element-date-link'] );

		$css	.= '}'."\n";

		// setup for trackback meta link hover
		$css	.= $class.' .entry-pings .comment-metadata a:hover, '.$class.' .entry-pings .comment-metadata a:focus { ';

			if ( self::build_check( $data, 'trackback-element-date-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-element-date-link-hov'] );

		$css	.= '}'."\n";

		// trackback body setup
		$css	.= $class.' .entry-pings .comment-content { ';

			if ( self::build_check( $data, 'trackback-element-body-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['trackback-element-body-text'] );

			if ( self::build_check( $data, 'trackback-element-body-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['trackback-element-body-stack'] );

			if ( self::build_check( $data, 'trackback-element-body-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['trackback-element-body-size'] );

			if ( self::build_check( $data, 'trackback-element-body-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['trackback-element-body-weight'] );

		$css	.= '}'."\n";

		$css	.= '/* comment form */'."\n";

		// wrapper for comment form
		$css	.= $class.' .comment-respond { ';

			if ( self::build_check( $data, 'comment-reply-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['comment-reply-back'] );

			if ( self::build_check( $data, 'comment-reply-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['comment-reply-padding-top'] );

			if ( self::build_check( $data, 'comment-reply-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['comment-reply-padding-bottom'] );

			if ( self::build_check( $data, 'comment-reply-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['comment-reply-padding-left'] );

			if ( self::build_check( $data, 'comment-reply-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['comment-reply-padding-right'] );

			if ( self::build_check( $data, 'comment-reply-margin-top' ) )
				$css	.= self::px_rem_css( 'margin-top', $data['comment-reply-margin-top'] );

			if ( self::build_check( $data, 'comment-reply-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['comment-reply-margin-bottom'] );

			if ( self::build_check( $data, 'comment-reply-margin-left' ) )
				$css	.= self::px_rem_css( 'margin-left', $data['comment-reply-margin-left'] );

			if ( self::build_check( $data, 'comment-reply-margin-right' ) )
				$css	.= self::px_rem_css( 'margin-right', $data['comment-reply-margin-right'] );

		$css	.= '}'."\n";

		// title setup for comment form title
		$css	.= $class.' .comment-respond h3 { ';

			if ( self::build_check( $data, 'comment-reply-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-title-text'] );

			if ( self::build_check( $data, 'comment-reply-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-reply-title-stack'] );

			if ( self::build_check( $data, 'comment-reply-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-reply-title-size'] );

			if ( self::build_check( $data, 'comment-reply-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-reply-title-weight'] );

			if ( self::build_check( $data, 'comment-reply-title-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['comment-reply-title-transform'] );

			if ( self::build_check( $data, 'comment-reply-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['comment-reply-title-align'] );

			if ( self::build_check( $data, 'comment-reply-title-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['comment-reply-title-margin-bottom'] );


		$css	.= '}'."\n";

		// displayed comment notes or logged in notice
		$css	.= $class.' p.comment-notes, '.$class.' p.logged-in-as { ';

			if ( self::build_check( $data, 'comment-reply-notes-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-notes-text'] );

			if ( self::build_check( $data, 'comment-reply-notes-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-reply-notes-stack'] );

			if ( self::build_check( $data, 'comment-reply-notes-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-reply-notes-size'] );

			if ( self::build_check( $data, 'comment-reply-notes-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-reply-notes-weight'] );

		$css	.= '}'."\n";

		// link setup for comment notes
		$css	.= $class.' p.comment-notes a, '.$class.' p.logged-in-as a { ';

			if ( self::build_check( $data, 'comment-reply-notes-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-notes-link'] );

			if ( self::build_check( $data, 'comment-reply-notes-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-reply-notes-link-border'] );

		$css	.= '}'."\n";

		// link hover setup for comment notes
		$css	.= $class.' p.comment-notes a:hover, '.$class.' p.comment-notes a:focus, '.$class.' p.logged-in-as a:hover '.$class.' p.logged-in-as a:focus { ';

			if ( self::build_check( $data, 'comment-reply-notes-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-notes-link-hov'] );

			if ( self::build_check( $data, 'comment-reply-notes-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['comment-reply-notes-link-border'] );

		$css	.= '}'."\n";

		// wrapper for the allowed tags area
		$css	.= $class.' p.form-allowed-tags { ';

			if ( self::build_check( $data, 'comment-reply-atags-base-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['comment-reply-atags-base-back'] );

			if ( self::build_check( $data, 'comment-reply-atags-base-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-atags-base-text'] );

			if ( self::build_check( $data, 'comment-reply-atags-base-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-reply-atags-base-stack'] );

			if ( self::build_check( $data, 'comment-reply-atags-base-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-reply-atags-base-size'] );

			if ( self::build_check( $data, 'comment-reply-atags-base-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-reply-atags-base-weight'] );

		$css	.= '}'."\n";

		// wrapper for multiple items
		$css	.= $class.' p.form-allowed-tags code { ';

			if ( self::build_check( $data, 'comment-reply-atags-code-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-atags-code-text'] );

			if ( self::build_check( $data, 'comment-reply-atags-code-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-reply-atags-code-stack'] );

			if ( self::build_check( $data, 'comment-reply-atags-code-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-reply-atags-code-size'] );

			if ( self::build_check( $data, 'comment-reply-atags-code-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-reply-atags-code-weight'] );

		$css	.= '}'."\n";

		$css	.= '/* comment form fields */'."\n";

		// paragraph wrap around comment input fields
		if ( self::build_check( $data, 'comment-reply-fields-input-margin-bottom' ) )
			$css	.= $class.' .comment-respond form p { '.self::px_rem_css( 'margin-bottom', $data['comment-reply-fields-input-margin-bottom'] ).'}'."\n";

		// comment form input labels
		$css	.= $class.' .comment-respond label { ';

			if ( self::build_check( $data, 'comment-reply-fields-label-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-fields-label-text'] );

			if ( self::build_check( $data, 'comment-reply-fields-label-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-reply-fields-label-stack'] );

			if ( self::build_check( $data, 'comment-reply-fields-label-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-reply-fields-label-size'] );

			if ( self::build_check( $data, 'comment-reply-fields-label-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-reply-fields-label-weight'] );

			if ( self::build_check( $data, 'comment-reply-fields-label-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['comment-reply-fields-label-transform'] );

			if ( self::build_check( $data, 'comment-reply-fields-label-align' ) )
				$css	.= self::text_css( 'text-align', $data['comment-reply-fields-label-align'] );

		$css	.= '}'."\n";

		// individual field setup
		$css	.= $class.' .comment-respond input[type="text"], '.$class.' .comment-respond input[type="email"], '.$class.' .comment-respond input[type="url"], '.$class.' .comment-respond textarea { ';

			if ( self::build_check( $data, 'comment-reply-fields-input-base-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['comment-reply-fields-input-base-back'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-reply-fields-input-text'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-reply-fields-input-stack'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-reply-fields-input-size'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-reply-fields-input-weight'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-base-border-color' ) )
				$css	.= self::hexcolor_css( 'border-color', $data['comment-reply-fields-input-base-border-color'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-border-style' ) )
				$css	.= self::text_css( 'border-style', $data['comment-reply-fields-input-border-style'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-border-width' ) )
				$css	.= self::px_css( 'border-width', $data['comment-reply-fields-input-border-width'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-border-radius' ) )
				$css	.= self::px_css( 'border-radius', $data['comment-reply-fields-input-border-radius'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-padding' ) )
				$css	.= self::px_rem_css( 'padding', $data['comment-reply-fields-input-padding'] );

		$css	.= '}'."\n";

		// focus state individual field setup
		$css	.= $class.' .comment-respond input[type="text"]:focus, '.$class.' .comment-respond input[type="email"]:focus, '.$class.' .comment-respond input[type="url"]:focus, '.$class.' .comment-respond textarea:focus { ';

			if ( self::build_check( $data, 'comment-reply-fields-input-focus-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['comment-reply-fields-input-focus-back'] );

			if ( self::build_check( $data, 'comment-reply-fields-input-focus-border-color' ) )
				$css	.= self::hexcolor_css( 'border-color', $data['comment-reply-fields-input-focus-border-color'] );

		$css	.= '}'."\n";

		// wrapper for multiple items
		$css	.= $class.' .comment-respond input#submit { ';

			if ( self::build_check( $data, 'comment-submit-button-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['comment-submit-button-back'] );

			if ( self::build_check( $data, 'comment-submit-button-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-submit-button-text'] );

			if ( self::build_check( $data, 'comment-submit-button-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['comment-submit-button-stack'] );

			if ( self::build_check( $data, 'comment-submit-button-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['comment-submit-button-size'] );

			if ( self::build_check( $data, 'comment-submit-button-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['comment-submit-button-weight'] );

			if ( self::build_check( $data, 'comment-submit-button-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['comment-submit-button-transform'] );

			if ( self::build_check( $data, 'comment-submit-button-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['comment-submit-button-padding-top'] );

			if ( self::build_check( $data, 'comment-submit-button-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['comment-submit-button-padding-bottom'] );

			if ( self::build_check( $data, 'comment-submit-button-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['comment-submit-button-padding-left'] );

			if ( self::build_check( $data, 'comment-submit-button-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['comment-submit-button-padding-right'] );

			if ( self::build_check( $data, 'comment-submit-button-border-radius' ) )
				$css	.= self::px_css( 'border-radius', $data['comment-submit-button-border-radius'] );

		$css	.= '}'."\n";

		// wrapper for multiple items
		$css	.= $class.' .comment-respond input#submit:hover, '.$class.' .comment-respond input#submit:focus { ';

			if ( self::build_check( $data, 'comment-submit-button-back-hov' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['comment-submit-button-back-hov'] );

			if ( self::build_check( $data, 'comment-submit-button-text-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['comment-submit-button-text-hov'] );

		$css	.= '}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_comments_area', $css, $data, $class );

		return $css;

	}

	function sidebar_widgets( $data, $class ) {

		$css	= '/* sidebar */'."\n";

		// wrapper for single widget setup
		$css	.= $class.' .sidebar .widget { ';

			if ( self::build_check( $data, 'sidebar-widget-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['sidebar-widget-back'] );

			if ( self::build_check( $data, 'sidebar-widget-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['sidebar-widget-padding-top'] );

			if ( self::build_check( $data, 'sidebar-widget-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['sidebar-widget-padding-bottom'] );

			if ( self::build_check( $data, 'sidebar-widget-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['sidebar-widget-padding-left'] );

			if ( self::build_check( $data, 'sidebar-widget-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['sidebar-widget-padding-right'] );

			if ( self::build_check( $data, 'sidebar-widget-margin-top' ) )
				$css	.= self::px_rem_css( 'margin-top', $data['sidebar-widget-margin-top'] );

			if ( self::build_check( $data, 'sidebar-widget-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['sidebar-widget-margin-bottom'] );

			if ( self::build_check( $data, 'sidebar-widget-margin-left' ) )
				$css	.= self::px_rem_css( 'margin-left', $data['sidebar-widget-margin-left'] );

			if ( self::build_check( $data, 'sidebar-widget-margin-right' ) )
				$css	.= self::px_rem_css( 'margin-right', $data['sidebar-widget-margin-right'] );

			if ( self::build_check( $data, 'sidebar-widget-border-radius' ) )
				$css	.= self::px_css( 'border-radius', $data['sidebar-widget-border-radius'] );

		$css	.= '}'."\n";

		// sidebar widget titles
		$css	.= $class.' .sidebar .widget .widget-title { ';

			if ( self::build_check( $data, 'sidebar-widget-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['sidebar-widget-title-text'] );

			if ( self::build_check( $data, 'sidebar-widget-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['sidebar-widget-title-stack'] );

			if ( self::build_check( $data, 'sidebar-widget-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['sidebar-widget-title-size'] );

			if ( self::build_check( $data, 'sidebar-widget-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['sidebar-widget-title-weight'] );

			if ( self::build_check( $data, 'sidebar-widget-title-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['sidebar-widget-title-transform'] );

			if ( self::build_check( $data, 'sidebar-widget-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['sidebar-widget-title-align'] );

			if ( self::build_check( $data, 'sidebar-widget-title-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['sidebar-widget-title-margin-bottom'] );

		$css	.= '}'."\n";

		// sidebar widget titles
		$css	.= $class.' .sidebar .widget { ';

			if ( self::build_check( $data, 'sidebar-widget-content-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['sidebar-widget-content-text'] );

			if ( self::build_check( $data, 'sidebar-widget-content-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['sidebar-widget-content-stack'] );

			if ( self::build_check( $data, 'sidebar-widget-content-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['sidebar-widget-content-size'] );

			if ( self::build_check( $data, 'sidebar-widget-content-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['sidebar-widget-content-weight'] );

		$css	.= '}'."\n";

		// sidebar content lists
		if ( self::build_check( $data, 'sidebar-widget-content-list-ol' ) )
			$css	.= $class.' .sidebar .widget ol li { '.self::text_css( 'list-style-type', $data['sidebar-widget-content-list-ol'] ).'}'."\n";

		if ( self::build_check( $data, 'sidebar-widget-content-list-ul' ) )
			$css	.= $class.' .sidebar .widget ul li { '.self::text_css( 'list-style-type', $data['sidebar-widget-content-list-ul'] ).'}'."\n";

		// link setup widget content
		$css	.= $class.' .sidebar .widget a { ';

			if ( self::build_check( $data, 'sidebar-widget-content-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['sidebar-widget-content-link'] );

			if ( self::build_check( $data, 'sidebar-widget-content-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['sidebar-widget-content-link-border'] );

		$css	.= '}'."\n";

		// link hover setup for comment notes
		$css	.= $class.' .sidebar .widget a:hover, '.$class.' .sidebar .widget a:focus { ';

			if ( self::build_check( $data, 'sidebar-widget-content-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['sidebar-widget-content-link-hov'] );

			if ( self::build_check( $data, 'sidebar-widget-content-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['sidebar-widget-content-link-border'] );

		$css	.= '}'."\n";


		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_main_sidebar', $css, $data, $class );

		return $css;

	}

	function footer_widgets( $data, $class ) {

		$css	= '/* footer widgets */'."\n";


		// setup for row containing footer widgets
		$css	.= $class.' .footer-widgets { ';

			if ( self::build_check( $data, 'footer-widget-row-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['footer-widget-row-back'] );

			if ( self::build_check( $data, 'footer-widget-row-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['footer-widget-row-padding-top'] );

			if ( self::build_check( $data, 'footer-widget-row-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['footer-widget-row-padding-bottom'] );

			if ( self::build_check( $data, 'footer-widget-row-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['footer-widget-row-padding-left'] );

			if ( self::build_check( $data, 'footer-widget-row-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['footer-widget-row-padding-right'] );

		$css	.= '}'."\n";


		// wrapper for footer widget titles
		$css	.= $class.' .footer-widgets .widget .widget-title { ';

			if ( self::build_check( $data, 'footer-widget-title-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['footer-widget-title-text'] );

			if ( self::build_check( $data, 'footer-widget-title-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['footer-widget-title-stack'] );

			if ( self::build_check( $data, 'footer-widget-title-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['footer-widget-title-size'] );

			if ( self::build_check( $data, 'footer-widget-title-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['footer-widget-title-weight'] );

			if ( self::build_check( $data, 'footer-widget-title-transform' ) )
				$css	.= self::text_css( 'text-transform', $data['footer-widget-title-transform'] );

			if ( self::build_check( $data, 'footer-widget-title-align' ) )
				$css	.= self::text_css( 'text-align', $data['footer-widget-title-align'] );

			if ( self::build_check( $data, 'footer-widget-title-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['footer-widget-title-margin-bottom'] );

		$css	.= '}'."\n";

		// footer widget content
		$css	.= $class.' .footer-widgets .widget { ';

			if ( self::build_check( $data, 'footer-widget-single-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['footer-widget-single-back'] );

			if ( self::build_check( $data, 'footer-widget-single-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['footer-widget-single-padding-top'] );

			if ( self::build_check( $data, 'footer-widget-single-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['footer-widget-single-padding-bottom'] );

			if ( self::build_check( $data, 'footer-widget-single-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['footer-widget-single-padding-left'] );

			if ( self::build_check( $data, 'footer-widget-single-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['footer-widget-single-padding-right'] );

			if ( self::build_check( $data, 'footer-widget-single-margin-bottom' ) )
				$css	.= self::px_rem_css( 'margin-bottom', $data['footer-widget-single-margin-bottom'] );

			if ( self::build_check( $data, 'footer-widget-single-border-radius' ) )
				$css	.= self::px_css( 'border-radius', $data['footer-widget-single-border-radius'] );

			if ( self::build_check( $data, 'footer-widget-content-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['footer-widget-content-text'] );

			if ( self::build_check( $data, 'footer-widget-content-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['footer-widget-content-stack'] );

			if ( self::build_check( $data, 'footer-widget-content-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['footer-widget-content-size'] );

			if ( self::build_check( $data, 'footer-widget-content-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['footer-widget-content-weight'] );

		$css	.= '}'."\n";

		// footer content lists
		if ( self::build_check( $data, 'footer-widget-content-list-ol' ) )
			$css	.= $class.' .footer-widgets .widget ol li { '.self::text_css( 'list-style-type', $data['footer-widget-content-list-ol'] ).'}'."\n";

		if ( self::build_check( $data, 'footer-widget-content-list-ul' ) )
			$css	.= $class.' .footer-widgets .widget ul li { '.self::text_css( 'list-style-type', $data['footer-widget-content-list-ul'] ).'}'."\n";

		// link setup footer widget content
		$css	.= $class.' .footer-widgets .widget a { ';

			if ( self::build_check( $data, 'footer-widget-content-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['footer-widget-content-link'] );

			if ( self::build_check( $data, 'footer-widget-content-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['footer-widget-content-link-border'] );

		$css	.= '}'."\n";

		// link hover setup footer widget content
		$css	.= $class.' .footer-widgets .widget a:hover, '.$class.' .footer-widgets .widget a:focus { ';

			if ( self::build_check( $data, 'footer-widget-content-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['footer-widget-content-link-hov'] );

			if ( self::build_check( $data, 'footer-widget-content-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['footer-widget-content-link-border'] );

		$css	.= '}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_footer_widgets', $css, $data, $class );

		return $css;

	}

	function footer_main( $data, $class ) {

		$css	= '/* footer main */'."\n";

		// container for site footer
		$css	.= $class.' .site-footer { ';

			if ( self::build_check( $data, 'footer-main-back' ) )
				$css	.= self::hexcolor_css( 'background-color', $data['footer-main-back'] );

			if ( self::build_check( $data, 'footer-main-padding-top' ) )
				$css	.= self::px_rem_css( 'padding-top', $data['footer-main-padding-top'] );

			if ( self::build_check( $data, 'footer-main-padding-bottom' ) )
				$css	.= self::px_rem_css( 'padding-bottom', $data['footer-main-padding-bottom'] );

			if ( self::build_check( $data, 'footer-main-padding-left' ) )
				$css	.= self::px_rem_css( 'padding-left', $data['footer-main-padding-left'] );

			if ( self::build_check( $data, 'footer-main-padding-right' ) )
				$css	.= self::px_rem_css( 'padding-right', $data['footer-main-padding-right'] );

		$css	.= '}'."\n";

		// container for paragraphs inside site footer (to exclude possible footer menus)
		$css	.= $class.' .site-footer p { ';

			if ( self::build_check( $data, 'footer-main-content-text' ) )
				$css	.= self::hexcolor_css( 'color', $data['footer-main-content-text'] );

			if ( self::build_check( $data, 'footer-main-content-stack' ) )
				$css	.= self::stack_css( 'font-family', $data['footer-main-content-stack'] );

			if ( self::build_check( $data, 'footer-main-content-size' ) )
				$css	.= self::px_rem_css( 'font-size', $data['footer-main-content-size'] );

			if ( self::build_check( $data, 'footer-main-content-weight' ) )
				$css	.= self::number_css( 'font-weight', $data['footer-main-content-weight'] );

			if ( self::build_check( $data, 'footer-main-content-align' ) )
				$css	.= self::text_css( 'text-align', $data['footer-main-content-align'] );

		$css	.= '}'."\n";

		// wrapper for site footer links
		$css	.= $class.' .site-footer p a { ';

			if ( self::build_check( $data, 'footer-main-content-link' ) )
				$css	.= self::hexcolor_css( 'color', $data['footer-main-content-link'] );

			if ( self::build_check( $data, 'footer-main-content-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['footer-main-content-link-border'] );

		$css	.= '}'."\n";

		// wrapper for site footer link hover
		$css	.= $class.' .site-footer p a:hover, '.$class.' .site-footer p a:focus { ';

			if ( self::build_check( $data, 'footer-main-content-link-hov' ) )
				$css	.= self::hexcolor_css( 'color', $data['footer-main-content-link-hov'] );

			if ( self::build_check( $data, 'footer-main-content-link-border' ) )
				$css	.= self::text_css( 'border-bottom-style', $data['footer-main-content-link-border'] );

		$css	.= '}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_footer_main', $css, $data, $class );

		return $css;

	}

	function responsive_wide( $data, $class ) {

		$css	= '/* responsive elements */'."\n";

		// wrapper for multiple items
		$css	.= '@media only screen and (max-width: 1023px) {'."\n";

			if ( self::build_check( $data, 'body-color-back-thin' ) )
				$css	.= "\t".$class.' { '.self::hexcolor_css( 'background-color', $data['body-color-back-thin'] ).'}'."\n";

		$css	.= '}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_responsive_wide', $css, $data, $class );

		return $css;

	}

	function retina_specific( $data, $class ) {

		$css	= '/* retina elements */'."\n";

		// wrapper for multiple items
		$css	.= '@media only screen and (-webkit-min-device-pixel-ratio: 1.5),'."\n";
		$css	.= "\t".'only screen and (-moz-min-device-pixel-ratio: 1.5),'."\n";
		$css	.= "\t".'only screen and (-o-min-device-pixel-ratio: 3/2),'."\n";
		$css	.= "\t".'only screen and (min-device-pixel-ratio: 1.5) { '."\n\n";

		if ( self::build_check( $data, 'site-header-img-retina' ) )
			$css	.= 'body.gppro-header-image .site-header .wrap { '.self::image_css( 'background', $data['site-header-img-retina'], 'no-repeat left' ).' background-size: 320px 165px; }'."\n";
/*
		if ( isset( $data['site-header-img-retina'] ) && !empty( $data['site-header-img-retina'] ) && $data['site-header-img-retina'] !== GP_Pro_Helper::get_default( 'site-header-img-retina' ) ) :
			// wrapper for multiple items
			$css	.= "\t".'body.gppro-header-image .site-header .wrap {'."\n";

				$css	.= "\t\t".'background: url( "'.$data['site-header-img-retina'].'" ) no-repeat left; '."\n";
				$css	.= "\t\t".'background-size: 320px 165px;'."\n";

			$css	.= "\t".'}'."\n";

		endif;
*/
		$css	.= '}'."\n";

		// check for inline add-ons
		$css	= apply_filters( 'gppro_css_inline_retina_specific', $css, $data, $class );

		return $css;

	}



	// build it man

	function build_css() {

		$data	= get_option( 'gppro-settings' );

		if ( !$data )
			return false;

		$class	= apply_filters( 'gppro_body_class', 'gppro-custom' );
		$class	= 'body.'.esc_attr( $class );

		$css	= '/* custom CSS generated '.date( 'r', time() ).' */'."\n\n";

		$css	.= self::general_body( $data, $class );
		$css	.= self::header_area( $data, $class );
		$css	.= self::navigation( $data, $class );
		$css	.= self::home_content( $data, $class );
		$css	.= self::post_content( $data, $class );
		$css	.= self::content_extras( $data, $class );
		$css	.= self::comments_area( $data, $class );
		$css	.= self::sidebar_widgets( $data, $class );
		$css	.= self::footer_widgets( $data, $class );
		$css	.= self::footer_main( $data, $class );
		$css	.= self::responsive_wide( $data, $class );
		$css	.= self::retina_specific( $data, $class );


		// grab any custom and include it
		$css	.= apply_filters( 'gppro_css_builder', null, $data, $class );

		// send it all back
		return $css;

	}

} // end class
