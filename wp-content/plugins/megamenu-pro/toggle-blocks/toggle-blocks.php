<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists('Mega_Menu_Pro_Toggle_Blocks') && class_exists( 'Mega_Menu_Toggle_Blocks' ) ) :

/**
 * Mobile Toggle Blocks
 */
class Mega_Menu_Pro_Toggle_Blocks extends Mega_Menu_Toggle_Blocks {

    /**
     * Constructor
     *
     * @since 1.4
     */
    public function __construct() {

        // Generic
        add_filter( 'megamenu_load_scss_file_contents', array( $this, 'append_scss'), 10 );

        // Logo Block
        add_filter( 'megamenu_scss_variables', array( $this, 'logo_add_block_vars_to_scss'), 10, 5 );
        add_filter( 'megamenu_registered_toggle_blocks', array( $this, 'logo_add_as_available_block_option') );
        add_action( 'wp_ajax_mm_get_toggle_block_logo', array( $this, 'logo_output_block_admin' ) );
        add_action( 'megamenu_output_admin_toggle_block_logo', array( $this, 'logo_output_block_admin'), 10, 2 );
        add_filter( 'megamenu_output_public_toggle_block_logo', array( $this, 'logo_output_block_public'), 10, 2 );

        // Menu Toggle - Custom Block
        add_filter( 'megamenu_scss_variables', array( $this, 'menu_toggle_custom_add_block_vars_to_scss'), 10, 5 );
        add_filter( 'megamenu_registered_toggle_blocks', array( $this, 'menu_toggle_custom_add_as_available_block_option') );
        add_action( 'wp_ajax_mm_get_toggle_block_menu_toggle_custom', array( $this, 'menu_toggle_custom_output_block_admin' ) );
        add_action( 'megamenu_output_admin_toggle_block_menu_toggle_custom', array( $this, 'menu_toggle_custom_output_block_admin'), 10, 2 );
        add_filter( 'megamenu_output_public_toggle_block_menu_toggle_custom', array( $this, 'menu_toggle_custom_output_block_public'), 10, 2 );
        add_filter( 'megamenu_toggle_block_attributes', array( $this, 'menu_toggle_custom_add_class' ), 10, 6);

        // Search Block
        add_filter( 'megamenu_scss_variables', array( $this, 'search_add_block_vars_to_scss'), 10, 5 );
        add_filter( 'megamenu_registered_toggle_blocks', array( $this, 'search_add_as_available_block_option') );
        add_action( 'wp_ajax_mm_get_toggle_block_search', array( $this, 'search_output_block_admin' ) );
        add_action( 'megamenu_output_admin_toggle_block_search', array( $this, 'search_output_block_admin'), 10, 2 );
        add_filter( 'megamenu_output_public_toggle_block_search', array( $this, 'search_output_block_public'), 10, 2 );

        // HTML Block
        add_filter( 'megamenu_registered_toggle_blocks', array( $this, 'html_add_as_available_block_option') );
        add_action( 'wp_ajax_mm_get_toggle_block_html', array( $this, 'html_output_block_admin' ) );
        add_action( 'megamenu_output_admin_toggle_block_html', array( $this, 'html_output_block_admin'), 10, 2 );
        add_filter( 'megamenu_output_public_toggle_block_html', array( $this, 'html_output_block_public'), 10, 2 );

        // Icon Block
        add_filter( 'megamenu_scss_variables', array( $this, 'icon_add_block_vars_to_scss'), 10, 5 );
        add_filter( 'megamenu_registered_toggle_blocks', array( $this, 'icon_add_as_available_block_option') );
        add_action( 'wp_ajax_mm_get_toggle_block_icon', array( $this, 'icon_output_block_admin' ) );
        add_action( 'megamenu_output_admin_toggle_block_icon', array( $this, 'icon_output_block_admin'), 10, 2 );
        add_filter( 'megamenu_output_public_toggle_block_icon', array( $this, 'icon_output_block_public'), 10, 2 );

    }

    /**
     * Add "Logo" as a drop down option to the toggle block editor
     *
     * @since 1.4
     * @param array $options
     * @return array
     */
    public function logo_add_as_available_block_option( $options ) {
        $options['logo'] = __("Logo / Image", "megamenu-pro");

        return $options;

    }

    /**
     * Output the logo block HTML (front end)
     *
     * @since 1.4
     * @param string $html
     * @param array $settings
     * @return string
     */
    public function logo_output_block_public( $html, $settings ) {

        $src = "";
        $id = "";
        $alt = "";
        $url = "";
        $target = "";

        if ( isset( $settings['logo_id'] ) ) {
            $id = (int) $settings['logo_id'];
            $logo = wp_get_attachment_image_src( $id, 'full' );
            if ( is_array( $logo ) && isset( $logo[0] ) ) {
                $src = $logo[0];
                $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
            }
        }

        $logo_attributes = apply_filters( "megamenu_toggle_logo_attributes", array(
            'class' => 'mega-menu-logo',
            'src' => $src,
            'alt' => $alt
        ), $settings );


        if ( isset( $settings['url'] ) ) {
            $url = do_shortcode( stripslashes( html_entity_decode( $settings['url'], ENT_QUOTES ) ) );
        } else {
            $url = get_home_url();
        }

        if ( isset( $settings['target'] ) ) {
            $target = $settings['target'];
        } else {
            $target = '_self';
        }

        $anchor_attributes = apply_filters( "megamenu_toggle_logo_anchor_attributes", array(
            'class' => 'mega-menu-logo',
            'href' => $url,
            'target' => $target
        ), $settings );

        $html = "<a";

        foreach ( $anchor_attributes as $name => $val ) {
            if ( strlen( $val ) ) {
                $html .= " " . $name .'="' . esc_attr( $val ) . '"';
            }
        }

        $html .= "><img";

        foreach ( $logo_attributes as $name => $val ) {
            if ( strlen( $val ) ) {
                $html .= " " . $name .'="' . esc_attr( $val ) . '"';
            }
        }

        $html .= " /></a>";

        return apply_filters("megamenu_toggle_block_logo_html", $html, $settings, $url, $src);

    }


