<?php
session_start();
include("db_connection.php"); // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST['mail']; // Récupérez l'adresse e-mail depuis le formulaire
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour vérifier les informations de connexion en utilisant l'adresse e-mail
    $query = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = :mail");
    $query->execute(array(':mail' => $mail));
    $user = $query->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        // L'utilisateur est authentifié avec succès
        $_SESSION['pseudo'] = $user['pseudo']; // Stockez le pseudo dans la session
        header("Location: connexion.php"); // Redirigez vers la page de connexion
    } else {
        // Affichez un message d'erreur si l'authentification a échoué
        echo "Identifiant ou mot de passe incorrect.";
    }
}
?>
