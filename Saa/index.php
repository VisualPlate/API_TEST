<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sää</title>
    <link rel="stylesheet" href="content/style/style.css">
</head>
<body>
<h1>Sää Kuopio</h1>
<main>
    <?php
    $latitude = 62.8924;
    $longitude = 27.6770;

    $url = "https://api.met.no/weatherapi/locationforecast/2.0/compact" . '?lat=' . $latitude .'&lon=' .  $longitude;

    //added because met.no needs contact email
    $options = [
        "http" => [
            "header"=> "User-Agent: Saa/1.0 tolonenjere0@gmail.com\r\n",
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        die("Ei yhteyttä sää palvelimeen");
    }
    $data = json_decode($response, true);

    date_default_timezone_set("Europe/Helsinki");
    
    $timestamp = $data["properties"]["timeseries"];
    $maxVisible = 6;
    foreach ($timestamp as $row => $r) {
            //values and their units
            $time = substr(trim($r['time']), 11);
            $windSpeed = $r['data']['instant']['details']['wind_speed'] . $data['properties']['meta']['units']['wind_speed'];
            $windDirection = $r['data']['instant']['details']['wind_from_direction'] . $data['properties']['meta']['units']['wind_from_direction'];
            $relHumidity = $r['data']['instant']['details']['relative_humidity'] . $data['properties']['meta']['units']['relative_humidity'];
            $airTemp = $r['data']['instant']['details']['air_temperature'] . $data['properties']['meta']['units']['air_temperature'];
            $airPressure = $r['data']['instant']['details']['air_pressure_at_sea_level'] . $data['properties']['meta']['units']['air_pressure_at_sea_level'];
            $code_summary = $r['data']['next_1_hours']['summary']['symbol_code'];

            //checks the current time and compares to $time
            if (date('H') <= $time) {
                //echo and create
                $maxVisible--;
                echo "<div class='weather-container'>";
                echo "<p>".$time. " " . $windSpeed . " " . $windDirection . " " . $relHumidity . " " . $airTemp . " " . $airPressure . "</p>";
                echo getSuomi($code_summary);
                echo "</div>";
            }
            //when 6 echoed, wont echo anymore
            if ($maxVisible < 1) {
                break;
            }
        
    }
    //translate text to finnish. 
    function getSuomi($code) {
        $table = [
            'clearsky_day'       => 'Selkeää',
            'clearsky_night'     => 'Selkeää yöllä',
            'partlycloudy_day'   => 'Puolipilvistä',
            'partlycloudy_night' => 'Puolipilvistä yöllä',
            'cloudy'             => 'Pilvistä',
            'rain'               => 'Sade',
            'lightrain'          => 'Heikkoa sadetta',
            'heavyrain'          => 'Voimakasta sadetta',
            'snow'               => 'Lunta',
            'lightsnow'          => 'Heikkoa lunta',
            'heavysnow'          => 'Voimakasta lunta',
            'fog'                => 'Sumua',
            'fair_day'           => 'Selkeää päivällä'
        ];
        return $table[$code] ?? $code;
    }

    ?>
</main>
</body>
</html>