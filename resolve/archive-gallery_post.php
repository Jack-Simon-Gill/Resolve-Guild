<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Resolve
 */

get_header();
?>

    <div id="primary" class="content-area pt-5">
        <main id="main" class="site-main mt-5 mb-5">
            <div class="container">
                <h1 class="text-center mb-5">Gallery</h1>
                    <div class="row">
                        <?php if ( have_posts() ) : ?>
                            <?php
                            /* Start the Loop */
                            while ( have_posts() ) :
                                the_post();

                                /*
                                 * Include the Post-Type-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                 */
                                get_template_part( 'template-parts/content-gallery', get_post_type() );

                            endwhile;

                            the_posts_navigation();

                        else :

                            get_template_part( 'template-parts/content', 'none' );

                        endif;
                        ?>
                    </div>
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
