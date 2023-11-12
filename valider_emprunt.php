<?php
session_start(); // Démarrer la session

if (isset($_SESSION['pseudo'])) {
    include("db_connection.php"); // Inclure le fichier de connexion à la base de données

    // Vérifier si la demande provient d'un formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_emprunt'])) {
        $id_emprunt = $_POST['id_emprunt'];

        // Mettre à jour le statut de la demande d'emprunt dans la base de données
        $query_update = $bdd->prepare("UPDATE emprunts SET statut_emprunt = 'Validé' WHERE id_emprunt = :id_emprunt");
        $query_update->execute(array(':id_emprunt' => $id_emprunt));

        // Rediriger l'utilisateur vers la page moncompte.php après la validation
        header("Location: moncompte.php");
        exit;
    } else {
        // Rediriger l'utilisateur vers la page moncompte.php si la demande ne provient pas d'un formulaire
        header("Location: moncompte.php");
        exit;
    }
} else {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: connexion.php");
    exit;
}
?>
