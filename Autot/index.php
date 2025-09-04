<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hae</title>
</head>
<body>
    <form method="get">
        <label for="hae">Hae kaikki</label>
        <button type="submit" name="getKaikki">hae</button>
    </form>
    <form method="get">
        <label for="hae">Hae ID:llä</label>
        <input type="number" name="getId" id="getId" min="1" required>
        <button type="submit" name="getID">Hae</button>
    </form>
    <?php
    require("path.php");

    $autot = [];

    if (isset($_GET["status"])) {
        switch ($_GET["status"]) {
            case "updated":
                echo"<div>Auton tiedot päivitetty onnistuneesti</div>";
                break;
            case "update_error":
                echo"<div>Auton tietoja ei voitu päivittää</div>";
                break;
            case "deleted":
                echo"<div>Tiedot poistettu</div>";
                break;
            case "error":
                echo"<div>Virhe</div>";
                break;
            case "added":
                echo"<div>Lisätty</div>";
                break;
            case "add_error":
                echo"<div>Virhe lisäämisessä</div>";
                break;
        }
    }

    if (isset($_GET["getKaikki"])) {
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);

        if ($response !== false) {
            $kaikki = json_decode($response, true);
            if (is_array($kaikki)) {
                $autot = array_merge($autot, $kaikki);
            }
        } else {
            $error = curl_error($ch);
            echo "Virhe API-kutsussa". htmlspecialchars($error);
        }
        curl_close($ch);
    }
    if (isset($_GET["getId"])) {
        $id = intval($_GET["getId"]);
        $response = @file_get_contents("$apiUrl?id=$id");
        if ($response) {
            $yksi = json_decode($response, true);
            if (is_array($yksi) && isset($yksi["ID"])) {
                $autot[] = $yksi;
            }
        }
    }
    if (!empty($autot)) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Merkki</th><th>Tyyppi</th><th>Vuosimalli</th><th>Toiminnot</th>";
        foreach ($autot as $auto) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($auto["ID"]) ."</td>";
            echo "<td>" . htmlspecialchars($auto["merkki"]) ."</td>";
            echo "<td>" . htmlspecialchars($auto["tyyppi"]) ."</td>";
            echo "<td>" . htmlspecialchars($auto["vuosimalli"]) ."</td>";
            echo "<td>";
            echo "<a href='muokkaa.php?id=" . urlencode($auto["ID"]) . "'>Muokkaa</a> ";
            echo "<a href='poista.php?id=" . urlencode($auto["ID"]) . "'>Poista</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Autoja ei löytynyt";
    }
    ?>
</body>
</html>