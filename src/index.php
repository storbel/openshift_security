<?php
$hostname = gethostname();
$appName = getenv('APP_NAME'); 
$appPort = getenv('APP_PORT') ?: '8080';

$otherApps = [
    'appa' => 'appa.appa.svc.cluster.local:' . $appPort,
    'appb' => 'appb.appa.svc.cluster.local:' . $appPort,
    'appc' => 'appc.appc.svc.cluster.local:' . $appPort
];

unset($otherApps[$appName]); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['city'])) {
    $city = $_POST['city'];
    $apiKey = getenv('WEATHER_API_KEY');  // Fetch the API key from environment variable
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";
    $weatherData = file_get_contents($apiUrl);
    $weatherArray = json_decode($weatherData, true);
    if ($weatherArray['cod'] == 200) {
        $weather = "The current temperature in " . $city . " is " . $weatherArray['main']['temp'] . "°C.";
            $weatherMain = $weatherArray['weather'][0]['main'];
            $weatherDescription = $weatherArray['weather'][0]['description'];
            $iconUrl = "http://openweathermap.org/img/w/" . $weatherArray['weather'][0]['icon'] . ".png";
            $temperature = $weatherArray['main']['temp'];
            $feelsLike = $weatherArray['main']['feels_like'];
            $minTemp = $weatherArray['main']['temp_min'];
            $maxTemp = $weatherArray['main']['temp_max'];
            $humidity = $weatherArray['main']['humidity'];
            $pressure = $weatherArray['main']['pressure'];
            $windSpeed = $weatherArray['wind']['speed'];
            $cloudiness = $weatherArray['clouds']['all'];
    } else {
        $weather = "Couldn't retrieve the temperature for " . $city . ". Please make sure the city's name is correct.";
    }


if ($weatherArray['cod'] == 200) {

} else {
    // ... Handle errors ...
}



    
}



    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Page</title>
    <!-- Bootstrap CSS link -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    

</head>
<body>

<div class="container mt-5">
    <div class="jumbotron">
        <h1 class="display-4">Application Status <?php echo $appName; ?></h1>
        <p class="lead">Hostname: <?php echo $hostname; ?></p>
        <hr class="my-4">
        <ul class="list-group">
            <?php 
                foreach ($otherApps as $otherAppName => $appUrl) {
                    $connectionStatus = @file_get_contents($appUrl) ? 'Connected' : 'Not Connected';
                    $badgeClass = ($connectionStatus == 'Connected') ? 'badge-success' : 'badge-danger';
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>Connection to $otherAppName 
                            <span class='badge $badgeClass'>$connectionStatus</span>
                          </li>";
                }
            ?>
        </ul>
    </div>
</div>




    <div class="container mt-12">
    <!-- Weather form -->
    <!-- ... -->
<div class="container mt-3">
    <!-- Weather form -->
    <div class="mb-4">
        <form action="" method="post" class="form-inline">
            <input type="text" name="city" class="form-control mr-2" placeholder="Enter city name">
            <button type="submit" class="btn btn-primary">Get Temperature</button>
        </form>
        <?php if ($weather): ?>
            <p class="mt-3"><?php echo $weather; ?></p>
        <?php endif; ?>
    </div>
    
    <!-- The rest of the application status content -->

</div>
    <!-- Weather display -->
    <div class="card text-white bg-primary mb-3">
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['city'])) {
                
                if ($weatherArray['cod'] == 200) {
        echo '<div class="card-header">
            <img src="'.$iconUrl.'" alt="Weather icon">
            '.$weatherMain.' ('.$weatherDescription.')
        </div>
        <div class="card-body">
            <h5 class="card-title">Temperature: '.$temperature.'°C</h5>
            <p class="card-text">Feels like: '.$feelsLike.'°C</p>
            <p class="card-text">Min: '.$minTemp.'°C | Max:'.$maxTemp.'°C</p>
            <hr>
            <p class="card-text">Humidity:'.$humidity.'%</p>
            <p class="card-text">Pressure: '.$pressure.' hPa</p>
            <p class="card-text">Wind Speed: '.$windSpeed.' m/s</p>
            <p class="card-text">Cloudiness: '.$cloudiness.'%</p>
        </div>';
        } else {
        echo "<p class='badge badge-warning'>Couldn't retrieve the temperature for " . $city . ". Please make sure the city's name is correct.</p>";
    }
    }
        ?>
    </div>

        <div id="osmMap"  class="card text-white bg-primary mb-3"></div>

        
    <!-- ... Rest of the application status content ... -->
</div>


    
    
<!-- Optional JavaScript -->
<!-- jQuery, Popper.js, and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var lat = <?php echo $weatherArray['coord']['lat']; ?>;
        var lon = <?php echo $weatherArray['coord']['lon']; ?>;
        
        var map = L.map('osmMap').setView([lat, lon], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lon]).addTo(map)
            .bindPopup("<?php echo $weatherArray['name']; ?>").openPopup();
    });
</script>
    
</body>
</html>
