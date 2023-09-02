<?php
$hostname = gethostname();
$appName = getenv('APP_NAME'); 
$appPort = getenv('APP_PORT') ?: '8080';
$googleMapsApiKey = getenv('GOOGLE_MAPS_API_KEY');
$otherApps = [
    'appa' => 'appa:' . $appPort,
    'appb' => 'appb:' . $appPort,
    'appc' => 'appc:' . $appPort
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




    <div class="container mt-8">
    <!-- Weather form -->
    <!-- ... -->

    <!-- Weather display -->
    <div class="card text-white bg-primary mb-4">
        <div class="card-header">
            <img src="<?php echo $iconUrl; ?>" alt="Weather icon">
            <?php echo $weatherMain; ?> (<?php echo $weatherDescription; ?>)
        </div>
        <div class="card-body">
            <h5 class="card-title">Temperature: <?php echo $temperature; ?>°C</h5>
            <p class="card-text">Feels like: <?php echo $feelsLike; ?>°C</p>
            <p class="card-text">Min: <?php echo $minTemp; ?>°C | Max: <?php echo $maxTemp; ?>°C</p>
            <hr>
            <p class="card-text">Humidity: <?php echo $humidity; ?>%</p>
            <p class="card-text">Pressure: <?php echo $pressure; ?> hPa</p>
            <p class="card-text">Wind Speed: <?php echo $windSpeed; ?> m/s</p>
            <p class="card-text">Cloudiness: <?php echo $cloudiness; ?>%</p>
        </div>
    </div>

        <div id="map"  class="card text-white bg-primary mb-4"></div>

        
    <!-- ... Rest of the application status content ... -->
</div>


    
    
<!-- Optional JavaScript -->
<!-- jQuery, Popper.js, and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo  getenv('GOOGLE_MAPS_API_KEY');?>;&callback=initMap"></script>

    <script>
    function initMap() {
        var cityLocation = {lat: <?php echo $weatherArray['coord']['lat']; ?>, lng: <?php echo $weatherArray['coord']['lon']; ?>};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: cityLocation
        });
        var marker = new google.maps.Marker({
            position: cityLocation,
            map: map
        });
    }
</script>
    
</body>
</html>
