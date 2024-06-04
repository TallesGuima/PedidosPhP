<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "Cefet";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    


   
} catch(PDOException $e) {
    echo "Falha no pedido. Tente novamente mais tarde." . $e->getMessage();
}
header("Refresh: 0.5; URL=index.php");
$conn = null;

