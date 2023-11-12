<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Ajoutez ici des styles spécifiques à la page moncompte.php si nécessaire */
    </style>
</head>
<body>
   
   


<?php
session_start();
include("db_connection.php");

if (isset($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];

    // Récupérez l'ID de l'utilisateur connecté
    $query_user = $bdd->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo");
    $query_user->execute(array(':pseudo' => $pseudo));
    $user = $query_user->fetch(PDO::FETCH_ASSOC);
    $id_utilisateur = $user['id'];

    // Sélectionnez les demandes d'emprunt en attente pour cet utilisateur
    $query_emprunts = $bdd->prepare("SELECT e.id_emprunt, j.nom_jeu, u.pseudo AS proprietaire, e.statut_emprunt
                                    FROM emprunts e
                                    INNER JOIN jeux j ON e.id_jeu = j.id
                                    INNER JOIN utilisateurs u ON e.id_proprietaire = u.id
                                    WHERE e.id_emprunteur = :id_utilisateur
                                    AND e.statut_emprunt = 'En attente'");
    $query_emprunts->execute(array(':id_utilisateur' => $id_utilisateur));

    if ($emprunts = $query_emprunts->fetchAll(PDO::FETCH_ASSOC)) {
        // Affichez les informations sur les demandes d'emprunt
        foreach ($emprunts as $emprunt) {
            echo '<div>';
            echo '<p>ID Emprunt : ' . $emprunt['id_emprunt'] . '</p>';
            echo '<p>Jeu : ' . $emprunt['nom_jeu'] . '</p>';
            echo '<p>Propriétaire : ' . $emprunt['proprietaire'] . '</p>';
            echo '<p>Statut : ' . $emprunt['statut_emprunt'] . '</p>';
            
            // Ajout du formulaire pour valider l'emprunt
            echo '<form method="post" action="valider_emprunt.php">';
            echo '<input type="hidden" name="id_emprunt" value="' . $emprunt['id_emprunt'] . '">';
            echo '<input type="submit" value="Valider l\'emprunt">';
            echo '</form>';
            
            echo '</div>';
        }
    } else {
        echo 'Aucune demande d\'emprunt en attente.';
    }
} else {
    echo 'Vous devez être connecté pour accéder à cette page.';
}
?>


 <!-- Assurez-vous d'inclure le lien vers jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Assurez-vous d'inclure le lien vers le script JS -->
    <script src="script.js"></script>
</body>
</html>

