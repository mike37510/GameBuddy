<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Inscription</h1>
    <form method="post" action="inscription_process.php">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required><br>

        <label for="mail">Adresse e-mail :</label>
        <input type="email" name="mail" required><br>

        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" required><br>

        <label for="mot_de_passe">Mot de passe (8 caractères minimum, lettres et chiffres) :</label>
        <input type="password" name="mot_de_passe" required><br>

        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
