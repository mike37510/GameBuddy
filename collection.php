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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Résultats de recherche</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Assurez-vous d'ajouter le lien vers votre feuille de style CSS ici -->
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

        .collection-button {
            background-color: #333;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .collection-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<h2>Résultats de recherche :</h2>

<!-- Formulaire de recherche -->
<form method="post" action="collection.php">
    <label for "recherche">Rechercher un jeu :</label>
    <input type="text" name="recherche">
    <input type="submit" value="Rechercher">
</form>

<?php
include("db_connection.php"); // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recherche = $_POST['recherche'];

    // Effectuez une requête SQL pour rechercher le jeu, trier les résultats par ordre alphabétique du nom,
    // et obtenir le nom du support associé
    $query = $bdd->prepare("SELECT j.id, j.nom_jeu, s.nom_plateforme, j.image_jeu 
                            FROM jeux j 
                            INNER JOIN supports s ON j.id_support = s.id
                            WHERE j.nom_jeu LIKE :recherche 
                            ORDER BY j.nom_jeu");
    $query->execute(array(':recherche' => '%' . $recherche . '%'));

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="result">';
        echo '<h3>' . $row['nom_jeu'] . '</h3>';
        echo '<p>Support : ' . $row['nom_plateforme'] . '</p>';
        echo '<p>ID du jeu : ' . $row['id'] . '</p>'; // Ajout de l'ID du jeu
        if (!empty($row['image_jeu'])) {
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_jeu']) . '">';
        } else {
            echo 'Aucune image disponible.';
        }

        // Bouton pour ajouter le jeu à la collection
        echo '<form method="post" action="collection_process.php">';
        echo '<input type="hidden" name="id_jeu" value="' . $row['id'] . '">';
        echo '<input type="submit" value="Ajouter à la collection" class="collection-button">';
        echo '</form>';

        echo '</div>';
    }
}
?>
</body>
</html>
<?php
} else {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit;
}
?>
