<?php

/**
 * Kickmaker functions and definitions
 *
 * @package Kickmaker
 */

if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

if (!defined("KICKMAKER_VERSION")) {
    define("KICKMAKER_VERSION", "1.0.0");
}

if (!defined("KICKMAKER_DIR")) {
    define("KICKMAKER_DIR", get_template_directory());
}

if (!defined("KICKMAKER_URI")) {
    define("KICKMAKER_URI", get_template_directory_uri());
}

// Include required theme features
require_once KICKMAKER_DIR . "/inc/setup.php";
require_once KICKMAKER_DIR . "/inc/security.php";
require_once KICKMAKER_DIR . "/inc/seo.php";
require_once KICKMAKER_DIR . "/inc/performance.php";
require_once KICKMAKER_DIR . "/inc/enqueue.php";
require_once KICKMAKER_DIR . "/inc/acf.php";
require_once KICKMAKER_DIR . "/inc/blocks.php";
require_once KICKMAKER_DIR . "/inc/helpers.php";
require_once KICKMAKER_DIR . "/inc/shortcodes/index.php";
require_once KICKMAKER_DIR . "/inc/widgets/index.php";
require_once KICKMAKER_DIR . "/inc/hooks.php";
require_once KICKMAKER_DIR . "/inc/utilities.php";
require_once KICKMAKER_DIR . "/inc/modules/index.php";
