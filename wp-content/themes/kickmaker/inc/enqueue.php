<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles
 *
 * @package Kickmaker
 */

function kickmaker_scripts()
{
    wp_enqueue_style(
        "kickmaker-style",
        KICKMAKER_URI . "/dist/css/main.css",
        [],
        KICKMAKER_VERSION,
    );

    wp_enqueue_script(
        "kickmaker-main",
        KICKMAKER_URI . "/dist/js/main.js",
        [],
        KICKMAKER_VERSION,
        true,
    );
}

add_action("wp_enqueue_scripts", "kickmaker_scripts");
