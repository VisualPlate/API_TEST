<?php
    //apiUrl at API_path.php
    require("API_path.php");

    $response = file_get_contents("$apiUrl");
    $data = json_decode($response, true);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
</head>
<body>
<h1>Pörssisähkön tuntihinnat sentteinä tänään</h1>
<canvas id="electricityPriceChart" style="width:100%;max-width:100vw"></canvas>

<?php
    //values for inline header data text
    $xValue = [];
    foreach ($data as $row => $r) {
        $xValue[] = '"' . substr(trim($r['DateTime']), 11, 8) . '"';
    }
    $xValues =  implode(',', $xValue);

    //price w tax
    $priceValue = [];
    foreach ($data as $row => $r) {
        $priceValue[] = number_format($r["PriceWithTax"] * 100,2);
    }
    $priceValues =  implode(',', $priceValue);
    //price w tax
    $priceTaxlessValue = [];
    foreach ($data as $row => $r) {
        $priceTaxlessValue[] = number_format($r["PriceNoTax"] * 100, 2);
    }
    $priceTaxlessValues =  implode(',', $priceTaxlessValue);
?>
<!--varibles for cart that cant be inside chart file-->
<script>
const xValues = [<?=$xValues?>];
const priceValues = [<?=$priceValues?>];
const priceTaxlessValues = [<?=$priceTaxlessValues?>];
</script>
<!--file for chart-->
<script src="mainChart.js"></script>

</body>
</html>