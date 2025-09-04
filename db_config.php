<?php
    $host = "localhost";
    $db = "213598";

    
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Yhteys ongelma:". $conn->connect_error);
    }
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>