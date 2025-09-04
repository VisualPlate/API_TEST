<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uusi</title>
</head>
<body>
    <h1>Muokkaa</h1>
    <?php
    require("path.php");

    $auto = null;
    if (isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        $response = @file_get_contents("$apiUrl?id=$id");
        $auto = json_decode($response, true);
    }

    if (isset($_POST["add"])) {
        $data = [
            "merkki"=> $_POST["merkki"],
            "tyyppi"=> $_POST["tyyppi"],
            "vuosimalli"=> intval($_POST["vuosimalli"])
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            header("Location: index.php?status=added");
        } else {
            header("Location: index.php?status=add_error");
        }
        exit;
    }
    ?>

    <h2>Lisää auto</h2>
    <form method="post">
        <input type="text" name="merkki" placeholder="Merkki">
        <input type="text" name="tyyppi" placeholder="Tyyppi">
        <input type="number" name="vuosimalli" placeholder="Vuosimalli">
        <input type="submit" name="add" value="Lisää">
    </form>
</body>
</html>