<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Connexion</h1>

    <?php
    session_start(); // Démarrer la session
    $logged_in = false;

    // Vérifiez si l'utilisateur est connecté
    if (isset($_SESSION['pseudo'])) {
        $logged_in = true;
        $pseudo = $_SESSION['pseudo'];
        echo "<p>Bienvenue, $pseudo !</p>";
    }

    if (!$logged_in) {
        // Affichez le formulaire de connexion
    ?>
    <form method="post" action="connexion_process.php">
        <label for="mail">Adresse e-mail :</label>
        <input type="email" name="mail" required><br>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" name="mot_de_passe" required><br>

        <input type="submit" value="Se connecter">
    </form>
    <?php
    }
    ?>


</body>
</html>
