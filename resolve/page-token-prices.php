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

    $servername = "localhost";
    $username = "jack_wp189";
    $password = "1pS98w4)[R";
    $dbname = "jack_wp189";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM wpwh_token ORDER BY id DESC LIMIT 24";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $times = "";
        $prices = "";
        while($row = mysqli_fetch_assoc($result)) {
            $time = $row["date"];
            $time = strstr($time, ' - ');
            $time = str_replace(' - ', '', $time);
            $time = "'".$time."',";
            $times = $time . $times;
            $price = $row["price"].',';
            $prices = $price . $prices;
        }
    } else {
        echo "Error";
    }

?>
    <div id="primary" class="content-area pt-5">
        <main id="main" class="site-main mt-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1 class="mt-5"><?php the_title();?></h1>
                    </div>
                </div>

                <div class="row justify-content-center mt-5 mb-5">
                    <div class="col-10">
                        <ul class="nav nav-pills nav-fill id="pills-tab" role="tablist"">
                            <li class="nav-item">
                                <a class="nav-link active" id="24-hours-tab" data-toggle="pill" role="tab" aria-controls="24-hours" href="#24-hours">Last 24 Hours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="7-days-tab" data-toggle="pill" role="tab" aria-controls="7-days" href="#7-days">Last 7 days</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="30-days-tab" data-toggle="pill" role="tab" aria-controls="30-days" href="#30-days">Last 30 days</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-days-tab" data-toggle="pill" role="tab" aria-controls="custom-days"  href="#custom-days">Custom range</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-5" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="24-hours" role="tabpanel" aria-labelledby="24-hours-tab">
                                <canvas id="chartjs-0" class="chartjs" width="undefined" height="undefined"></canvas>
                                    <script>
                                        new Chart(document.getElementById("chartjs-0"),
                                            {
                                                "type":"line",
                                                "data":{
                                                    "labels":[
                                                        <?php echo $times; ?>
                                                    ],
                                                    "datasets":[
                                                        {
                                                            "label":"Token Prices Last 24 Hours",
                                                            "data":[<?php echo $prices; ?>],
                                                            "fill":true,
                                                            "borderColor":"rgb(75, 192, 192)",
                                                            "lineTension":0.1
                                                        }
                                                    ]
                                                },
                                                "options":{}
                                            });
                                    </script>
                            </div>
                            <div class="tab-pane fade" id="7-days" role="tabpanel" aria-labelledby="7-days-tab">
                                <?php
                                    $endLabel = date("Y-m-d", strtotime( '-1 days' ) );
                                    $startLabel = date("Y-m-d", strtotime( '-7 days' ) );
                                    $end = date("Y-m-d", strtotime( '-1 days' ) );
                                    $start = date("Y-m-d", strtotime( '-7 days' ) );

                                    $times = "";
                                    $prices = "";

                                    while (strtotime($start) <= strtotime($end)) {
                                        $sql = "SELECT AVG(price) FROM wpwh_token WHERE date_e = '$start'";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while($row = mysqli_fetch_assoc($result)) {
                                                $price = round($row["AVG(price)"]);
                                                $time = "'".$start."',";
                                                $times = $times . $time;
                                                $price = $price.',';
                                                $prices = $prices . $price;
                                            }
                                        } else {
                                            echo "Error";
                                        }
                                        $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
                                    }

                                ?>
                                <div class="chartjs-wrapper">
                                    <canvas id="chartjs-7-days" class="chartjs" width="undefined" height="undefined"></canvas>
                                    <script>
                                        new Chart(document.getElementById("chartjs-7-days"),
                                            {
                                                "type":"line",
                                                "data":{
                                                    "labels":[
                                                        <?php echo $times; ?>
                                                    ],
                                                    "datasets":[
                                                        {
                                                            "label":"Gold average - last 7 days. <?php echo $startLabel.' - '. $endLabel; ?>",
                                                            "data":[<?php echo $prices; ?>],
                                                            "fill":true,
                                                            "borderColor":"rgb(75, 192, 192)",
                                                            "lineTension":0.1
                                                        }
                                                    ]
                                                },
                                                "options":{}
                                            });
                                    </script>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="30-days" role="tabpanel" aria-labelledby="30-days-tab">
                                <div class="alert-info alert text-center text-uppercase">Coming soon</div>
                            </div>

                            <div class="tab-pane fade" id="custom-days" role="tabpanel" aria-labelledby="custom-days-tab">
                                <div class="alert-info alert text-center text-uppercase">Coming soon</div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();