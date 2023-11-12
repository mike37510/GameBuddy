<?php
include("db_connection.php"); // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_jeu = $_POST['nom_jeu'];
    $id_support = $_POST['id_support'];

    // Vérifiez si un jeu avec le même nom existe pour le même support
    $query_check = $bdd->prepare("SELECT COUNT(*) FROM jeux WHERE nom_jeu = :nom_jeu AND id_support = :id_support");
    $query_check->execute(array(':nom_jeu' => $nom_jeu, ':id_support' => $id_support));
    $count = $query_check->fetchColumn();

    if ($count > 0) {
        // Le jeu avec le même nom existe déjà pour ce support, affichez un message d'erreur
        echo "Ce jeu existe déjà pour ce support.";
    } else {
        // Le jeu n'existe pas, effectuez l'insertion dans la table 'jeux'
        $query = $bdd->prepare("INSERT INTO jeux (nom_jeu, id_support) VALUES (:nom_jeu, :id_support)");
        $query->bindParam(':nom_jeu', $nom_jeu, PDO::PARAM_STR);
        $query->bindParam(':id_support', $id_support, PDO::PARAM_INT);
        $query->execute();

        // Vérifiez si une image a été fournie
        if (!empty($_FILES['image_jeu']['tmp_name'])) {
            $image_jeu_temp = $_FILES['image_jeu']['tmp_name'];

            // Ouvrez et lisez le contenu du fichier image
            $image_jeu = file_get_contents($image_jeu_temp);
        } else {
            // Utilisez une image par défaut si aucune image n'est fournie
            $image_jeu = file_get_contents("./vide.jpeg");
        }

        // Mettez à jour l'enregistrement précédemment inséré avec l'image
        $query_update = $bdd->prepare("UPDATE jeux SET image_jeu = :image_jeu WHERE nom_jeu = :nom_jeu AND id_support = :id_support");
        $query_update->bindParam(':image_jeu', $image_jeu, PDO::PARAM_LOB);
        $query_update->bindParam(':nom_jeu', $nom_jeu, PDO::PARAM_STR);
        $query_update->bindParam(':id_support', $id_support, PDO::PARAM_INT);
        $query_update->execute();

        // Redirigez l'utilisateur vers une page de confirmation ou une autre page
        header("Location: ajout_jeu_success.php");
    }
}
?>
