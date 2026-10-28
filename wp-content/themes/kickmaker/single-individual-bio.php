<?php

/**
 * @package Kickmaker
 */

defined("ABSPATH") || exit();

// CSS for blocks
wp_enqueue_style(
    'css-for-block-cta-subscribe',
    get_template_directory_uri() . '/blocks/cta-subscribe/cta-subscribe.css',
    array(),
    filemtime(get_template_directory() . '/blocks/cta-subscribe/cta-subscribe.css')
);
wp_enqueue_style(
    'css-for-block-featured-cards-slider',
    get_template_directory_uri() . '/blocks/featured-cards-slider/featured-cards-slider.css',
    array(),
    filemtime(get_template_directory() . '/blocks/featured-cards-slider/featured-cards-slider.css')
);

// Add JS for blocks
wp_enqueue_script(
    'js-for-block-featured-cards-slider',
    get_template_directory_uri() . '/blocks/featured-cards-slider/featured-cards-slider.js',
    array(),
    filemtime(get_template_directory() . '/blocks/featured-cards-slider/featured-cards-slider.js'),
    true
);


get_header();

// ACF group: cpt_individual_bio
$bio = get_field('cpt_individual_bio');
$role_title   = !empty($bio['role_title']) ? $bio['role_title'] : '';
$linkedin_url = !empty($bio['linkedin']) ? $bio['linkedin'] : '';
$description  = !empty($bio['description']) ? $bio['description'] : '';

?>

<main class="main-individual-bio">
    <?php while (have_posts()): ?>
        <?php the_post(); ?>

        <section class="section-hero-banner">
            <div class="container custom-container">
                <div class="wrapper-hero-banner">
                    <?php breadcrumb_for_single(); ?>
                    <h1 class="title"><?php the_title(); ?></h1>

                    <div class="hero-banner__meta">
                        <?php if ($role_title): ?>
                            <!-- ACF: role_title (text) -->
                            <span class="role">
                                <?php echo esc_html($role_title); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($linkedin_url): ?>
                            <span class="vertical-line"></span>

                            <a class="linkedin"
                                href="<?php echo esc_url($linkedin_url); ?>"
                                target="_blank"
                                rel="noopener"
                                aria-label="<?php esc_attr_e('LinkedIn', 'kickmaker'); ?>">
                                <svg width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.20312 18.3633H3.70508V8.74707H7.20312V18.3633ZM15.5088 8.08984C17.6816 8.09002 19.4434 9.85203 19.4434 12.0244V18.3623H15.9453V12.2432C15.9453 11.2782 15.1624 10.4944 14.1973 10.4941C13.2319 10.4941 12.4482 11.278 12.4482 12.2432V18.3623H8.9502V8.74609H12.4482V9.58008C13.188 8.64202 14.3152 8.09334 15.5088 8.08984ZM5.4541 3.49902C6.41977 3.4991 7.20288 4.28166 7.20312 5.24707C7.20312 6.21269 6.41992 6.99602 5.4541 6.99609C4.48822 6.99609 3.70508 6.21274 3.70508 5.24707C3.70532 4.28161 4.48837 3.49902 5.4541 3.49902Z" fill="white" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </section>
        <section class="section-data">
            <div class="container custom-container">
                <div class="wrapper-data">
                    <div class="hero-image-description">
                        <div class="hero-image-description__image">
                            <!-- Featured image (left) -->
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('large', ['class' => 'image']); ?>
                            <?php else: ?>
                                <img class="image"
                                    src="<?php echo esc_url(get_template_directory_uri() . '/dist/images/defauld-feature-image.webp'); ?>"
                                    alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </div>

                        <div class="hero-image-description__text">
                            <svg width="42" height="36" viewBox="0 0 42 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24.3377 36V23.5385C24.3377 17.3538 25.7285 12.1385 28.5099 7.8923C31.3841 3.55384 35.8808 0.923076 42 0V7.2C38.3841 7.56923 35.8808 8.86154 34.4901 11.0769C33.192 13.2 32.543 16.3846 32.543 20.6308L26.9801 19.8H41.8609V36H24.3377ZM0 36V23.5385C0 17.3538 1.39073 12.1385 4.17218 7.8923C7.04636 3.55384 11.543 0.923076 17.6623 0V7.2C14.0464 7.56923 11.543 8.86154 10.1523 11.0769C8.8543 13.2 8.2053 16.3846 8.2053 20.6308L2.64238 19.8H17.5232V36H0Z" fill="#33ABC6" />
                            </svg>
                            <?php echo wp_kses_post($description); ?>
                        </div>
                    </div>

                    <div class="content-data">
                        <?php the_content(); ?>
                    </div>

                </div>
            </div>
            <figure class="bg-decoration">
                <img src="<?php echo get_template_directory_uri() . '/src/images/KM_Amplifier_RGB_Transparent_22.png' ?>" alt="background decoration">
            </figure>
        </section>
    <?php endwhile; ?>

    <?php
    get_template_part('blocks/featured-cards-slider/featured-cards-slider');
    get_template_part('blocks/cta-subscribe/cta-subscribe');
    ?>
</main>


<?php get_footer();
