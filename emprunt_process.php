<?php
session_start();
include("db_connection.php"); // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_jeu'])) {
    $id_jeu = $_POST['id_jeu'];

    // Récupérez l'ID de l'utilisateur connecté
    $query_user = $bdd->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo");
    $query_user->execute(array(':pseudo' => $_SESSION['pseudo']));
    $user = $query_user->fetch(PDO::FETCH_ASSOC);
    $id_demandeur = $user['id'];

    // Récupérez l'ID du propriétaire du jeu
    $query_owner = $bdd->prepare("SELECT id_utilisateur FROM collection WHERE id_jeu = :id_jeu");
    $query_owner->execute(array(':id_jeu' => $id_jeu));
    $owner = $query_owner->fetch(PDO::FETCH_ASSOC);
    $id_proprietaire = $owner['id_utilisateur'];

    // Vérifiez si une demande d'emprunt existe déjà pour ce jeu et cet utilisateur
    $query_check = $bdd->prepare("SELECT COUNT(*) FROM emprunts WHERE id_jeu = :id_jeu AND id_emprunteur = :id_demandeur");

    $query_check->execute(array(':id_jeu' => $id_jeu, ':id_demandeur' => $id_demandeur));
    $count = $query_check->fetchColumn();

    if ($count > 0) {
        // La demande d'emprunt existe déjà, affichez un message d'erreur
        echo "Vous avez déjà fait une demande d'emprunt pour ce jeu.";
    } else {
        // La demande d'emprunt n'existe pas, effectuez l'insertion dans la table 'emprunts'
        $query_insert = $bdd->prepare("INSERT INTO emprunts (id_jeu, id_proprietaire, id_emprunteur, statut_emprunt) VALUES (:id_jeu, :id_proprietaire, :id_demandeur, 'En attente')");
        $query_insert->execute(array(':id_jeu' => $id_jeu, ':id_proprietaire' => $id_proprietaire, ':id_demandeur' => $id_demandeur));

        // Affichez un message de succès
        echo "La demande d'emprunt a été prise en compte.";
    }
} else {
    echo "Erreur de traitement du formulaire.";
}
?>
