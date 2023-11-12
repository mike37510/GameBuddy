# GameBuddy
**GameBuddy** est votre compagnon de jeu ultime, conçu pour simplifier et améliorer le partage de vos jeux vidéo préférés avec vos amis. Avec GameBuddy, vous pouvez facilement prêter et emprunter des jeux, créer une liste de souhaits pour savoir quels jeux vos amis aimeraient essayer, et rester connecté avec une communauté de joueurs partageant les mêmes intérêts. **En cours de developpement**

__**Inscription et Connexion :**__

Les utilisateurs peuvent créer un compte en fournissant des informations telles que leur nom, prénom, adresse e-mail, pseudo et mot de passe.
Ils peuvent se connecter avec leur pseudo et mot de passe une fois qu'ils ont créé un compte.

**Gestion de Collection :**

Les utilisateurs peuvent ajouter des jeux à leur collection. La relation entre les utilisateurs et les jeux est stockée dans la table collection.
La page emprunt.php permet à un utilisateur connecté de rechercher des jeux dans la collection d'autres utilisateurs et de demander un emprunt.

**Demande d'Emprunt :**

Lorsqu'un utilisateur envoie une demande d'emprunt pour un jeu, l'information est enregistrée dans la table emprunts avec le statut initial "En attente".
Les utilisateurs peuvent voir les demandes d'emprunt en attente sur la page moncompte.php.

**Validation d'Emprunt :**

Sur la page moncompte.php, le propriétaire d'un jeu peut voir les demandes d'emprunt en attente pour ses jeux et peut les valider.
Interface Visuelle :

Des pages comme emprunt.php et moncompte.php sont visuellement conviviales avec un formulaire de recherche, des résultats de recherche, et des fonctionnalités pour interagir avec la base de données.

**Sécurité :**

Utilisation de sessions pour gérer l'authentification des utilisateurs.
Utilisation de requêtes préparées pour éviter les injections SQL.





