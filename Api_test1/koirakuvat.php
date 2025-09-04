<?php
    //apiUrl at API_path.php
    require("content/require/API_path.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>koirakuvat</title>
    <link rel="stylesheet" href="content/style/style.css">
</head>
<body>
<h1>Satunnaiset koirakuvat</h1>
<main>
    
    <form method="GET">
        <input type="number" name="amnt" id="amnt" placeholder="Kuvien määrä" value="10" required>
        <input type="submit" name="randomize" value="Satunnaista">
    </form>
    <br>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET"){

        if (isset($_GET["randomize"]) && isset($_GET["amnt"])){

            $amnt = intval($_GET["amnt"]);
            $response = file_get_contents($apiUrl . $amnt);
            $data = json_decode($response, true);

            if ($data && $data["status"] == "success"){

                echo "<div class='flex-container'>";

                //uses link data
                foreach ($data['message'] as $row => $r) {

                    echo "<div class='column'>";
                    echo "<img src=\"" . htmlspecialchars($r) ."\">";

                    //makes the path usable so newlines /n arent problem. may break if link structures change
                    $pathParse = parse_url($r, PHP_URL_PATH);
                    //splits into pieces
                    $sgmts = explode('/', trim($pathParse, '/'));
                    //takes right one with index
                    $type = str_replace('-', ' ', $sgmts[1]);
                    echo "<h3>". $type ."</h3>";

                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "virhe:" . $data['status'];
            }
        }
    }
    ?>
</main>
</body>
</html>