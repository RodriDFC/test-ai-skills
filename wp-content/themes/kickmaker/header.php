<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * The header for our theme
 *
 * @package Kickmaker
 */

$post_id = get_queried_object_id();
$header_fixed = !empty(get_field("header_fixed", $post_id))
    ? get_field("header_fixed", $post_id)
    : false;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo("charset"); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="stylesheet" href="https://use.typekit.net/dnh0vua.css">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e(
        "Skip to content",
        "kickmaker",
    ); ?></a>

    <header id="masthead" class="header-site <?php echo $header_fixed ? "header-fixed" : ""; ?>">
        <nav id="main-nav" class="navbar navbar-expand-lg p-0" aria-labelledby="main-nav-label">
            <div class="container">
                <?php get_template_part("template-parts/header/header", "content"); ?>
            </div>
        </nav>
    </header>
