<!DOCTYPE html>
<html>
<head>
    <title>Liste des Jeux</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Assurez-vous d'ajouter le lien vers votre feuille de style CSS ici -->
    <style>
        .game {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
        }

        .game img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
<h2>Liste des Jeux :</h2>

<?php
include("db_connection.php"); // Inclure le fichier de connexion à la base de données

// Effectuez une requête SQL pour récupérer la liste des jeux, triés par ordre alphabétique du nom, en affichant le nom du support
$query = $bdd->query("SELECT j.id, j.nom_jeu, s.nom_plateforme, j.image_jeu 
                      FROM jeux j 
                      INNER JOIN supports s ON j.id_support = s.id
                      ORDER BY j.nom_jeu");

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo '<div class="game">';
    echo '<h3>' . $row['nom_jeu'] . '</h3>';
    echo '<p>Support : ' . $row['nom_plateforme'] . '</p>';
    if (!empty($row['image_jeu'])) {
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_jeu']) . '">';
    } else {
        echo 'Aucune image disponible.';
    }
    echo '</div>';
}
?>

</body>
</html>
