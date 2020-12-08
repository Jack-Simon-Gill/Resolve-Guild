<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Resolve
 */

get_header();
?>
    <script>var whTooltips = {colorLinks: false, iconizeLinks: false, renameLinks: false};</script>

    <div id="primary" class="content-area pt-5">
    <main id="main" class="site-main mt-5">
        <div class="container">
            <?php
            while ( have_posts() ) :
                the_post(); ?>
                <h1 class="text-center mb-5"><?php the_title();?></h1>
            <div class="row">
                <div class="col-12">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php
            endwhile; // End of the loop.
            ?>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Name</th>
                            <th scope="col">Spec</th>
                            <th scope="col" class="text-center">Class</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        global $wpdb;
                        $results = $wpdb->get_results( "SELECT * FROM wpwh_roster ORDER by rank ASC, name ASC");
                        foreach ($results as $result){
                            $thumbnail = str_replace('avatar','inset',$result->thumbnail);
                            $class = $result->class;
                            $id = $result->ID;
                            $name = $result->name;
                            ?>
                            <tr>
                                <td class="align-middle"><a href="#" data-toggle="modal" data-target="#<?php echo $name.$id;?>"><img src="<?php echo get_template_directory_uri().'/roster/insets/'.$name.'.jpg'; ?>" class="img-fluid"></a></td>
                                <td class="align-middle"><a href="#" data-toggle="modal" data-target="#<?php echo $name.$id;?>"><?php echo $name; ?></a></td>
                                <td class="align-middle"><?php echo $result->spec; ?></td>
                                <td class="align-middle"><?php get_class_icon($class);?></td>
                            </tr>
                            <?php

                        }

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
		</main><!-- #main -->
	</div><!-- #primary -->


<?php
$results = $wpdb->get_results( "SELECT * FROM wpwh_roster ORDER by rank ASC, name ASC");
    foreach ($results as $result) {
        $id = $result->ID;
        $name = $result->name;
        $class = $result->classID;
        $items = $wpdb->get_results("SELECT * FROM wpwh_armoury WHERE ID = $id");
        foreach ($items as $item) {
            $ilvl = explode(',', $item->averageItemLevel);
            $head = explode(',', $item->head);
            $hAzerite = $item->headAzerite;
            $neck = explode(',', $item->neck);
            $shoulder = explode(',', $item->shoulder);
            $sAzerite = $item->shoulderAzerite;
            $back = explode(',', $item->back);
            $chest = explode(',', $item->chest);
            $cAzerite = $item->chestAzerite;
            $tabard = explode(',', $item->tabard);
            $wrist = explode(',', $item->wrist);
            $hands = explode(',', $item->hands);
            $waist = explode(',', $item->waist);
            $legs = explode(',', $item->legs);
            $feet = explode(',', $item->feet);
            $finger1 = explode(',', $item->finger1);
            $finger2 = explode(',', $item->finger2);
            $trinket1 = explode(',', $item->trinket1);
            $trinket2 = explode(',', $item->trinket2);
            $mainHand = explode(',', $item->mainHand);
            $offHand = explode(',', $item->offHand);
            ?>
            <div class="modal fade" id="<?php echo $name.$id;?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $name; ?> Character Items - <?php echo $ilvl[0].' ('.$ilvl[1].' equipped)';?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row pt-3" style="background-image:url('<?php echo get_template_directory_uri().'/roster/insets/'.$name.'_main.jpg'; ?>');background-size:cover;background-repeat: no-repeat;">
                                    <div class="col-6">
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <?php $headurlname = str_replace(' ','-',$head[2]); ?>
                                                <a href="https://www.wowhead.com/item=<?php echo $head[0];?>/<?php echo $headurlname;?>&ilvl=<?php echo $head[3];?>&azerite-powers=<?php echo $class;?>:<?php echo $hAzerite;?>"><img src="<?php echo $head[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $head[2];?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $neck[0];?>&ilvl=<?php echo $neck[3];?>"><img src="<?php echo $neck[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $neck[2];?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <?php $shoulderurlname = str_replace(' ','-',$shoulder[2]); ?>
                                                <a href="https://www.wowhead.com/item=<?php echo $shoulder[0];?>/<?php echo $shoulderurlname;?>&ilvl=<?php echo $shoulder[3];?>&azerite-powers=<?php echo $class;?>:<?php echo $sAzerite;?>"><img src="<?php echo $shoulder[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $shoulder[2];?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $back[0];?>"><img src="<?php echo $back[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $back[2];?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <?php $chesturlname = str_replace(' ','-',$chest[2]); ?>
                                                <a href="https://www.wowhead.com/item=<?php echo $chest[0];?>/<?php echo $chesturlname;?>&ilvl=<?php echo $chest[3];?>&azerite-powers=<?php echo $class;?>:<?php echo $cAzerite;?>"><img src="<?php echo $chest[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $chest[2];?></p>
                                            </div>
                                        </div>
                                        <?php if ($tabard[0] != '0'){ ?>
                                            <div class="row mb-2 align-items-center">
                                                <div class="col-3">
                                                    <a href="https://www.wowhead.com/item=<?php echo $tabard[0];?>"><img src="<?php echo $tabard[1];?>"></a>
                                                </div>
                                                <div class="col-9">
                                                    <p class="item__name"><?php echo $tabard[2];?></p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $wrist[0];?>&ilvl=<?php echo $wrist[3];?>"><img src="<?php echo $wrist[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $wrist[2];?></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $mainHand[0];?>&ilvl=<?php echo $mainHand[3];?>"><img src="<?php echo $mainHand[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $mainHand[2];?></p>
                                            </div>
                                        </div>
                                    <?php if ($offHand[0] != '0'){ ?>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $offHand[0];?>"><img src="<?php echo $offHand[1];?>"></a>
                                            </div>
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $offHand[2];?></p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                    <div class="col-6 text-right">
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $hands[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $hands[0];?>&ilvl=<?php echo $hands[3];?>"><img src="<?php echo $hands[1];?>"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $waist[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $waist[0];?>&ilvl=<?php echo $waist[3];?>"><img src="<?php echo $waist[1];?>"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $legs[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $legs[0];?>&ilvl=<?php echo $legs[3];?>"><img src="<?php echo $legs[1];?>"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $feet[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $feet[0];?>&ilvl=<?php echo $feet[3];?>"><img src="<?php echo $feet[1];?>"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $finger1[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $finger1[0];?>&ilvl=<?php echo $finger1[3];?>"><img src="<?php echo $finger1[1];?>"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $finger2[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $finger2[0];?>&ilvl=<?php echo $finger2[3];?>"><img src="<?php echo $finger2[1];?>"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $trinket1[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $trinket1[0];?>&ilvl=<?php echo $trinket1[3];?>"><img src="<?php echo $trinket1[1];?>"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-9">
                                                <p class="item__name"><?php echo $trinket2[2];?></p>
                                            </div>
                                            <div class="col-3">
                                                <a href="https://www.wowhead.com/item=<?php echo $trinket2[0];?>&ilvl=<?php echo $trinket2[3];?>"><img src="<?php echo $trinket2[1];?>"></a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-wow" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <?php
        }
    }
?>
<?php
get_footer();