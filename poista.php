<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poista</title>
</head>
<body>
    <?php
    require("path.php");

    $autot = [];
    if (isset($_GET["id"])) {
        $ID = intval($_GET["id"]);
        $ch = curl_init("$apiUrl?id=$ID");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode === 200 || $httpCode === 204) {
            header("Location: index.php?status=deleted");
        } else {
            header("Location: index.php?status=error");
        }
        exit;
    }
    ?>
</body>
</html>