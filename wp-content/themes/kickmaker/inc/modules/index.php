<?php
if (!defined("ABSPATH")) {
    exit();
}

/**
 * Modules loader
 *
 * Loads all reusable UI component modules.
 * Order matters: modules with no dependencies first.
 *
 * @package Kickmaker
 */

require_once __DIR__ . "/buttons.php";
require_once __DIR__ . "/breadcrumbs.php";
require_once __DIR__ . "/image.php";
require_once __DIR__ . "/video.php";
require_once __DIR__ . "/media.php";
require_once __DIR__ . "/post-card.php";
