<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if ( ! class_exists( 'Mega_Menu_Mobile_Submenu_Direction' ) ) :

class Mega_Menu_Mobile_Submenu_Direction {

	private const DEFAULT_MOBILE_BACK_LINK_TEXT = '{parent_title}';

	/**
	 * Init hooks.
	 */
	public function __construct() {
		add_filter( 'megamenu_mobile_direction_options', [ $this, 'add_mobile_direction_option' ], 10, 2 );
		add_filter( 'megamenu_wrap_attributes', [ $this, 'set_mobile_direction_attribute' ], 10, 5 );
		add_filter( 'megamenu_load_scss_file_contents', [ $this, 'append_scss' ], 10 );
		add_filter( 'megamenu_default_theme', [ $this, 'add_back_link_theme_defaults' ], 10 );
		add_filter( 'megamenu_theme_editor_settings', [ $this, 'add_theme_editor_back_link_settings' ], 10 );
		add_filter( 'megamenu_scss_variables', [ $this, 'add_mobile_direction_scss_variable' ], 10, 5 );
		add_filter( 'megamenu_scss_variables', [ $this, 'remove_back_link_text_from_scss_variables' ], 10, 5 );
	}

	/**
	 * Adds the Sliding Panels submenu direction option.
	 *
	 * @param array  $options  Existing options.
	 * @param string $selected Selected value.
	 * @return array
	 */
	public function add_mobile_direction_option( $options, $selected ) {
		$options['horizontal'] = [
			'label'    => __( 'Sliding Panels', 'megamenu-pro' ),
			'selected' => 'horizontal' === $selected,
			'disabled' => '',
		];

		return $options;
	}

	/**
	 * Ensures side-to-side direction only applies to off-canvas mobile menus.
	 *
	 * @param array  $attributes         Menu attributes.
	 * @param int    $menu_id            Menu ID.
	 * @param array  $location_settings  Location settings.
	 * @param array  $global_settings    Global Mega Menu settings.
	 * @param string $current_theme_location Theme location slug.
	 * @return array
	 */
	public function set_mobile_direction_attribute( $attributes, $menu_id, $location_settings, $global_settings, $current_theme_location = '' ) {
		$effect = isset( $location_settings['effect_mobile'] ) ? $location_settings['effect_mobile'] : 'disabled';
		$is_off_canvas = in_array( $effect, [ 'slide_left', 'slide_right' ], true );
		$direction = isset( $location_settings['effect_mobile_direction'] ) ? $location_settings['effect_mobile_direction'] : 'vertical';

		$back_text = '';
		if ( $current_theme_location && class_exists( 'Mega_Menu_Location' ) && class_exists( 'Mega_Menu_Theme' ) ) {
			$location_obj = Mega_Menu_Location::find( $current_theme_location );
			if ( $location_obj ) {
				$theme = Mega_Menu_Theme::find( $location_obj->get_theme_id() );
				$back_text = (string) $theme->get( 'mobile_menu_off_canvas_back_link_text', '' );
			}
		}

		if ( $back_text === '' && ! empty( $location_settings['effect_mobile_back_text'] ) ) {
			$back_text = (string) $location_settings['effect_mobile_back_text'];
		}

		if ( $back_text === '' ) {
			$back_text = self::DEFAULT_MOBILE_BACK_LINK_TEXT;
		}

		$attributes['data-mobile-direction'] = ( $is_off_canvas && 'horizontal' === $direction ) ? 'horizontal' : 'vertical';
		$attributes['data-mobile-back-text'] = do_shortcode( $back_text );

		return $attributes;
	}

