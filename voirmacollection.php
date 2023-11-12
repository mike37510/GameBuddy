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
    include("db_connection.php"); // Inclure le fichier de connexion à la base de données

    // Récupérez l'ID de l'utilisateur en utilisant le pseudo
    $query = $bdd->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo");
    $query->execute(array(':pseudo' => $pseudo));
    $user = $query->fetch();

    if ($user) {
        $id_utilisateur = $user['id'];

        // Requête pour récupérer les jeux dans la collection de l'utilisateur
        $query = $bdd->prepare("SELECT j.nom_jeu, s.nom_plateforme, j.image_jeu
                                FROM collection c
                                INNER JOIN jeux j ON c.id_jeu = j.id
                                INNER JOIN supports s ON j.id_support = s.id
                                WHERE c.id_utilisateur = :id_utilisateur");
        $query->execute(array(':id_utilisateur' => $id_utilisateur));

        if ($query) {
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Ma Collection</title>
                <link rel="stylesheet" type="text/css" href="style.css"> <!-- Assurez-vous d\'ajouter le lien vers votre feuille de style CSS ici -->
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
            <h2>Ma Collection :</h2>';
            
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="result">';
                echo '<h3>' . $row['nom_jeu'] . '</h3>';
                echo '<p>Support : ' . $row['nom_plateforme'] . '</p>';
                if (!empty($row['image_jeu'])) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_jeu']) . '">';
                } else {
                    echo 'Aucune image disponible.';
                }
                echo '</div>';
            }

            echo '</body>
            </html>';
        } else {
            echo "Erreur lors de la récupération des jeux dans la collection.";
        }
    } else {
        echo "Erreur lors de la récupération de l'ID de l'utilisateur.";
    }
} else {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit;
}
?>
