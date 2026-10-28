<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * Widgets Entry Point
 *
 * Cleanly load all custom widgets and sidebars from here.
 *
 * @package Kickmaker
 */

// Example: require_once __DIR__ . '/my-widget.php';

function kickmaker_widgets_init()
{
    register_sidebar([
        "name" => esc_html__("Sidebar", "kickmaker"),
        "id" => "sidebar-1",
        "description" => esc_html__("Add widgets here.", "kickmaker"),
        "before_widget" => '<section id="%1$s" class="widget %2$s">',
        "after_widget" => "</section>",
        "before_title" => '<h2 class="widget-title">',
        "after_title" => "</h2>",
    ]);
}
add_action("widgets_init", "kickmaker_widgets_init");
