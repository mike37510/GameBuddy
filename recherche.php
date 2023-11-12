<!DOCTYPE html>
<html>
<head>
    <title>Résultats de recherche</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Assurez-vous d'ajouter le lien vers votre feuille de style CSS ici -->
    <style>
        .result {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
        }

        .result img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
<h2>Résultats de recherche :</h2>

<!-- Formulaire de recherche -->
<form method="post" action="recherche.php">
    <label for="recherche">Rechercher un jeu :</label>
    <input type="text" name="recherche">
    <input type="submit" value="Rechercher">
</form>

<?php
include("db_connection.php"); // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recherche = $_POST['recherche'];

    // Effectuez une requête SQL pour rechercher le jeu, trier les résultats par ordre alphabétique du nom,
    // et obtenir le nom du support associé
    $query = $bdd->prepare("SELECT j.id, j.nom_jeu, s.nom_plateforme, j.image_jeu 
                            FROM jeux j 
                            INNER JOIN supports s ON j.id_support = s.id
                            WHERE j.nom_jeu LIKE :recherche 
                            ORDER BY j.nom_jeu");
    $query->execute(array(':recherche' => '%' . $recherche . '%'));

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="result">';
        echo '<h3>' . $row['nom_jeu'] . '</h3>';
        echo '<p>Support : ' . $row['nom_plateforme'] . '</p>';
        if (!empty($row['image_jeu'])) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_jeu']) . '">';
        } else {
            echo 'Aucune image disponible.';
        }

        // Ajoutez une requête pour obtenir les utilisateurs qui possèdent ce jeu
        $query_users = $bdd->prepare("SELECT u.pseudo FROM utilisateurs u
                                      INNER JOIN collection c ON u.id = c.id_utilisateur
                                      WHERE c.id_jeu = :jeu_id");
        $query_users->execute(array(':jeu_id' => $row['id']));

        if ($users = $query_users->fetchAll(PDO::FETCH_ASSOC)) {
            echo '<p>Propriétaires : ';
            foreach ($users as $user) {
                echo $user['pseudo'] . ' ';
            }
            echo '</p>';
        } else {
            echo '<p>Aucun propriétaire trouvé.</p>';
        }

        echo '</div>';
    }
}
?>

</body>
</html>
