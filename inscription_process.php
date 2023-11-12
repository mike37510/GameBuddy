<?php
// Inclure le fichier de connexion à la base de données
include("db_connection.php");

// Récupération des données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$mail = $_POST['mail'];
$pseudo = $_POST['pseudo'];
$mot_de_passe = $_POST['mot_de_passe'];

// Vérification des restrictions
$errors = array();

if (strlen($mot_de_passe) < 8) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
}

if (!preg_match('/[A-Z]/', $mot_de_passe) || !preg_match('/[a-z]/', $mot_de_passe) || !preg_match('/[0-9]/', $mot_de_passe)) {
    $errors[] = "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.";
}

// Vérification de l'unicité du pseudo
$pseudo_query = $bdd->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo");
$pseudo_query->execute(array(':pseudo' => $pseudo));

if ($pseudo_query->rowCount() > 0) {
    $errors[] = "Ce pseudo existe déjà.";
}

// Vérification de l'unicité de l'e-mail
$mail_query = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = :mail");
$mail_query->execute(array(':mail' => $mail));

if ($mail_query->rowCount() > 0) {
    $errors[] = "Cette adresse e-mail est déjà utilisée.";
}

if (!empty($errors)) {
    // Afficher les erreurs
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
} else {
    // Toutes les vérifications sont passées, vous pouvez insérer les données dans la base de données
    $mot_de_passe = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    $insert_query = $bdd->prepare("INSERT INTO utilisateurs (nom, prenom, mail, pseudo, mot_de_passe) VALUES (:nom, :prenom, :mail, :pseudo, :mot_de_passe)");
    $insert_query->execute(array(':nom' => $nom, ':prenom' => $prenom, ':mail' => $mail, ':pseudo' => $pseudo, ':mot_de_passe' => $mot_de_passe));

    // Redirection vers une page de succès
    header("Location: inscription_success.php");
}
?>
