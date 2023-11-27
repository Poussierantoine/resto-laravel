
**Site de restaurant en Laravel - projet MIASHS IC 2023**
================================================
Antoine Poussier
----------------



# Description
Ce depot correspond à un projet réalisé dans le cadre de ma formation.
J'ai réalisé un faux site web pour une plateforme qui met en avant des restaurants. 
Les technologies utilisées sont Laravel (avec Blade), JetStream, Fortify, Livewire, TailwindCSS et AlpineJS. (AlpineJS est utilisée par JetStream mais je n'ai personnellement pas exploré ses fonctionnalités).
J'ai essayé de minimiser les feuilles de style CSS et d'utiliser principalement tailwind pour le style du front-end.

Les images ont été prises sur google image et les icônes dans la librairie [Feather](https://feathericons.com/) (voir plus bas).


Le site permet aux utilisateurs de voir les différents restaurants disponibles et de contacter les administrateurs du site. Si les utilisateurs s'enregistrent, ils ont acces à plusieurs fonctionnalités. Ils peuvent créer un restaurant, ou commenter leur restaurants ou ceux des autres. Les restaurants comportent un nom, une description et des tags (soit de type tag, soit de type foodType qui représentent les types de cuisine proposés par le restaurant). Les administrateurs peuvent activer/désactiver les tags, foodTypes et restaurants (inactifs par defaut lorsqu'ils sont créés), supprimer les demandes de support lorsqu'elles sont traitées. Il peuvent aussi changer les rôles des utilisateurs (user/admin).
Les utilisateurs (user / admin) auront accès à un tableau de bord qui leur permet d'effectuer les différentes actions (excepté la demande de support et l'ajout de commentaires qui sont dans des vues publiques).
Les utilisateurs qui possèdent un restaurant inactif peuvent voir à quoi ressemble leur restaurant depuis leur tableau de bord.

