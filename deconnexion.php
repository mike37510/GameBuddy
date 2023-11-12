<?php
session_start();
session_destroy(); // Détruire la session
header("Location: connexion.php"); // Redirigez vers la page de connexion
?>
