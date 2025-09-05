<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sää</title>
    <link rel="stylesheet" href="content/style/style.css">
</head>
<body>
<h1 class="text-align-center text-primary head-header">Sää Kuopio</h1>
<main>
    <form method="get" class="text-align-center search-form">
        <input type="number" name="maxVis" placeholder="Haettavat tunnit (6-24)" min="6" max="24">
        <input type="submit" value="Hae säätiedot">
    </form>
    <?php
    //icons
    $imgDay = "sun128.png";
    $imgNight = "moon128.png";




    $latitude = 62.8924;
    $longitude = 27.6770;

    $url = "https://api.met.no/weatherapi/locationforecast/2.0/compact" . '?lat=' . $latitude .'&lon=' .  $longitude;

    //added because met.no needs contact email
    $options = [
        "http" => [
            "header"=> "User-Agent: Saa/1.0 example@example.a\r\n",
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        die("Ei yhteyttä sää palvelimeen");
    }
    $data = json_decode($response, true);

    date_default_timezone_set("Europe/London");
    
    $timestamp = $data["properties"]["timeseries"];
    $maxVisible = 6;
    if (isset($_GET["maxVis"]) && $_GET["maxVis"] < 25 && $_GET["maxVis"] > 5) { $maxVisible = $_GET["maxVis"];}
    echo "<div class='weather-container'>";

    foreach ($timestamp as $row => $r) {

            //values and their units
            $time = substr(trim($r['time']), 11);

            //assigns time 
            $dateTime = new DateTime(trim($r['time']));
            //adds 3 hours so the time will be accurate, otherwise showing London time. 
            //If timezone is Helsinki, everyhting breaks since api is in utc +0 time
            $dateTime->modify('+3 hours');
            //Formats time so it can be shown
            $timeHelsinki = $dateTime->format('H:i:s');

            $windSpeed = $r['data']['instant']['details']['wind_speed'] .  " m/s";
            $windDirection = $r['data']['instant']['details']['wind_from_direction'] . " °";
            $relHumidity = $r['data']['instant']['details']['relative_humidity'] .  " %";
            $airTemp = $r['data']['instant']['details']['air_temperature'] .  " °C";
            $airPressure = $r['data']['instant']['details']['air_pressure_at_sea_level'] . " hPa";
            $code_summary = $r['data']['next_1_hours']['summary']['symbol_code'];

            //checks the current time and compares to $time
            if (date('H') <= $time) {

                //check what color to add to tempature 
                $pureAirTemp = $r['data']['instant']['details']['air_temperature'];
                $color = "greenish";
                if ($pureAirTemp < 0){
                    $color = "blueish";
                } elseif ($pureAirTemp > 25) {
                    $color = "reddish";
                }
                //echo and create
                $maxVisible--;

                echo "<div class='weather-card'>";

                //containers icons at top
                echo "<div class='card-icons'>";
                if ($timeHelsinki > "21-0-0" && $timeHelsinki < "6-0-0") {
                    echo "<img src='content/media/$imgNight' class='card-icon icon-night'>";
                } else {
                    echo "<img src='content/media/$imgDay' class='card-icon icon-day'>";
                }
                echo "</div>";

                //header
                echo "<h2 class='text-align-center text-primary'>$timeHelsinki</h2>";
                echo "<h1 class='text-align-center air-temp $color'>$airTemp</h1>";

                //weather code
                echo "<h3 class='text-align-center text-primary'>".getSuomi($code_summary)."</h3>";

                //Text
                echo "<div class='grid-4 text-secondary'>";
                echo "<h4>Tuulen nopeus</h4>";
                echo "<h4>Tuulen suunta</h4>";
                echo "<h4>Ilman kosteus</h4>";
                echo "<h4>Ilmanpaine</h4>";
                echo "</div>";

                //values for text
                echo "<div class='grid-4 text-primary'>";
                echo "<h3>$windSpeed</h3>";
                echo "<h3>$windDirection</h3>";
                echo "<h3>$relHumidity</h3>";
                echo "<h3>$airPressure</h3>";
                echo "</div>";
                echo "</div>";
            }
            //when 6 echoed, wont echo anymore and breaks loop
            if ($maxVisible < 1) {
                break;
            }
    }
    echo "</div>";
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
            'fair_day'           => 'Selkeää päivällä',
            'fair_night'         => 'Selkeää yöllä'
        ];
        return $table[$code] ?? $code;
    }
    //checks what icon to put on top icon bar
    function checkIcon($icon) {
        $icons = [
            'clearsky_day'       => 'Selkeää',
            'clearsky_night'     => 'Selkeää yöllä',
            'partlycloudy_day'   => 'Puolipilvistä',
            'partlycloudy_night' => 'Puolipilvistä yöllä',
            'cloudy'             => 'content/media/cloudy128.png',
            'rain'               => 'content/media/rain_medium128.png',
            'lightrain'          => 'content/media/rainLight128.png',
            'heavyrain'          => 'content/media/rain128.png',
            'snow'               => 'Lunta',
            'lightsnow'          => 'Heikkoa lunta',
            'heavysnow'          => 'content/media/snow128.png',
            'fog'                => 'content/media/fog128.png',
            'fair_day'           => 'Selkeää päivällä',
            'fair_night'         => 'Selkeää yöllä'
        ];
        return $icons[$icon] ?? $icon;
    }
    ?>
</main>
</body>
</html>