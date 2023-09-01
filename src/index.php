<?php
$hostname = gethostname();
$appName = getenv('APP_NAME'); 
$appPort = getenv('APP_PORT') ?: '8080';

$otherApps = [
    'appa' => 'appa:' . $appPort,
    'appb' => 'appb:' . $appPort,
    'appc' => 'appc:' . $appPort
];

unset($otherApps[$appName]); 
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
        <h1 class="display-4">Application Status</h1>
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

<!-- Optional JavaScript -->
<!-- jQuery, Popper.js, and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
