<?php
/**
 * Security features
 *
 * @package Kickmaker
 */

if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

// Remove WordPress version generator meta tag
remove_action("wp_head", "wp_generator");

// Remove Windows Live Writer manifest link
remove_action("wp_head", "wlwmanifest_link");

// Remove RSD (Really Simple Discovery) link
remove_action("wp_head", "rsd_link");

// Disable XML-RPC
add_filter("xmlrpc_enabled", "__return_false");

// Prevent author enumeration (redirect /?author=N to homepage)
function kickmaker_prevent_author_enumeration()
{
    if (is_admin()) {
        return;
    }

    if (isset($_GET["author"])) {
        wp_redirect(home_url(), 301);
        exit();
    }
}
add_action("template_redirect", "kickmaker_prevent_author_enumeration");

// Remove WP core updates for non-admins (reduces info disclosure)
function kickmaker_hide_update_notice()
{
    if (!current_user_can("update_core")) {
        remove_action("admin_notices", "update_nag", 3);
    }
}
add_action("admin_init", "kickmaker_hide_update_notice");
