<?php
    //apiUrl at API_path.php
    require("content/require/API_path.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sää</title>
    <link rel="stylesheet" href="content/style/style.css">
</head>
<body>
<h1>Sää</h1>
<main>
    <?php
    $latitude = 62.8924;
    $longitude = 27.6770;

    $response = file_get_contents("$apiUrl . 'lat=' . $latitude .'&lon='  $longitude");
    if ($response === FALSE) {
        die("Ei yhteyttä sää palvelimeen");
    }
    $data = json_decode($response, true);

    $timestamp = array_slice($data["properties"]["timeseries"],0,6);
    function getSuomi($code) {
        $table = [
            "clearsky_day"=> "Selkeää",
            "clearsky_night"=> "Selkeää yöllä",
            "partlycloudy_day"=> "puolipilvistä"
        ];
        return $table[$code] ?? $code;
    }

    ?>
</main>
</body>
</html>