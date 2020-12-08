<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Resolve
 */

?>

	</div><!-- #content -->
	<footer id="colophon" class="site-footer pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'container'       => 'ul'
                    ) );
                    ?>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'menu-2',
                        'menu_id'        => 'footer-menu',
                        'container'       => 'ul'
                    ) );
                    ?>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <h5 class="text-center">WoW token price</h5>
                    <div class="row">
                        <?php
                        global $wpdb;
                        $results = $wpdb->get_results( "SELECT * FROM wpwh_token ORDER BY ID DESC LIMIT 1");
                        foreach ($results as $result){
                            echo '<div class="col-12">';
                                echo '<img src="'.get_template_directory_uri().'/inc/wowtokeninterlaced.png" class="img-fluid mx-auto d-block">';
                            echo '</div>';
                            echo '<div class="col-12 text-center">';
                                echo '<p class="gold-price pt-3">'.$result->price.'g</p><p>'.$result->date.' GMT</p>';
                            echo '</div>';
                            echo '<div class="col-12 text-center">';
                                echo '<p class="pt-2"><a href="/token-prices/">Last 24 hours</a></p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-12">
                    <hr>
                </div>
            </div>
        </div>
	</footer><!-- #colophon -->
    <section class="footer-sig mt-3 mb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 text-center text-lg-left">
                    Copyright &copy; <?php echo date('Y');?> Resolve Guild
                </div>
                <div class="col-12 col-lg-6 text-right">
                    <a href="https://www.jaminspired.co.uk">Powered by Jam <img src="<?php echo get_template_directory_uri().'/inc/jam.png';?>"></a>
                </div>
            </div>
        </div>
    </section>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>