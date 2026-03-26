# Consignes

### 1. Clonez le repository, et le faire marcher
- Soit en mettant le dossier au bon endroit dans xampp, laragon ou uWamp (dans le dossier wwww ou htdocs).
- Soit en utilisant Docker si vous savez le faire.

### 2. Modifier le code pour:

#### 2.1 Suppression d'un pokemon:
 - Sur chaque ligne du tableau, Ajouter un mini formulaire avec juste un bouton qui permet de supprimer un pokemon de notre liste
 - L'envoie de ce formulaire doit déclancher une requete SQL de suppression du pokemon concerné.

#### 2.2 Modifier le nom d'un pokemon dans la DB
- Créer un formulaire qui déclanche un requêtre SQL d'update pour le pokemon concerné.

#### 2.3 Afficher une page pour avoir les infos détaillés d'un pokemon
 - Faire une autre requete vers tyradex pour récuperer des infos en plus (ex: taille, poids, stats du pokemon..)
- Créer une table `pokemon_details` et l'utiliser pour stocker ces infos la:
  - Les infos doivent être liées au bon pokemon de la table `pokemons` via une clé étrangère
- Faire la mise en page (génération du html) sur un fichier `pokemon.php`
