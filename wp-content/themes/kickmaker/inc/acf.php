<?php
// if ( ! defined( 'ABSPATH' ) ) {
//     exit; // Exit if accessed directly.
// }
// /**
//  * ACF Configurations
//  *
//  * @package Kickmaker
//  */

// // Set ACF Local JSON save point
// function kickmaker_acf_json_save_point( $path ) {
//     return KICKMAKER_DIR . '/acf-json';
// }
// add_filter( 'acf/settings/save_json', 'kickmaker_acf_json_save_point' );

// // Set ACF Local JSON load point
// function kickmaker_acf_json_load_point( $paths ) {
//     // Remove original path
//     unset( $paths[0] );

//     // Append new path
//     $paths[] = KICKMAKER_DIR . '/acf-json';

//     return $paths;
// }
// add_filter( 'acf/settings/load_json', 'kickmaker_acf_json_load_point' );

/**
 * Make ACF field 'video_duration' readonly in WP Admin.
 */
add_filter("acf/load_field/name=video_duration", function ($field) {
    $field["readonly"] = 1;
    return $field;
});
