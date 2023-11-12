<?php
session_start(); // Démarrer la session
$logged_in = false;

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['pseudo'])) {
    $logged_in = true;
    $pseudo = $_SESSION['pseudo'];
    echo "<p>Bienvenue, $pseudo !";
}

if ($logged_in) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Ajouter un jeu</title>
        <link rel="stylesheet" type="text/css" href="style.css"> <!-- Assurez-vous d'ajouter le lien vers votre feuille de style CSS ici -->
    </head>
    <body>
    <h2>Ajouter un jeu</h2>
    <form method="post" action="ajout_jeu_process.php" enctype="multipart/form-data">
        <label for="nom_jeu">Nom du jeu :</label>
        <input type="text" name="nom_jeu" required><br>

        <label for="id_support">Plateforme :</label>
        <select name="id_support" required>
            <?php
            include("db_connection.php"); // Inclure le fichier de connexion à la base de données

            // Récupérez la liste des plateformes depuis la table 'supports'
            $query = $bdd->query("SELECT id, nom_plateforme FROM supports");

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['id'] . "'>" . $row['nom_plateforme'] . "</option>";
            }
            ?>
        </select><br>

        <label for="image_jeu">Image du jeu :</label>
        <input type="file" name="image_jeu" accept="image/*"><br>

        <input type="submit" value="Ajouter">
    </form>
    </body>
    </html>
    <?php
} else {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit;
}
?>
