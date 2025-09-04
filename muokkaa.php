<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muokkaa</title>
</head>
<body>
    <h1>Muokkaa</h1>
    <?php
    //path to file
    $apiUrl = "http://localhost/jere/WebProjects/schoolprojects/apihar/autot_api.php";

    $auto = null;
    if (isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        $response = @file_get_contents("$apiUrl?id=$id");
        $auto = json_decode($response, true);
    }

    if (isset($_POST["update"])) {
        $data = [
            "ID"=> intval($_POST["id"]),
            "merkki"=> $_POST["merkki"],
            "tyyppi"=> $_POST["tyyppi"],
            "vuosimalli"=> intval($_POST["vuosimalli"])
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 204) {
            header("Location: index.php?status=update");
        } else {
            header("Location: index.php?status=update_error");
        }
        exit;
    }
    ?>

    <h2>Muokkaa autoa</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id)?>">
        <input type="text" name="merkki" value="<?= htmlspecialchars($auto['merkki'])?>" placeholder="Merkki">
        <input type="text" name="tyyppi" value="<?= htmlspecialchars($auto['tyyppi'])?>" placeholder="Tyyppi">
        <input type="number" name="vuosimalli" value="<?= htmlspecialchars($auto['vuosimalli'])?>" placeholder="Vuosimalli">
        <input type="submit" name="update" value="Tallenna muutokset">
    </form>
</body>
</html>