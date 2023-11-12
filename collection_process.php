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

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_jeu'])) {
        $id_jeu = $_POST['id_jeu'];

        // Récupérez l'ID de l'utilisateur en utilisant le pseudo
        $query = $bdd->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo");
        $query->execute(array(':pseudo' => $pseudo));
        $user = $query->fetch();

        if ($user) {
            $id_utilisateur = $user['id'];

            // Insérez le jeu dans la table de collection
            $insertQuery = $bdd->prepare("INSERT INTO collection (id_utilisateur, id_jeu) VALUES (:id_utilisateur, :id_jeu)");
            $insertQuery->execute(array(':id_utilisateur' => $id_utilisateur, ':id_jeu' => $id_jeu));

            // Vérifiez si l'insertion a réussi
            if ($insertQuery) {
                // Requête pour obtenir le nom du jeu ajouté et le nom du support associé
                $query = $bdd->prepare("SELECT j.nom_jeu, s.nom_plateforme FROM jeux j INNER JOIN supports s ON j.id_support = s.id WHERE j.id = :id_jeu");
                $query->execute(array(':id_jeu' => $id_jeu));
                $result = $query->fetch();

                if ($result) {
                    // Affichez un message de succès avec le nom du jeu et le nom du support
                    echo "Le jeu '{$result['nom_jeu']}' sur le support '{$result['nom_plateforme']}' a été ajouté à votre collection avec succès.";
                } else {
                    // Affichez un message d'erreur si la récupération des données a échoué
                    echo "Erreur lors de la récupération des données du jeu ajouté.";
                }
            } else {
                // Affichez un message d'erreur si l'ajout à la collection a échoué
                echo "Erreur lors de l'ajout du jeu à la collection.";
            }
        } else {
            // Affichez un message d'erreur si l'ID de l'utilisateur n'a pas été trouvé
            echo "Erreur lors de la récupération de l'ID de l'utilisateur.";
        }
    }
}
?>
