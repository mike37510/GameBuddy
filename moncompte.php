<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Ajoutez ici des styles spécifiques à la page moncompte.php si nécessaire */
        .emprunt-info {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
        }

        .emprunt-button {
            background-color: #333;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 10px;
        }

        .emprunt-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

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

    // Récupérez l'ID de l'utilisateur connecté
    $query_user = $bdd->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo");
    $query_user->execute(array(':pseudo' => $pseudo));
    $user = $query_user->fetch(PDO::FETCH_ASSOC);
    $id_utilisateur = $user['id'];

    echo "<p>ID de l'utilisateur connecté : $id_utilisateur</p>";

    // Requête pour récupérer les jeux avec des demandes d'emprunt en attente pour l'utilisateur connecté en tant que propriétaire
    $query_emprunts = $bdd->prepare("SELECT e.id_emprunt, j.nom_jeu, e.statut_emprunt, u.pseudo as emprunteur
    FROM emprunts e
    INNER JOIN jeux j ON e.id_jeu = j.id
    INNER JOIN collection c ON j.id = c.id_jeu
    INNER JOIN utilisateurs u ON e.id_emprunteur = u.id
    WHERE c.id_utilisateur = :id_utilisateur AND e.statut_emprunt = 'En attente'");
    $query_emprunts->execute(array(':id_utilisateur' => $id_utilisateur));

    // Afficher la requête SQL
    $query_emprunts_info = $query_emprunts->queryString;
    echo "<p>Requête SQL : $query_emprunts_info</p>";

    // Afficher les jeux avec des demandes d'emprunt en attente
    if ($query_emprunts->rowCount() > 0) {
        while ($row_emprunt = $query_emprunts->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="emprunt-info">';
            echo '<p>ID de l\'emprunt : ' . $row_emprunt['id_emprunt'] . '</p>';
            echo '<p>Jeu : ' . $row_emprunt['nom_jeu'] . '</p>';
            echo '<p>Emprunteur : ' . $row_emprunt['emprunteur'] . '</p>';
            echo '<p>Statut : ' . $row_emprunt['statut_emprunt'] . '</p>';
            
            // Bouton pour valider la demande d'emprunt
            echo '<form method="post" action="valider_emprunt.php">';
            echo '<input type="hidden" name="id_emprunt" value="' . $row_emprunt['id_emprunt'] . '">';
            echo '<input type="submit" value="Valider la demande" class="emprunt-button">';
            echo '</form>';

            echo '</div>';
        }
    } else {
        echo '<p>Aucune demande d\'emprunt en attente pour l\'utilisateur ID ' . $id_utilisateur . '.</p>';
    }
} else {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit;
}
?>

 <!-- Assurez-vous d'inclure le lien vers jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Assurez-vous d'inclure le lien vers le script JS -->
    <script src="script.js"></script>
</body>
</html>
