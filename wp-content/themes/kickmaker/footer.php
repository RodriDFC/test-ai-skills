<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
/**
 * The template for displaying the footer
 *
 * @package Kickmaker
 */
?>
    <footer id="colophon" class="site-footer">
        <?php get_template_part("template-parts/footer/footer", "content"); ?>
    </footer>
  
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
