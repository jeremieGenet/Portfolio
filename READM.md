# DESCRIPTION

- Blog v3

- Articles (table blog_post) avec un titre, un slug, un contenu et une image
- Affichage de la liste des articles à la racine "/blog" (article avec une image)
- Systeme de stockage des images
- Systeme de pagination
- Catégorisation des articles (table 'blog_category' et table de liaison avec les article blog_category_post)

- Administration des articles "/admin" (Création, modification et suppréssion)
- Administration des catégories d'articles (Création, modification et suppréssion)

- Inscription d'un nouvel utilisateur
- Possibilité de se connecter ou se déconnecter 
(username = admin, mot de passe = admin) Pour tester le système de connexion



# Pour lancer le projet :

php -S localhost:8000 -t public

# Outils installés :

fzaninotto/faker = fausse donnée pour notre bdd

altorouter = librairie router
var_dumper = librairie qui permet un affichage propre des tableaux, objets...
whoops = librairie d'aide à l'affichage et le débug des erreurs
laracasts/flash = librairie qui s'occupe des messages flash