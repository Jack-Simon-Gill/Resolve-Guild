<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Resolve
 */

?>
    <div class="col-3 mb-4">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <a href="<?php echo get_the_post_thumbnail_url();?>" data-lightbox="gallery"><img src="<?php echo get_the_post_thumbnail_url();?>" class="img-fluid"></a>
        </article><!-- #post-<?php the_ID(); ?> -->
    </div>
