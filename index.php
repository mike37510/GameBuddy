<!DOCTYPE html>
<html>
<head>
    <title>Mon Site de Jeux !</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Assurez-vous d'ajouter le lien vers votre feuille de style CSS ici -->
</head>
<body>
    <header>
        <h1>Mon Site de Jeux</h1>
    </header>

    <nav>
        <ul>
            <li><a href="ajout_jeu.php">Ajouter un jeu</a></li>
            <li><a href="listejeux.php">Liste des jeux</a></li>
            <li><a href="recherche.php">Recherche de jeux</a></li>
            <li><a href="collection.php">Gestion de ma collection</a></li>
            <li><a href="emprunt.php">Gestion des emprunts</a></li>
            <li><a href="voirmacollection.php">Voir ma collection</a></li>
            <li><a href="inscription.php">Inscription</a></li>
            <li><a href="connexion.php">Connexion</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>

        </ul>
    </nav>

    <main>
        <!-- Le contenu de votre page d'accueil peut être placé ici -->
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Mon Site de Jeux</p>
    </footer>
</body>
</html>
