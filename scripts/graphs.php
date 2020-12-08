<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    </head>
    
    <?php 
	// Create front end graphs based on token prices over time
	require('db-connect.php');
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $today = date('Y-m-d');
    
?>

    <body>
        <?php 
            $end = date("Y-m-d", strtotime( '-1 days' ) ); 
            $start = date("Y-m-d", strtotime( '-8 days' ) ); 
            
            $times = "";
            $prices = "";
            
            while (strtotime($start) <= strtotime($end)) {
                $sql = "SELECT AVG(price) FROM wpwh_token WHERE date_e = '$start'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $price = round($row["AVG(price)"]);
                        $time = "'".$start."',";
                        $times = $time . $times;
                        $price = $price.',';
                        $prices = $price. $prices;
                    }
                } else {
                    echo "Error";
                }
                $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
	}
        
        ?>
        
        <div class="chartjs-wrapper">
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
                                    "label":"Gold average - last 7 days",
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
        
    </body>
    
    <?php mysqli_close($conn); ?>
    
</html>