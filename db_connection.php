<?php
$host = "192.168.1.254";
$user = "mabase";
$pass = "mabase";
$dbname = "mabase";

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
