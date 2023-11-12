<?php
session_start();
include("db_connection.php"); // Inclure le fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['pseudo'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Récupérer l'ID de l'utilisateur connecté
$query_user = $bdd->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo");
$query_user->execute(array(':pseudo' => $_SESSION['pseudo']));
$user = $query_user->fetch(PDO::FETCH_ASSOC);
$id_utilisateur = $user['id'];

// Récupérer les demandes d'emprunt en attente pour les jeux de l'utilisateur
$query_emprunts = $bdd->prepare("SELECT e.id_emprunt, j.nom_jeu, u.pseudo AS emprunteur, e.statut_emprunt
                                 FROM emprunts e
                                 INNER JOIN jeux j ON e.id_jeu = j.id
                                 INNER JOIN utilisateurs u ON e.id_emprunteur = u.id
                                 WHERE e.id_proprietaire = :id_utilisateur AND e.statut_emprunt = 'En attente'");
$query_emprunts->execute(array(':id_utilisateur' => $id_utilisateur));

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valider Emprunts</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Ajoutez ici des styles spécifiques à la page valider_emprunt.php si nécessaire */
    </style>
</head>
<body>
    <h2>Demandes d'emprunt en attente</h2>

    <?php
    // Afficher la liste des demandes d'emprunt en attente
    while ($row = $query_emprunts->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="emprunt">';
        echo '<p><strong>Jeu :</strong> ' . $row['nom_jeu'] . '</p>';
        echo '<p><strong>Emprunteur :</strong> ' . $row['emprunteur'] . '</p>';
        echo '<p><strong>Statut :</strong> ' . $row['statut_emprunt'] . '</p>';
        echo '<button onclick="validerEmprunt(' . $row['id_emprunt'] . ')">Valider</button>';
        echo '</div>';
    }
    ?>

    <!-- Assurez-vous d'inclure le lien vers jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Assurez-vous d'inclure le lien vers le script JS -->
    <script src="script.js"></script>

    <script>
        function validerEmprunt(idEmprunt) {
            // Envoyer une requête AJAX pour valider l'emprunt avec l'identifiant idEmprunt
            $.ajax({
                type: "POST",
                url: "valider_emprunt_process.php",
                data: { idEmprunt: idEmprunt },
                success: function(response) {
                    // Mettre à jour la page ou afficher un message de succès
                    alert(response);
                    location.reload(); // Recharger la page après la validation de l'emprunt
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>
</body>
</html>
