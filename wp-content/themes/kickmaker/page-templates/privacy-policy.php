<?php

/**
 * Template Name: Privacy Policy
 *
 * @package Understrap
 */

defined("ABSPATH") || exit();

get_header();
?>

<main class="privacy">
    <?php while (have_posts()):
        the_post();
        $updated_date = get_the_modified_date("j F Y"); // E.g., 22 June 2026
        $content = apply_filters("the_content", get_the_content());
    ?>
    
    <section class="privacy__hero">
        <div class="container">
            <div class="privacy__hero-content">
                <div class="breadcrumb-site privacy__label">
                    <div class="items-breadcrumb">
                        <span property="itemListElement" typeof="ListItem">
                            <span class="current-item">Privacy Policy</span>
                        </span>
                    </div>
                </div>
                <h1 class="privacy__title"><?php the_title(); ?></h1>
                <p class="privacy__updated">Updated <?php echo esc_html($updated_date); ?></p>
            </div>
        </div>
    </section>

    <section class="privacy__body">
        <div class="container">
            <div class="privacy__layout">
                <aside class="privacy__sidebar">
                    <nav id="privacy-nav" class="privacy__nav">
                        <!-- JS will populate this with links -->
                    </nav>
                </aside>
                
                <div class="privacy__content">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </section>

    <?php 
    $cta_content = get_field('cta_privacy', 'option');
    if ($cta_content) : 
    ?>
        <div class="privacy__cta">
            <?php 
            get_template_part("blocks/general-content/general-content", null, [
                "content" => $cta_content,
                "base_class" => "general-section block-content global-block cta-privacy",
            ]); 
            ?>
        </div>
    <?php endif; ?>

    <?php endwhile; ?>
</main>

<?php get_footer();
