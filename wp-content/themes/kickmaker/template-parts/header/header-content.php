<div class="header-site-container">
    <div class="container-logo">
        <?php
        $theme_settings_header = get_field("theme_settings_header", "options");
        $logo_header = !empty($theme_settings_header["logo"])
            ? $theme_settings_header["logo"]["url"]
            : "";

        if (function_exists("the_custom_logo") && get_custom_logo()) {
            the_custom_logo();
        } elseif ($logo_header) { ?>
            <a href="<?php echo esc_url(home_url("/")); ?>" class="custom-logo-link">
                <img class="logo-default" src="<?php echo esc_url(
                    $logo_header,
                ); ?>" alt="<?php bloginfo("name"); ?>">
            </a>
        <?php }
        ?>
    </div>

    <div class="content-menu-list">
        <?php
        wp_nav_menu([
            "theme_location" => "primary",
            "fallback_cb" => "",
        ]); ?>
    </div>

    <div class="header-buttons-container fordesktop">
        <?php echo do_shortcode("[shortcode_buttons_header]"); ?>

        <!-- WPML languages list -->
        <?php 
        $languages = apply_filters("wpml_active_languages", null, ["skip_missing" => 0]);

        if (!empty($languages)): ?>
            <details class="language-switcher">
                <summary>
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" aria-hidden="true" focusable="false">
                        <path d="M12.5002 2.0835C15.1057 4.93594 16.5864 8.63769 16.6668 12.5002C16.5864 16.3627 15.1057 20.0643 12.5002 22.9168M12.5002 2.0835C9.89466 4.93594 8.41397 8.63769 8.3335 12.5002C8.41397 16.3627 9.89466 20.0643 12.5002 22.9168M12.5002 2.0835C6.74719 2.0835 2.0835 6.74719 2.0835 12.5002C2.0835 18.2531 6.74719 22.9168 12.5002 22.9168M12.5002 2.0835C18.2531 2.0835 22.9168 6.74719 22.9168 12.5002C22.9168 18.2531 18.2531 22.9168 12.5002 22.9168M2.60435 9.37516H22.396M2.60433 15.6252H22.396" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="visually-hidden"><?php esc_html_e("Language", "kickmaker"); ?></span>
                </summary>

                <ul class="language-switcher-list">
                    <?php foreach ($languages as $language): ?>
                        <li class="language-switcher-item<?php echo !empty($language["active"])
                            ? " is-active"
                            : ""; ?>">
                            <a href="<?php echo esc_url($language["url"]); ?>"><?php echo esc_html(
                                $language["native_name"],
                            ); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>
        <?php endif; ?>
    </div>
</div>