La base de donnée (SQLLite) est reliée au projet via le fichier .env et par un système de migration, models, controllers.
Des données de tests sont générées le plus aléatoirement possible par un système de seeder qui permet au site de permettre toutes ses fonctionnalités sans toucher manuellement à la base de données.
Il est possible de lancer chaque Seeder séparément dans l'ordre qu'on veut, les données seront générées pour le seeder en question et d'autres tables si elles sont dépendantes (par exemple, un restaurant a nécessairement besoin d'un propriétaire, le seeder va donc également créer un user).
L'architecture du projet suit l'architecture fournie par Laravel, les images sont stockées dans le dossier storage (exeptées les icones et l'image principale du site) Les images correspondant aux données de tests seront fournies avec le projet mais l'import de nouvelles images se fera automatiquement.

Les données générées par les seeder fournissent 3 utilisateurs avec lequel vous pouvez vous connecter:

utilisateur de rôle admin:

**email:** admin@admin

**password:** admin

utilisateurs standards:

**email:** user1@user1

**password:** user1

**email:** user2@user2

**password:** user2


L'utilisateur user1 a par défaut deux restaurants, un contact-support et un commentaire qu'on peut retrouver dans son tableau de bord.
L'utilisateur user2 n'a rien.


Sur la page d'accueil du site sont présentés 4 restaurants dans un carrousel qui comprend des liens vers leurs pages respectives ainsi qu'un lien vers la pages des restaurants (vous trouverez également ce lien dans la navbar).




# Installation


Pour installer le projet, vous devez d'abord cloner le dépot git dans un dossier de votre choix.
Ensuite, il faut se placer dans le bon répertoire et ouvrir deux terminals: vous pouvez pour cela directement ouvrir les terminals dans le dossier restaurant depuis l'explorateur de fichiers ou taper la commande suivante depuis la source du dossier:
```shell
cd restaurant
```

Vous devez donc vous trouver dans le répertoire "~/resto-laravel/restaurant"

Il faut égallement créer un fichier .env à la racine du projet (.../restaurant/.env) et y copier le contenu du fichier .env.example. Ensuite, vous devez modifier les paramètres de connexion à la base de donnée (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) pour qu'ils correspondent à votre configuration locale.

Il faut ensuite lancer les commandes suivantes pour installer les librairies nécessaires:
```shell
composer install
npm install
```

Ensuite, vous devez créer la base de donnée et lancer les migrations et les seeders avec la commande suivante:
```shell
php artisan migrate:fresh --seed
```

Ensuite, il faut générer le lien depuis le dossier public vers le storage pour les images en tapant cette commande:
```shell
php artisan storage:link
```

Enfin, il faut construire la partie front-end avec la commande suivante:
```shell
npm run dev
```

Une fois le projet prêt, vous pouvez lancer le serveur local avec la commande suivante dans un second terminal (même dossier resto-poussier/restaurant):
```shell
php artisan serve
```
Ensuite, vous pouvez vous rendre sur l'url http://127.0.0.1:8000/ pour accéder au site (l'url devrait correspondre à l'url que renvoie la commande serve, sinon, préférez celle renvoyée par la commande)


# Utilisation

La nav-bar en haut du site permet la navigation. Selon si l'utilisateur est connecté et selon son rôle, les liens et l'affichage des pages disponibles sont modifiés.

Pour ajouter un commentaire à un restaurant, l'utilisateur doit être connecté et avoir le rôle 'user' et il faut ensuite se rendre sur une page de restaurant et descendre jusqu'au formulaire de commentaire.

Pour créer un contact support, l'utilisateur doit soit être un 'user', soit ne pas être connecté, puis se rendre sur la page "nous contacter" grâce à la nav-bar (un utilisateur connecté aura déjà son nom et son email de rempli)

Pour créer un restaurant l'utilisateur doit être de type 'user' et se rendre dans son tableau de bord -> créer un restaurant.

Un utilisateur peut éditer / supprimer ses restaurants depuis la page "voir tous mes restaurants" disponible dans le tableau de bord (uniquement s'il en a).
L'icône éditer dans la dernière colonne envoi sur un formulaire d'édition du restaurant concerné (c'est le seul formulaire d'édition qui n'est pas en ajax car il est trop conséquent)

L'utilisateur peut également modifier le contenu de ses contact-support / commentaires depuis le tableau de bord avec les liens "voir tous mes ..." (en ajax cette fois).

L'admin peut rendre actif/ inactif les tags, foodTypes et restaurants depuis son tableau de bord (en ajax) en cliquant sur le bouton approprié.
Si des restaurants / tags / foodtypes sont inactif, l'information est affichée sur le tableau de bord.

L'admin peut également supprimer les tags et foodTypes.

Il peut enfin changer le rôle d'un utilisateur également depuis le crud disponible à partir du tableau de bord.



# Choix personnels

- J'ai choisi de mettre deux niveaux d'authentification (auth et admin). Admin est dédié à  un administrateur ou au possesseur du site, auth est dédiéaux utilisateurs.
- Les tags et les food_types sont gérés sous forme de models avec leur table dans la base de données et sont liés par une relation many to many au model restaurant.
- Les Seeders permettent tous de générer une quantité voulue de donnée grâce aux factories, quel que soit le seeder. S'il est utilisé seul, il va générer les données (même dans d'autres tables) dont il a besoin. (les seeders de Tag et FoodType génèrent d'abord des instances prédéfinies puis basculent sur de l'aléatoire).
- J'ai créé un système de popup pour faire des retours à l'utilisateur sur ses actions mais je me suis rendu compte plus tard que pour un vrai site, ce ne serait pas très propre, il aurai mieux valu les gérer en javascript alors qu'ils sont actuellement générés via livewire. J'ai néanmoins gardé ce système qui fonctionne.
- J'ai créé une classe pour les imports d'images et leur redimensionnement en thumbnail.
- J'ai persévéré à créer un crud entierèment générique dans un composant livewire, qui permet que si l'application s'étend, il n'y aurait normalement plus à faire de crud mais seulement une ou deux fonctions dans un controlleur. Le composant permet, à partir des tableaux renvoyés par les méthodes getCrud() des controllers de récupérer toutes les données de la BDD (la table est récuperée directement dans le composant -> $modelName) et de permettre l'édition (ajax avec la fonction editForm du controller ou redirect vers la fonction edit) la suppression et le tri suivant les valeurs du tableau. Cette partie m'a pris énormément de temps et m'a retardé pour les fonctionnalités secondaires que je voulais intégrer mais j'ai appris beaucoup et c'est du code que je pourrai certainement réutiliser sur d'autres projets (bien que le format des array qui transitent serait peut être à retravailler)
- J'ai essayé de rendre le site au maximum responsive avec tailwind (la nav bar étant déja gérée par jetStream) mais je ne me suis pas penché sur le responsive du crud générique (qui est assez complexe et qui aurait été compliqué à gérer sans javascript)
- J'ai fait du javascript uniquement pour le carrousel de la page d'accueil et ai essayé de tout faire avec laravel. Ce n'est pas forcément ce que j'aurais fait sur un autre projet mais je voulais explorer les possibilités et les limites du framework. Il est possible que mon utilisation de livewire soit un peu forcée par moment alors qu'AlpineJS se marie très bien avec et m'aurait permis de faire parfois les mêmes choses (je n'ai pas eu le temps de m'y pencher)