    /**
     * Output the HTML for the "Menu Toggle" block settings
     *
     * @since 1.4
     * @param int $block_id
     * @param array $settings
     */
    public function logo_output_block_admin( $block_id, $settings = array() ) {

        if ( empty( $settings ) ) {
            $block_id = "0";
        }

        if ( isset( $settings['vertical_offset'] ) && ! strlen( $settings['vertical_offset'] ) ) {
            unset( $settings['vertical_offset'] );
        }


        if ( isset( $settings['max_height'] ) && ! strlen( $settings['max_height'] ) ) {
            unset( $settings['vertical_offset'] );
        }

        $defaults = array(
            "align" => "left",
            "logo_id" => 0,
            "url" => home_url(),
            "logo_src" => "",
            "vertical_offset" => "-1px",
            "max_height" => "40px",
            "target" => "_self"
        );

        $logo_src = "";

        $settings = array_merge( $defaults, $settings );

        if ( isset( $settings['logo_id'] ) && (int) $settings['logo_id'] > 0 ) {
            $logo = wp_get_attachment_image_src( (int) $settings['logo_id'], 'thumbnail' );
            if ( is_array( $logo ) && isset( $logo[0] ) ) {
                $settings['logo_src'] = $logo[0];
            }
        }

        ?>

        <div class='block'>
            <div class='block-title'><span title='<?php _e("Logo", "megamenu-pro"); ?>' class="dashicons dashicons-format-image"></span></div>
            <div class='block-settings'>
                <?php $this->print_toggle_block_panel_header( __( 'Logo Settings', 'megamenu-pro' ) ); ?>
                <input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='logo' />
                <input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align'] ?>'>
                <table class="mmm-settings-table">
                    <tbody>
                        <tr>
                            <td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Image', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Select the logo image to display.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Media File', 'megamenu-pro' ); ?></span>
                                    <div class='mmm_image_selector' data-src='<?php echo $settings['logo_src']; ?>' data-field='logo_id_<?php echo $block_id; ?>'></div>
                                    <input type='hidden' id='logo_id_<?php echo $block_id; ?>' name='toggle_blocks[<?php echo $block_id; ?>][logo_id]' value='<?php echo $settings['logo_id']; ?>' />
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Link', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Where the logo links to.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'URL', 'megamenu-pro' ); ?></span><input type='text' class='logo_url' name='toggle_blocks[<?php echo $block_id; ?>][url]' value='<?php echo esc_attr( stripslashes( $settings['url'] ) ) ?>' /></label>
                                <label><span class='mega-short-desc'><?php _e( 'New Window', 'megamenu-pro' ); ?></span><select name='toggle_blocks[<?php echo $block_id; ?>][target]'>
                                    <option value='_self' <?php selected( $settings['target'], '_self' ) ?>><?php _e( 'No', 'megamenu-pro' ) ?></option>
                                    <option value='_blank' <?php selected( $settings['target'], '_blank' ) ?>><?php _e( 'Yes', 'megamenu-pro' ) ?></option>
                                </select></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Display', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Adjust the logo size and alignment within the toggle bar.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Vertical Offset', 'megamenu-pro' ); ?></span><input type='text' class='logo_vertical_offset' name='toggle_blocks[<?php echo $block_id; ?>][vertical_offset]' value='<?php echo $settings['vertical_offset'] ?>' /></label>
                                <label><span class='mega-short-desc'><?php _e( 'Max Height', 'megamenu-pro' ); ?></span><input type='text' class='logo_max_height' name='toggle_blocks[<?php echo $block_id; ?>][max_height]' value='<?php echo $settings['max_height'] ?>' /></label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php $this->print_toggle_block_panel_footer(); ?>
            </div>
        </div>

        <?php
    }


    /**
     * Create a new variable containing the toggle blocks to be used by the SCSS file
     *
     * @param array $vars
     * @param string $location
     * @param string $theme
     * @param int $menu_id
     * @param string $theme_id
     * @return array - all custom SCSS vars
     * @since 1.4
     */
    public function logo_add_block_vars_to_scss( $vars, $location, $theme, $menu_id, $theme_id ) {

        $toggle_blocks = $this->get_toggle_blocks_for_theme( $theme_id );

        $logo_blocks = array();

        if ( is_array( $toggle_blocks ) ) {

            foreach( $toggle_blocks as $index => $settings ) {

                if ( isset( $settings['type'] ) && $settings['type'] == 'logo' ) {

                    $logo_src = "";

                    if ( isset( $settings['logo_id'] ) ) {
                        $logo = wp_get_attachment_image_src( (int) $settings['logo_id'], 'thumbnail' );
                        if ( is_array( $logo ) && isset( $logo[0] ) ) {
                            $logo_src = $logo[0];
                        }
                    }

                    $styles = array(
                        'id' => $index,
                        'logo_id' => isset($settings['logo_id']) ? "'" . $settings['logo_id'] . "'" : "0",
                        'logo_src' => "'" . $logo_src . "'",
                        'logo_url' => isset($settings['url']) ? "'" . $settings['url'] . "'" : "''",
                        'logo_vertical_offset' => isset($settings['vertical_offset']) && strlen($settings['vertical_offset']) ? $settings['vertical_offset'] : "-1px",
                        'logo_max_height' => isset($settings['max_height']) && strlen($settings['max_height']) ? $settings['max_height'] : "40px",
                    );

                    $logo_blocks[ $index ] = $styles;
                }

            }
        }

        //$menu_toggle_blocks(
        // (123, red, 150px),
        // (456, green, null),
        // (789, blue, 90%),());
        if ( count( $logo_blocks ) ) {

            $list = "(";

            foreach ( $logo_blocks as $id => $vals ) {
                $list .= "(" . implode( ",", $vals ) . "),";
            }

            // Always add an empty list item to meke sure there are always at least 2 items in the list
            // Lists with a single item are not treated the same way by SASS
            $list .= "());";

            $vars['logo_blocks'] = $list;

        } else {

            $vars['logo_blocks'] = "()";

        }

        return $vars;
    }


    /**
     * Add "Menu Toggle - Custom" as a drop down option to the toggle block editor
     *
     * @since 2.1.2
     * @param array $options
     * @return array
     */
    public function menu_toggle_custom_add_as_available_block_option( $options ) {
        $options['menu_toggle_custom'] = __("Menu Toggle (Custom)", "megamenu-pro");

        return $options;

    }


    /**
     * Add 'mega-menu-toggle-animated-block' as an extra block class, to allow clicking on it to open/close the sub menu
     *
     * @since 2.1.2
     * @param array $options
     * @return array
     */
    public function menu_toggle_custom_add_class( $atts, $block, $content, $nav_menu, $args, $theme_id ) {
        if ( $block['type'] == 'menu_toggle_custom' ) {
            $atts['class'] = $atts['class'] .= ' mega-menu-toggle-animated-block';
        }
        return $atts;

    }


    /**
     * Output the Menu Toggle - Custom block HTML (front end)
     *
     * @since 2.1.2
     * @param string $html
     * @param array $settings
     * @return string
     */
    public function menu_toggle_custom_output_block_public( $html, $settings ) {

        $open_img = "";
        $closed_img = "";

        if ( isset( $settings['open_id'] ) ) {
            $id = (int) $settings['open_id'];
            $open_src_data = $id ? wp_get_attachment_image_src( $id, 'full' ) : false;
            if ( is_array( $open_src_data ) && isset( $open_src_data[0] ) ) {
                $src = $open_src_data[0];
                $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

                $open_img_attributes = apply_filters( "megamenu_menu_toggle_custom_open_attributes", array(
                    'src' => $src,
                    'alt' => $alt
                ), $settings );

                $open_img = "<img";

                foreach ( $open_img_attributes as $name => $val ) {
                    if ( strlen( $val ) ) {
                        $open_img .= " " . $name .'="' . esc_attr( $val ) . '"';
                    }
                }

                $open_img .= "></img>";
            }
        }

        if ( isset( $settings['closed_id'] ) ) {
            $id = (int) $settings['closed_id'];
            $closed_src_data = $id ? wp_get_attachment_image_src( $id, 'full' ) : false;
            if ( is_array( $closed_src_data ) && isset( $closed_src_data[0] ) ) {
                $src = $closed_src_data[0];
                $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

                $closed_img_attributes = apply_filters( "megamenu_menu_toggle_custom_closed_attributes", array(
                    'src' => $src,
                    'alt' => $alt
                ), $settings );

                $closed_img = "<img";

                foreach ( $closed_img_attributes as $name => $val ) {
                    if ( strlen( $val ) ) {
                        $closed_img .= " " . $name .'="' . esc_attr( $val ) . '"';
                    }
                }

                $closed_img .= "></img>";
            }
        }

        $html = '<span class="mega-menu-toggle-block mega-toggle-label" role="button" aria-expanded="false" tabindex="0">';
        $html .=     '<span class="mega-toggle-label-closed">';
        $html .=         $closed_img;
        $html .=     '</span>';
        $html .=     '<span class="mega-toggle-label-open">';
        $html .=         $open_img;
        $html .=     '</span>';
        $html .= '</span>';

        return apply_filters("megamenu_toggle_block_menu_toggle_custom_html", $html, $settings);

    }


    /**
     * Output the HTML for the "Menu Toggle - Custom" block settings
     *
     * @since 2.1.2
     * @param int $block_id
     * @param array $settings
     */
    public function menu_toggle_custom_output_block_admin( $block_id, $settings = array() ) {

        if ( empty( $settings ) ) {
            $block_id = "0";
        }

        $defaults = array(
            "align" => 'left',
            "open_id" => 0,
            "closed_id" => 0,
            "open_src" => "",
            "closed_src" => ""
        );

        $settings = array_merge( $defaults, $settings );

        if ( isset( $settings['open_id'] ) && (int) $settings['open_id'] > 0 ) {
            $open_img = wp_get_attachment_image_src( (int) $settings['open_id'], 'thumbnail' );
            if ( is_array( $open_img ) && isset( $open_img[0] ) ) {
                $settings['open_src'] = $open_img[0];
            }
        }

        if ( isset( $settings['closed_id'] ) && (int) $settings['closed_id'] > 0 ) {
            $closed_img = wp_get_attachment_image_src( (int) $settings['closed_id'], 'thumbnail' );
            if ( is_array( $closed_img ) && isset( $closed_img[0] ) ) {
                $settings['closed_src'] = $closed_img[0];
            }
        }

        ?>

        <div class='block'>
            <div class='block-title'><?php _e("TOGGLE", "megamenu-pro"); ?> <span title='<?php _e("Menu Toggle", "megamenu-pro"); ?>' class="dashicons dashicons-menu"></span></div>
            <div class='block-settings'>
                <?php $this->print_toggle_block_panel_header( __( 'Menu Toggle (Custom) Settings', 'megamenu-pro' ) ); ?>
                <input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='menu_toggle_custom' />
                <input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align'] ?>'>
                <table class="mmm-settings-table">
                    <tbody>
                        <tr>
                            <td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Closed', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Image shown when the menu is closed.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Image', 'megamenu-pro' ); ?></span>
                                    <div class='mmm_image_selector' data-src='<?php echo $settings['closed_src']; ?>' data-field='closed_id_<?php echo $block_id; ?>'></div>
                                    <input type='hidden' id='closed_id_<?php echo $block_id; ?>' name='toggle_blocks[<?php echo $block_id; ?>][closed_id]' value='<?php echo $settings['closed_id']; ?>' />
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mega-name mega-name-wide"><div class="mega-name-title"><?php _e( 'Open', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Image shown when the menu is open.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Image', 'megamenu-pro' ); ?></span>
                                    <div class='mmm_image_selector' data-src='<?php echo $settings['open_src']; ?>' data-field='open_id_<?php echo $block_id; ?>'></div>
                                    <input type='hidden' id='open_id_<?php echo $block_id; ?>' name='toggle_blocks[<?php echo $block_id; ?>][open_id]' value='<?php echo $settings['open_id']; ?>' />
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php $this->print_toggle_block_panel_footer(); ?>
            </div>
        </div>

        <?php
    }



    /**
     * Create a new variable containing the toggle blocks to be used by the SCSS file
     *
     * @param array $vars
     * @param string $location
     * @param string $theme
     * @param int $menu_id
     * @param string $theme_id
     * @return array - all custom SCSS vars
     * @since 2.1.2
     */
    public function menu_toggle_custom_add_block_vars_to_scss( $vars, $location, $theme, $menu_id, $theme_id ) {

        $toggle_blocks = $this->get_toggle_blocks_for_theme( $theme_id );

        $menu_toggle_custom_blocks = array();

        if ( is_array( $toggle_blocks ) ) {

            foreach( $toggle_blocks as $index => $settings ) {

                if ( isset( $settings['type'] ) && $settings['type'] == 'menu_toggle_custom' ) {

                    $styles = array(
                        'id' => $index
                    );

                    $menu_toggle_custom_blocks[ $index ] = $styles;
                }

            }
        }

        //$menu_toggle_blocks(
        // (123, red, 150px),
        // (456, green, null),
        // (789, blue, 90%),());
        if ( count( $menu_toggle_custom_blocks ) ) {

            $list = "(";

            foreach ( $menu_toggle_custom_blocks as $id => $vals ) {
                $list .= "(" . implode( ",", $vals ) . "),";
            }

            // Always add an empty list item to meke sure there are always at least 2 items in the list
            // Lists with a single item are not treated the same way by SASS
            $list .= "());";

            $vars['menu_toggle_custom_blocks'] = $list;

        } else {

            $vars['menu_toggle_custom_blocks'] = "()";

        }

        return $vars;
    }


    /**
     * Add "Search" as a drop down option to the toggle block editor
     *
     * @since 1.4
     * @param array $options
     * @return array
     */
    public function search_add_as_available_block_option( $options ) {
        $options['search'] = __("Search", "megamenu-pro");

        return $options;

    }


    /**
     * Return the default settings for a search block
     *
     * @since 1.4
     * @return array
     */
    private function get_search_block_defaults() {

        $defaults = array(
            "align" => "left",
            "search_type" => 'expand_to_right',
            "height" => '25px',
            "width" => '200px',
            "text_color" => '#333',
            "icon_color_closed" => '#fff',
            "icon_color_open" => '#333',
            "background_color_closed" => 'transparent',
            "background_color_open" => '#fff',
            "border_radius" => '2px',
            "placeholder_text" => 'Search',
            "woocommerce" => 'false',
            "vertical_offset" => '0px'
        );

        return $defaults;
    }


    /**
     * Output the search block HTML (front end)
     *
     * @since 1.4
     * @param string $html
     * @param array $settings
     * @return string
     */
    public function search_output_block_public( $html, $settings ) {

        $defaults = $this->get_search_block_defaults();

        $settings = array_merge( $defaults, $settings );

        $name = apply_filters("megamenu_search_var", "s");
        $action = apply_filters("megamenu_search_action", trailingslashit( home_url() ) );
        $placeholder = esc_attr( do_shortcode( stripslashes( html_entity_decode( $settings['placeholder_text'], ENT_QUOTES ) ) ) );

        $inputs = "";

        $search_input_attributes = apply_filters( "megamenu_toggle_search_input_attributes", array(
            'type' => 'text',
            'aria-label' => $placeholder,
            'data-placeholder' => $placeholder,
            'name' => $name
        ), $html, $settings );

        if ( $settings['search_type'] == 'static'  && isset( $search_input_attributes['data-placeholder'] ) ) {
            $search_input_attributes['placeholder'] = $search_input_attributes['data-placeholder'];
            unset( $search_input_attributes['data-placeholder'] );
        }

        $search_input = "<input ";

        foreach ( $search_input_attributes as $name => $val ) {
            if ( strlen( $val ) ) {
                $search_input .= " " . $name ."='" . esc_attr( $val ) . "'";
            }
        }

        $search_input .= " />";

        if ($settings['woocommerce'] === 'true') {
            $inputs = "<input type='hidden' name='post_type' value='product' />";
        }

        if ( $settings['search_type'] == 'expand_to_left' ) {
            $html = "<div class='mega-search-wrap'><form class='mega-search expand-to-left mega-search-closed' action='" . $action . "'>
                        <span class='dashicons dashicons-search search-icon'></span>
                        <input type='submit' value='" . __( "Search" , "megamenu-pro" ) . "'>
                        {$search_input}
                        " . apply_filters("megamenu_search_inputs", $inputs) . "
                    </form></div>";
        }

        if ( $settings['search_type'] == 'expand_to_right' ) {
            $html = "<div class='mega-search-wrap'><form class='mega-search expand-to-right mega-search-closed' action='" .  $action . "'>
                        <span class='dashicons dashicons-search search-icon'></span>
                        <input type='submit' value='" . __( "Search" , "megamenu-pro" ) . "'>
                        {$search_input}
                        " . apply_filters("megamenu_search_inputs", $inputs) . "
                    </form></div>";
        }

        if ( $settings['search_type'] == 'static' ) {
            $html = "<div class='mega-search-wrap mega-static'><form class='mega-search static mega-search-open' action='" . $action . "'>
                        <span class='dashicons dashicons-search search-icon'></span>
                        <input type='submit' value='" . __( "Search" , "megamenu-pro" ) . "'>
                        {$search_input}
                        " . apply_filters("megamenu_search_inputs", $inputs) . "
                    </form></div>";
        }

        return apply_filters("megamenu_toggle_search_replacement_html", $html);

    }


    /**
     * Output the HTML for the "Search" block settings
     *
     * @since 1.4
     * @param int $block_id
     * @param array $settings
     */
    public function search_output_block_admin( $block_id, $settings = array() ) {

        if ( empty( $settings ) ) {
            $block_id = "0";
        }

        $defaults = $this->get_search_block_defaults();

        if ( isset( $settings['height'] ) && ! strlen( $settings['height'] ) ) {
            unset( $settings['height'] );
        }


        if ( isset( $settings['width'] ) && ! strlen( $settings['width'] ) ) {
            unset( $settings['width'] );
        }

        if ( isset( $settings['border_radius'] ) && ! strlen( $settings['border_radius'] ) ) {
            unset( $settings['border_radius'] );
        }

        if ( isset( $settings['vertical_offset'] ) && ! strlen( $settings['vertical_offset'] ) ) {
            unset( $settings['vertical_offset'] );
        }

        $settings = array_merge( $defaults, $settings );

        ?>

        <div class='block'>
            <div class='block-title'><span title='<?php _e("Search", "megamenu-pro"); ?>' class="dashicons dashicons-search"></span></div>
            <div class='block-settings'>
                <?php $this->print_toggle_block_panel_header( __( 'Search Settings', 'megamenu-pro' ) ); ?>
                <input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='search' />
                <input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align'] ?>'>
                <table class="mmm-settings-table">
                    <tbody>
                        <tr>
                            <td class="mega-name"><div class="mega-name-title"><?php _e( 'Search', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Style and size of the search input.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Style', 'megamenu-pro' ); ?></span><select name='toggle_blocks[<?php echo $block_id; ?>][search_type]'>
                                    <option value='expand_to_left' <?php selected( $settings['search_type'], 'expand_to_left' ); ?>><?php _e( 'Expand to Left', 'megamenu-pro' ) ?></option>
                                    <option value='expand_to_right' <?php selected( $settings['search_type'], 'expand_to_right' ); ?>><?php _e( 'Expand to Right', 'megamenu-pro' ) ?></option>
                                    <option value='static' <?php selected( $settings['search_type'], 'static' ); ?>><?php _e( 'Static', 'megamenu-pro' ) ?></option>
                                </select></label>
                                <label><span class='mega-short-desc'><?php _e( 'Height', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][height]' value='<?php echo $settings['height']; ?>' /></label>
                                <label><span class='mega-short-desc'><?php _e( 'Width', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][width]' value='<?php echo $settings['width']; ?>' /></label>
                                <label><span class='mega-short-desc'><?php _e( 'Placeholder', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][placeholder_text]' value='<?php echo stripslashes( esc_attr( $settings['placeholder_text'] ) ); ?>' /></label>
                                <label><span class='mega-short-desc'><?php _e( 'Border Radius', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][border_radius]' value='<?php echo $settings['border_radius']; ?>' /></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mega-name"><div class="mega-name-title"><?php _e( 'Colors (Closed)', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Colors when the search is collapsed.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Icon', 'megamenu-pro' ); ?></span><?php $this->print_toggle_color_option( 'icon_color_closed', $block_id, $settings['icon_color_closed'] ); ?></label>
                                <label><span class='mega-short-desc'><?php _e( 'Background', 'megamenu-pro' ); ?></span><?php $this->print_toggle_color_option( 'background_color_closed', $block_id, $settings['background_color_closed'] ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mega-name"><div class="mega-name-title"><?php _e( 'Colors (Open)', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Colors when the search is expanded.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Text', 'megamenu-pro' ); ?></span><?php $this->print_toggle_color_option( 'text_color', $block_id, $settings['text_color'] ); ?></label>
                                <label><span class='mega-short-desc'><?php _e( 'Icon', 'megamenu-pro' ); ?></span><?php $this->print_toggle_color_option( 'icon_color_open', $block_id, $settings['icon_color_open'] ); ?></label>
                                <label><span class='mega-short-desc'><?php _e( 'Background', 'megamenu-pro' ); ?></span><?php $this->print_toggle_color_option( 'background_color_open', $block_id, $settings['background_color_open'] ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mega-name"><div class="mega-name-title"><?php _e( 'Position', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Vertical alignment within the toggle bar.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Vertical Offset', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][vertical_offset]' value='<?php echo $settings['vertical_offset']; ?>' /></label>
                            </td>
                        </tr>
                        <?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>
                        <tr>
                            <td class="mega-name"><div class="mega-name-title"><?php _e( 'WooCommerce', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Search WooCommerce products instead of all content.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Use WooCommerce Search', 'megamenu-pro' ); ?></span><select name='toggle_blocks[<?php echo $block_id; ?>][woocommerce]'>
                                    <option value='true' <?php selected( $settings['woocommerce'], 'true' ) ?>><?php _e( 'Yes', 'megamenu-pro' ) ?></option>
                                    <option value='false' <?php selected( $settings['woocommerce'], 'false' ) ?>><?php _e( 'No', 'megamenu-pro' ) ?></option>
                                </select></label>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php $this->print_toggle_block_panel_footer(); ?>
            </div>
        </div>

        <?php
    }


    /**
     * Create a new variable containing the toggle blocks to be used by the SCSS file
     *
     * @param array $vars
     * @param string $location
     * @param string $theme
     * @param int $menu_id
     * @param string $theme_id
     * @return array - all custom SCSS vars
     * @since 1.4
     */
    public function search_add_block_vars_to_scss( $vars, $location, $theme, $menu_id, $theme_id ) {

        $toggle_blocks = $this->get_toggle_blocks_for_theme( $theme_id );

        $search_blocks = array();

        $defaults = $this->get_search_block_defaults();

        if ( is_array( $toggle_blocks ) ) {

            foreach( $toggle_blocks as $index => $settings ) {

                if ( isset( $settings['type'] ) && $settings['type'] == 'search' ) {

                    $styles = array(
                        'id' => $index,
                        'search_height' => isset($settings['height']) && strlen($settings['height']) ? $settings['height'] : $defaults['height'],
                        'search_text_color' => isset($settings['text_color']) ? $settings['text_color'] : $defaults['text_color'],
                        'search_icon_color_closed' => isset($settings['icon_color_closed']) ? $settings['icon_color_closed'] : $defaults['icon_color_closed'],
                        'search_icon_color_open' => isset($settings['icon_color_open']) ? $settings['icon_color_open'] : $defaults['icon_color_open'],
                        'search_background_color_closed' => isset($settings['background_color_closed']) ? $settings['background_color_closed'] : $defaults['background_color_closed'],
                        'search_background_color_open' => isset($settings['background_color_open']) ? $settings['background_color_open'] : $defaults['background_color_open'],
                        'search_border_radius' => isset($settings['border_radius']) && strlen($settings['border_radius']) ? $settings['border_radius'] : $defaults['border_radius'],
                        'search_vertical_offset' => isset($settings['vertical_offset']) && strlen($settings['vertical_offset']) ? $settings['vertical_offset'] : $defaults['vertical_offset'],
                        'search_width' => isset($settings['width']) && strlen($settings['width']) ? $settings['width'] : $defaults['width']
                    );

                    $search_blocks[ $index ] = $styles;
                }

            }
        }

        //$menu_toggle_blocks(
        // (123, red, 150px),
        // (456, green, null),
        // (789, blue, 90%),());
        if ( count( $search_blocks ) ) {

            $list = "(";

            foreach ( $search_blocks as $id => $vals ) {
                $list .= "(" . implode( ",", $vals ) . "),";
            }

            // Always add an empty list item to meke sure there are always at least 2 items in the list
            // Lists with a single item are not treated the same way by SASS
            $list .= "());";

            $vars['search_blocks'] = $list;

        } else {

            $vars['search_blocks'] = "()";

        }

        return $vars;
    }




    /**
     * Add "HTML" as a drop down option to the toggle block editor
     *
     * @since 1.4
     * @param array $options
     * @return array
     */
    public function html_add_as_available_block_option( $options ) {
        $options['html'] = __("HTML", "megamenu-pro");

        return $options;

    }


    /**
     * Output the HTML (front end)
     *
     * @since 1.4
     * @param string $html
     * @param array $settings
     * @return string
     */
    public function html_output_block_public( $html, $settings ) {

        return do_shortcode( wp_unslash( $settings['html'] ) );

    }


    /**
     * Output the HTML for the "HTML" block settings
     *
     * @since 1.4
     * @param int $block_id
     * @param array $settings
     */
    public function html_output_block_admin( $block_id, $settings = array() ) {

        if ( empty( $settings ) ) {
            $block_id = "0";
        }

        $defaults = array(
            'align' => 'left',
            'html' => ''
        );

        $settings = array_merge( $defaults, $settings );

        ?>

        <div class='block'>
            <div class='block-title'><span title='<?php _e("HTML", "megamenu-pro"); ?>' class="dashicons dashicons-media-code"></span></div>
            <div class='block-settings'>
                <?php $this->print_toggle_block_panel_header( __( 'HTML Settings', 'megamenu-pro' ) ); ?>
                <input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='html' />
                <input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align'] ?>'>
                <div class='mm-toggle-html-editor-wrap'>
                    <p class='mega-name-title'><?php _e( 'Content', 'megamenu-pro' ); ?></p>
                    <textarea class='mm-toggle-html-editor' name='toggle_blocks[<?php echo $block_id; ?>][html]'><?php echo esc_textarea( wp_unslash( $settings['html'] ) ) ?></textarea>
                </div>
                <?php $this->print_toggle_block_panel_footer(); ?>
            </div>
        </div>

        <?php
    }


    /**
     * Create a new variable containing the toggle blocks to be used by the SCSS file
     *
     * @param array $vars
     * @param string $location
     * @param string $theme
     * @param int $menu_id
     * @param string $theme_id
     * @return array - all custom SCSS vars
     * @since 1.4
     */
    public function icon_add_block_vars_to_scss( $vars, $location, $theme, $menu_id, $theme_id ) {

        $toggle_blocks = $this->get_toggle_blocks_for_theme( $theme_id );

        $icon_blocks = array();

        if ( is_array( $toggle_blocks ) ) {

            foreach( $toggle_blocks as $index => $settings ) {

                if ( isset( $settings['type'] ) && $settings['type'] == 'icon' ) {

                    $icon_code = 'disabled';
                    $font = 'dashicons';
                    $weight = 'normal';

                    if ( isset( $settings['icon'] ) ) {

                        $icon_full = $settings['icon'];
                        $icon_classes = explode( ' ', $settings['icon'] );
                        
                        // dashicons or FA4
                        if ( count( $icon_classes ) == 1 ) {
                            $icon_code_parts = explode('-', $icon_classes[0]);
                            $icon_type = $icon_code_parts[0];
                            $icon_code = $icon_code_parts[1];
                        }

                        // fa5
                        if ( isset( $icon_classes[2] ) ) {
                            $icon_code_parts = explode('-', $icon_classes[2]);
                            $icon_type = $icon_classes[0];
                            $icon_code = $icon_code_parts[1];
                        }

                        // fa6
                        if ( isset( $icon_classes[3] ) ) {
                            $icon_type .= " fa6";
                        }

                        if ($icon_type == 'fa') {
                            $font = 'FontAwesome';
                        }

                        if ($icon_type == 'fab') {
                            $font = "'Font Awesome 5 Brands'";
                            $weight = '400';
                        }

                        if ($icon_type == 'far') {
                            $font = "'Font Awesome 5 Free'";
                            $weight = '400';
                        }

                        if ($icon_type == 'fas') {
                            $font = "'Font Awesome 5 Free'";
                            $weight = '900';
                        }

                        if ($icon_type == 'fab fa6') {
                            $font = "var(--fa-style-family-brands)";
                            $weight = '400';
                        }

                        if ($icon_type == 'far fa6') {
                            $font = "var(--fa-font-regular)";
                            $weight = '400';
                        }

                        if ($icon_type == 'fas fa6') {
                            $font = "var(--fa-style-family-classic)";
                            $weight = '900';
                        }

                    }

                    $styles = array(
                        'id' => $index,
                        'icon' => $icon_code != 'disabled' ? "'\\" . $icon_code  . "'" : "''",
                        'color' => isset($settings['color']) ? $settings['color'] : '#fff',
                        'font' => $font,
                        'size' => isset($settings['size']) ? $settings['size'] : '20px',
                        'weight' => $weight,
                        'icon_type' => $icon_type,
                        'icon_full' => $icon_full
                    );

                    $icon_blocks[ $index ] = $styles;
                }

            }
        }

        //$menu_toggle_blocks(
        // (123, red, 150px),
        // (456, green, null),
        // (789, blue, 90%),());
        if ( count( $icon_blocks ) ) {

            $list = "(";

            foreach ( $icon_blocks as $id => $vals ) {
                $list .= "(" . implode( ",", $vals ) . "),";
            }

            // Always add an empty list item to meke sure there are always at least 2 items in the list
            // Lists with a single item are not treated the same way by SASS
            $list .= "());";

            $vars['icon_blocks'] = $list;

        } else {

            $vars['icon_blocks'] = "()";

        }

        return $vars;
    }




    /**
     * Add "Icon" as a drop down option to the toggle block editor
     *
     * @since 1.4
     * @param array $options
     * @return array
     */
    public function icon_add_as_available_block_option( $options ) {
        $options['icon'] = __("Icon", "megamenu-pro");

        return $options;

    }


    /**
     * Output the icon (front end)
     *
     * @since 1.4
     * @param string $html
     * @param array $settings
     * @return string
     */
    public function icon_output_block_public( $html, $settings ) {

        $anchor_attributes = apply_filters( "megamenu_toggle_icon_attributes", array(
            'class' => 'mega-icon',
            'href' => isset($settings['url']) ? do_shortcode( stripslashes( html_entity_decode( $settings['url'], ENT_QUOTES ) ) ) : get_home_url(),
            'target' => isset($settings['target']) ? $settings['target'] : '_self',
            'title' => isset($settings['title']) ? $settings['title'] : ''
        ), $settings );

        $html = "<a";

        foreach ( $anchor_attributes as $name => $val ) {
            if ( strlen( $val ) ) {
                $html .= " " . $name ."='" . esc_attr( $val ) . "'";
            }
        }

        $html .= "></a>";

        return $html;

    }


    /**
     * Output the HTML for the "Icon" block settings
     *
     * @since 1.4
     * @param int $block_id
     * @param array $settings
     */
    public function icon_output_block_admin( $block_id, $settings = array() ) {

        if ( empty( $settings ) ) {
            $block_id = "0";
        }

        $defaults = array(
            'align' => 'left',
            'icon' => 'dash-f319',
            'color' => '#fff',
            'url' => '',
            'size' => '20px',
            'target' => '_self',
            'title' => ''
        );

        $settings = array_merge( $defaults, $settings );

        $icon_code = "";
        $icon_type = "dashicons";

        if ( isset( $settings['icon'] ) ) {

            $icon_full = $settings['icon'];
            $icon_classes = explode( ' ', $settings['icon'] );

            if ( isset( $icon_classes[2] ) ) {
                $icon_code_class = $icon_classes[2]; // fontawesome
            } else {
                $icon_code_class = $icon_classes[0]; // genericon
            }

            $icon_code_parts = explode('-', $icon_code_class);

            // e.g. f006
            $icon_code = end($icon_code_parts);
            $icon_code_attr = "&#x" . $icon_code . "";

            $icon_type = $icon_classes[0];

            if ( isset( $icon_classes[3] ) ) {
                $icon_type .= " " . $icon_classes[3]; // fa6
            }
        }

        ?>

        <div class='block icon_block'>
            <div class='block-title'><span title='<?php _e("Icon", "megamenu-pro"); ?>' class="<?php echo $icon_type ?>" rel='<?php echo $icon_code_attr; ?>'></span></div>
            <div class='block-settings'>
                <?php $this->print_toggle_block_panel_header( __( 'Icon Settings', 'megamenu-pro' ) ); ?>
                <input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='icon' />
                <input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align'] ?>'>
                <table class="mmm-settings-table">
                    <tbody>
                        <tr>
                            <td class="mega-name"><div class="mega-name-title"><?php _e( 'Icon', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Select and style the icon.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'Icon', 'megamenu-pro' ); ?></span><?php $this->print_icon_option( 'icon', $block_id, $settings['icon'], array() ); ?></label>
                                <label><span class='mega-short-desc'><?php _e( 'Color', 'megamenu-pro' ); ?></span><?php $this->print_toggle_color_option( 'color', $block_id, $settings['color'] ); ?></label>
                                <label><span class='mega-short-desc'><?php _e( 'Size', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][size]' value='<?php echo $settings['size']; ?>' /></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mega-name"><div class="mega-name-title"><?php _e( 'Link', 'megamenu-pro' ); ?></div><div class="mega-description"><?php _e( 'Where the icon links to.', 'megamenu-pro' ); ?></div></td>
                            <td class="mega-value">
                                <label><span class='mega-short-desc'><?php _e( 'URL', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][url]' value='<?php echo $settings['url']; ?>' /></label>
                                <label><span class='mega-short-desc'><?php _e( 'New Window', 'megamenu-pro' ); ?></span><select name='toggle_blocks[<?php echo $block_id; ?>][target]'>
                                    <option value='_self' <?php selected( $settings['target'], '_self' ) ?>><?php _e( 'No', 'megamenu-pro' ) ?></option>
                                    <option value='_blank' <?php selected( $settings['target'], '_blank' ) ?>><?php _e( 'Yes', 'megamenu-pro' ) ?></option>
                                </select></label>
                                <label><span class='mega-short-desc'><?php _e( 'Title', 'megamenu-pro' ); ?></span><input type='text' name='toggle_blocks[<?php echo $block_id; ?>][title]' value='<?php echo $settings['title']; ?>' /></label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php $this->print_toggle_block_panel_footer(); ?>
            </div>
        </div>

        <?php
    }

    /**
     * Print an icon selection box
     *
     * @since 2.1
     * @param string $key
     * @param int $block_id
     * @param string $value
     */
    public function print_icon_option( $key, $block_id, $value, $icons ) {

        $icons = array();

        ?>
            <select class='icon_dropdown' name='toggle_blocks[<?php echo $block_id ?>][<?php echo $key ?>]'>
                <?php

                    if ( class_exists( 'Mega_Menu_Menu_Item_Manager') && method_exists( 'Mega_Menu_Menu_Item_Manager', 'all_icons' ) ) {

                        echo "<optgroup label='Dashicons'>";

                        $menu_item_manager = new Mega_Menu_Menu_Item_Manager();
                        $icons = $menu_item_manager->all_icons();

                        foreach ($icons as $code => $class) {
                            echo "<option data-class='dashicons {$class}' value='{$code}'" . selected( $value, $code, false ) . ">{$code}</option>";
                        }

                        echo "</optgroup>";
                    }


                    if ( class_exists( 'Mega_Menu_Font_Awesome') && method_exists( 'Mega_Menu_Font_Awesome', 'icons' ) ) {

                        echo "<optgroup label='Font Awesome 4'>";

                        $fontawesome = new Mega_Menu_Font_Awesome();
                        $fa_icons = $fontawesome->icons();

                        foreach ($fa_icons as $code => $class) {
                            echo "<option data-class='{$class}' value='{$code}'" . selected( $value, $code, false ) . ">{$code}</option>";
                        }

                        echo "</optgroup>";
                    }

                    if ( class_exists( 'Mega_Menu_Font_Awesome_5') && method_exists( 'Mega_Menu_Font_Awesome_5', 'icons' ) ) {

                        echo "<optgroup label='Font Awesome 5'>";

                        $fontawesome5 = new Mega_Menu_Font_Awesome_5();
                        $fa5_icons = $fontawesome5->icons();

                        foreach ($fa5_icons as $code => $class) {
                            echo "<option data-class='{$class}' value='{$class} {$code}'" . selected( $value, $class . " " . $code, false ) . ">{$class} {$code}</option>";
                        }

                        echo "</optgroup>";
                    }

                    if ( class_exists( 'Mega_Menu_Font_Awesome_6') && method_exists( 'Mega_Menu_Font_Awesome_6', 'icons' ) ) {

                        echo "<optgroup label='Font Awesome 6'>";

                        $fontawesome6 = new Mega_Menu_Font_Awesome_6();
                        $fa6_icons = $fontawesome6->icons();

                        foreach ($fa6_icons as $code => $class) {
                            $value_attr = esc_attr($class . " " . $code . " fa6");
                            echo "<option data-class='{$class} fa6' value='{$value_attr}'" . selected( $value, $value_attr, false ) . ">{$class} {$code}</option>";
                        }

                        echo "</optgroup>";
                    }
                    
                ?>
            </select>

        <?php
    }

    /**
     * Return the saved toggle blocks for a specified theme
     *
     * @param string $theme_id
     * @since 1.4
     * @return array
     */
    private function get_toggle_blocks_for_theme( $theme_id ) {

        if ( function_exists( 'max_mega_menu_get_toggle_blocks' ) ) {
            $blocks = max_mega_menu_get_toggle_blocks();

            if ( isset( $blocks[ $theme_id ] ) ) {
                return $blocks[ $theme_id ];
            }
        }

        return false;

    }


    /**
     * Append the logo SCSS to the main SCSS file
     *
     * @since 1.4
     * @param string $scss
     * @param string
     */
    public function append_scss( $scss ) {

        $path = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'scss/toggle-blocks.scss';

        $contents = file_get_contents( $path );

        return $scss . $contents;

    }

}

endif;