	/**
	 * Text templates are not SCSS values; omit so compilation does not fail.
	 *
	 * @param array<string, string> $vars            SCSS variables.
	 * @param string                $location_id     Location ID.
	 * @param array                 $theme_settings  Theme settings.
	 * @param int|string            $menu_id         Menu ID.
	 * @param string                $theme_id        Theme ID.
	 * @return array<string, string>
	 */
	/**
	 * Exposes the resolved mobile direction as a SCSS variable so the
	 * mobile-submenu.scss @if guard can skip output when not needed.
	 *
	 * @param array<string, string> $vars
	 * @param string                $location_id
	 * @param array                 $theme_settings
	 * @param int|string            $menu_id
	 * @param string                $theme_id
	 * @return array<string, string>
	 */
	public function add_mobile_direction_scss_variable( $vars, $location_id, $theme_settings, $menu_id, $theme_id ) {
		$location_settings = get_option( 'megamenu_settings' );
		$effect            = isset( $location_settings[ $location_id ]['effect_mobile'] ) ? $location_settings[ $location_id ]['effect_mobile'] : 'disabled';
		$is_off_canvas     = in_array( $effect, [ 'slide_left', 'slide_right' ], true );
		$direction         = isset( $location_settings[ $location_id ]['effect_mobile_direction'] ) ? $location_settings[ $location_id ]['effect_mobile_direction'] : 'vertical';

		$vars['effect_mobile_direction'] = ( $is_off_canvas && 'horizontal' === $direction ) ? 'horizontal' : 'vertical';

		return $vars;
	}

	public function remove_back_link_text_from_scss_variables( $vars, $location_id, $theme_settings, $menu_id, $theme_id ) {
		unset( $vars['mobile_menu_off_canvas_back_link_text'] );

		return $vars;
	}

	/**
	 * Default theme keys for sliding-panels back link styling (compiled as SCSS variables).
	 *
	 * @param array $theme Default theme settings.
	 * @return array
	 */
	public function add_back_link_theme_defaults( $theme ) {
		$theme['mobile_menu_off_canvas_back_link_text']                    = self::DEFAULT_MOBILE_BACK_LINK_TEXT;
		$theme['mobile_menu_off_canvas_back_link_color']                   = '#666';
		$theme['mobile_menu_off_canvas_back_link_font_size']               = 'mobile_menu_item_link_font_size';
		$theme['mobile_menu_off_canvas_back_link_weight']                  = 'bold';
		$theme['mobile_menu_off_canvas_back_link_text_align']              = 'center';
		$theme['mobile_menu_off_canvas_back_link_height']                  = '60px';
		$theme['mobile_menu_off_canvas_back_link_background_from']           = 'transparent';
		$theme['mobile_menu_off_canvas_back_link_background_to']             = 'transparent';
		$theme['mobile_menu_off_canvas_back_link_background_hover_from']   = 'transparent';
		$theme['mobile_menu_off_canvas_back_link_background_hover_to']     = 'transparent';

		return $theme;
	}

