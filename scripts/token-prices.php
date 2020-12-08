<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    </head>
    
    <?php 

	require('db-connect.php');
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

    <body>
        
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
        
    </body>
    
    <?php mysqli_close($conn); ?>
    
</html>