<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists('Mega_Menu_Custom_Fonts') ) :

/**
 *
 */
class Mega_Menu_Custom_Fonts {

	/**
	 * Array of custom fonts
	 */
	var $enabled_fonts = array();


	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->set_enabled_fonts();

		add_filter( 'megamenu_fonts', array( $this, 'add_fonts_to_theme_font_list' ), 10 );
		add_filter( 'megamenu_theme_font_select_structure', array( $this, 'add_custom_fonts_to_theme_select_structure' ), 15, 3 );
		add_action( 'megamenu_general_settings', array( $this, 'add_custom_font_row' ), 21 );


	}


	/**
	 * Retrieve the enabled custom fonts from the settings
	 *
	 * @since 1.0
	 */
	private function set_enabled_fonts() {

		$settings = get_option("megamenu_settings");

		if ( isset( $settings['fonts']['custom'] ) ) {

			$this->enabled_fonts = array_filter( explode( "\n", $settings['fonts']['custom'] ) );

		}

	}



	/**
	 * Add the specified custom fonts to the existing list of Available fonts
	 *
	 * @since 1.0
	 * @param array $fonts
	 * @return array
	 */
	public function add_fonts_to_theme_font_list( $fonts ) {

		if ( count( $this->enabled_fonts ) ) {
			foreach ( $this->enabled_fonts as $font ) {
				$fonts[] = trim( $font );
			}
		}

		return $fonts;
	}


	/**
	 * Register Custom Fonts as their own optgroup in the Theme Editor font control.
	 *
	 * @since 2.4.7
	 * @param array<int, array<string, mixed>> $structure
	 * @param string                             $key
	 * @param string                             $decoded_value
	 * @return array<int, array<string, mixed>>
	 */
	public function add_custom_fonts_to_theme_select_structure( $structure, $key, $decoded_value ) {

		if ( ! count( $this->enabled_fonts ) ) {
			return $structure;
		}

		$items = array();

		foreach ( $this->enabled_fonts as $font ) {
			$t = trim( $font );
			if ( '' === $t ) {
				continue;
			}
			$orig    = stripslashes( $t );
			$parts   = explode( ',', $orig );
			$items[] = array(
				'value' => $orig,
				'label' => trim( $parts[0] ),
			);
		}

		if ( ! count( $items ) ) {
			return $structure;
		}

		$block = array(
			'type'  => 'optgroup',
			'label' => __( 'Custom Fonts', 'megamenu-pro' ),
			'items' => $items,
		);

		return $this->insert_theme_font_select_block_after( $structure, 'local', $block );
	}


	/**
	 * @param array<int, array<string, mixed>> $structure
	 * @param string                             $after_id Block `id` to insert after.
	 * @param array<string, mixed>               $new_block
	 * @return array<int, array<string, mixed>>
	 */
	private function insert_theme_font_select_block_after( $structure, $after_id, $new_block ) {

		$out = array();

		foreach ( $structure as $block ) {
			$out[] = $block;
			if ( isset( $block['id'] ) && $after_id === $block['id'] ) {
				$out[] = $new_block;
			}
		}

		return $out;
	}


	/**
	 * Add the custom font text area to the General Settings page
	 *
	 * @since 1.0
	 * @param array $settings
	 */
	public function add_custom_font_row( $settings ) {

		?>

        <h3><?php _e("Fonts", "megamenu-pro"); ?></h3>

        <table>
            <tr>
                <td class='mega-name'>
                    <?php _e("Custom Fonts", "megamenu-pro"); ?>
                    <div class='mega-description'>
                    	<?php _e("Specify custom fonts to be made available in the Font dropdowns in the Theme Editor (one per line)", "megamenu-pro"); ?>
                    </div>
                </td>
                <td class='mega-value'>

                	<?php
                	    echo "<textarea name='settings[fonts][custom]'>";

                	    if (isset($settings['fonts']['custom'])) {
                	    	echo esc_textarea(stripslashes($settings['fonts']['custom']));
                	    }

                	    echo "</textarea>";
                	?>

                </td>
            </tr>
        </table>

		<?php

	}

}

endif;