	/**
	 * Theme editor: back link groups under Mobile → Off Canvas Settings.
	 *
	 * @param array $settings Theme editor tab settings.
	 * @return array
	 */
	public function add_theme_editor_back_link_settings( $settings ) {
		if ( ! isset( $settings['mobile_menu']['settings'] ) || ! is_array( $settings['mobile_menu']['settings'] ) ) {
			return $settings;
		}

		$group_sliding_panel_header = [
			'priority'    => 70.5,
			'title'       => __( 'Sliding Panel Settings', 'megamenu-pro' ),
			'description' => '',
			'description' => __( 'These options only apply if the Mobile Menu Type is set to "Off Canvas" and the Submenu Style is set to "Sliding Panels".', 'megamenu' ),

		];

		$group_back_text = [
			'priority'    => 71,
			'title'       => __( 'Back Link Text', 'megamenu-pro' ),
			'description' => __( 'Use {parent_title} as a placeholder. Shortcodes are supported.', 'megamenu-pro' ),
			'settings'    => [
				[
					'title' => '',
					'type'  => 'freetext',
					'key'   => 'mobile_menu_off_canvas_back_link_text',
				],
			],
		];

		$group_font = [
			'priority'    => 72,
			'title'       => __( 'Back Link Font', 'megamenu-pro' ),
			'description' => __( 'Typography for the sliding-panels back link in side-to-side mobile submenus.', 'megamenu-pro' ),
			'settings'    => [
				[
					'title' => __( 'Color', 'megamenu-pro' ),
					'type'  => 'color',
					'key'   => 'mobile_menu_off_canvas_back_link_color',
				],
				[
					'title' => __( 'Size', 'megamenu-pro' ),
					'type'  => 'freetext',
					'key'   => 'mobile_menu_off_canvas_back_link_font_size',
					'validation' => 'px',
				],
				[
					'title' => __( 'Weight', 'megamenu-pro' ),
					'type'  => 'weight',
					'key'   => 'mobile_menu_off_canvas_back_link_weight',
				],
				[
					'title' => __( 'Align', 'megamenu-pro' ),
					'type'  => 'align',
					'key'   => 'mobile_menu_off_canvas_back_link_text_align',
				],
			],
		];

		$group_back_link_height = [
			'priority'    => 72.5,
			'title'       => __( 'Back Link Height', 'megamenu-pro' ),
			'description' => __( 'Minimum height of the back link row in side-to-side mobile submenus.', 'megamenu-pro' ),
			'settings'    => [
				[
					'title' => '',
					'type'  => 'freetext',
					'key'   => 'mobile_menu_off_canvas_back_link_height',
					'validation' => 'px',
				],
			],
		];

		$group_background = [
			'priority'    => 73,
			'title'       => __( 'Back Link Background', 'megamenu-pro' ),
			'description' => __( 'Background for the sliding-panels back link row.', 'megamenu-pro' ),
			'settings'    => [
				[
					'title' => __( 'From', 'megamenu' ),
					'type'  => 'color',
					'key'   => 'mobile_menu_off_canvas_back_link_background_from',
				],
				[
					'title' => __( 'Copy', 'megamenu' ),
					'type'  => 'copy_color',
					'key'   => 'copy_color',
				],
				[
					'title' => __( 'To', 'megamenu' ),
					'type'  => 'color',
					'key'   => 'mobile_menu_off_canvas_back_link_background_to',
				],
			],
		];

		$group_background_hover = [
			'priority'    => 74,
			'title'       => __( 'Back Link Background (Hover)', 'megamenu-pro' ),
			'description' => __( 'Background when the back link is hovered or keyboard-focused.', 'megamenu-pro' ),
			'settings'    => [
				[
					'title' => __( 'From', 'megamenu' ),
					'type'  => 'color',
					'key'   => 'mobile_menu_off_canvas_back_link_background_hover_from',
				],
				[
					'title' => __( 'Copy', 'megamenu' ),
					'type'  => 'copy_color',
					'key'   => 'copy_color',
				],
				[
					'title' => __( 'To', 'megamenu' ),
					'type'  => 'color',
					'key'   => 'mobile_menu_off_canvas_back_link_background_hover_to',
				],
			],
		];

		$ordered = [];
		$inserted = false;

		foreach ( $settings['mobile_menu']['settings'] as $group_key => $group_value ) {
			$ordered[ $group_key ] = $group_value;

			if ( 'mobile_menu_off_canvas_close_button' === $group_key ) {
				$ordered['mobile_menu_sliding_panel_header']                   = $group_sliding_panel_header;
				$ordered['mobile_menu_off_canvas_back_link_text']              = $group_back_text;
				$ordered['mobile_menu_off_canvas_back_link_font']              = $group_font;
				$ordered['mobile_menu_off_canvas_back_link_height']            = $group_back_link_height;
				$ordered['mobile_menu_off_canvas_back_link_background']        = $group_background;
				$ordered['mobile_menu_off_canvas_back_link_background_hover']  = $group_background_hover;
				$inserted = true;
			}
		}

		if ( ! $inserted ) {
			$ordered['mobile_menu_sliding_panel_header']                   = $group_sliding_panel_header;
			$ordered['mobile_menu_off_canvas_back_link_text']              = $group_back_text;
			$ordered['mobile_menu_off_canvas_back_link_font']              = $group_font;
			$ordered['mobile_menu_off_canvas_back_link_height']            = $group_back_link_height;
			$ordered['mobile_menu_off_canvas_back_link_background']        = $group_background;
			$ordered['mobile_menu_off_canvas_back_link_background_hover']  = $group_background_hover;
		}

		$settings['mobile_menu']['settings'] = $ordered;

		return $settings;
	}

	/**
	 * Appends side-to-side mobile submenu SCSS to the compiled menu stylesheet.
	 *
	 * @param string $scss Existing SCSS.
	 * @return string
	 */
	public function append_scss( $scss ) {
		$path = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'scss/mobile-submenu.scss';
		$contents = file_get_contents( $path );

		return $scss . $contents;
	}
}

endif;
