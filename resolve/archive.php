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
        <main id="main" class="site-main mt-5">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <?php if ( have_posts() ) : ?>

                            <header class="page-header">
                                <?php
                                the_archive_title( '<h1 class="page-title">', '</h1>' );
                                the_archive_description( '<div class="archive-description">', '</div>' );
                                ?>
                            </header><!-- .page-header -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9 col-12">
                            <?php
                            /* Start the Loop */
                            while ( have_posts() ) :
                                the_post();

                                /*
                                 * Include the Post-Type-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                 */
                                get_template_part( 'template-parts/content-archive', get_post_type() );

                            endwhile;

                            the_posts_navigation();

                        else :

                            get_template_part( 'template-parts/content', 'none' );

                        endif;
                        ?>
                    </div>
                    <div class="col-lg-3 col-12">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
