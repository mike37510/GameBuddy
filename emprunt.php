<?php
session_start(); // Démarrer la session
$logged_in = false;

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['pseudo'])) {
    $logged_in = true;
    $pseudo = $_SESSION['pseudo'];
    echo "<p>Bienvenue, $pseudo !</p>";
}

if ($logged_in) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Demande d'emprunt</title>
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

            .emprunt-button {
                background-color: #333;
                color: #fff;
                padding: 5px 10px;
                border: none;
                border-radius: 3px;
                cursor: pointer;
            }

            .emprunt-button:hover {
                background-color: #555;
            }
        </style>
    </head>
    <body>
    <h2>Demande d'emprunt :</h2>

    <!-- Formulaire de recherche -->
    <form method="post" action="emprunt.php">
        <label for="recherche">Rechercher un jeu :</label>
        <input type="text" name="recherche">
        <input type="submit" value="Rechercher">
    </form>

    <!-- Afficher la collection de l'utilisateur avec la possibilité de demander un emprunt -->
    <?php
    include("db_connection.php"); // Inclure le fichier de connexion à la base de données

    // Recherche de jeux
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recherche'])) {
        $recherche = $_POST['recherche'];

        // Effectuez une requête SQL pour rechercher le jeu dans la base de données
        $query_search = $bdd->prepare("SELECT j.id, j.nom_jeu, s.nom_plateforme, j.image_jeu, u.pseudo as proprietaire
                                       FROM jeux j 
                                       INNER JOIN collection c ON j.id = c.id_jeu
                                       INNER JOIN utilisateurs u ON c.id_utilisateur = u.id
                                       INNER JOIN supports s ON j.id_support = s.id
                                       WHERE u.pseudo = :pseudo AND j.nom_jeu LIKE :recherche");
        $query_search->execute(array(':pseudo' => $pseudo, ':recherche' => '%' . $recherche . '%'));

        while ($row_search = $query_search->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="result">';
            echo '<h3>' . $row_search['nom_jeu'] . '</h3>';
            echo '<p>Support : ' . $row_search['nom_plateforme'] . '</p>';
            echo '<p>Propriétaire : ' . $row_search['proprietaire'] . '</p>';
            if (!empty($row_search['image_jeu'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row_search['image_jeu']) . '">';
            } else {
                echo 'Aucune image disponible.';
            }

            // Bouton pour demander un emprunt
            echo '<form method="post" action="emprunt_process.php">';
            echo '<input type="hidden" name="id_jeu" value="' . $row_search['id'] . '">';
            echo '<input type="submit" value="Demander un emprunt" class="emprunt-button">';
            echo '</form>';

            echo '</div>';
        }
    }
    ?>
    </body>
    </html>
    <?php
} else {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit;
}
?>
