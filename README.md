
**Site de restaurant en Laravel - MIASHS IC 2023**
================================================
Antoine Poussier
----------------



# Description
Le projet constitue un site web d'une plateforme qui met en avant des restaurants. 
Les technologies utilisées sont Laravel (avec Blade), JetStream, Fortify, Livewire, TailwindCSS et AlpineJS. (AlpineJS est utilisée par JetStream mais je n'ai personnellement pas exploré ses fonctionnalités)
J'ai essayé de minimiser les feuilles de style CSS et d'utiliser principalement tailwind pour le style du front-end

Les images ont été prises sur google image et les icones dans la librairie [Feather](https://feathericons.com/) (voir plus bas)


Le site permet aux utilisateurs de voir les differents restaurants disponibles et de contacter les administrateurs du site. si les utilisateurs s'enregistrent, ils ont acces à plusieurs fonctionnalités. Ils peuvent créer un restaurant, ou commenter leur restaurants ou ceux des autres. Les restaurants comportent un nom, une description et des tags (soit de type tag, soit de type foodType qui representent les types de cuisine proposés par le restaurant). Les administrateurs peuvent activer/desactiver les tags, foodTypes et restaurants (inactifs par defaut lorsqu'ils sont créés), supprimer les demandes de supports lorsqu'elles sont traitées. Il peuvent aussi changer les roles des utilisateurs (user/admin).
Les utilisateurs (user / admin) auront accès à un tableau de bord qui leur permet d'effectuer les differents actions (exepté la demande de support et l'ajout de commentaires qui sont dans des vues publiques)
les utilisateurs qui possedent un restaurant inactif peuvent voir a quoi ressemble leur restaurant depuis leur tableau de bord.

La base de donnée (SQLLite) est reliée au projet via le fichier .env et par un systeme de migration, models, controllers.
Des données de tests sont generées le plus aleatoirement possible par un systeme de seeder qui permet au site de permettre toutes ses fonctionnalités sans toucher manuellement à la base de donnée.
Il est possible de lancer chaque Seeder séparement dans l'ordre qu'on veut, les données seront generées pour le seeder en question et d'autres tables si des tables sont dependantes (par exemple, un restaurant a necessairement besoin d'un proprietaire, le seeder va donc egalement créer un user)
L'architecture du projet suit l'architecture fournie par Laravel, les images sont stockées dans le dossier storage (exeptées les icones et l'image principale du site) Les images correspondant aux données de tests seront fournies avec le projet mais l'import de nouvelles images se fera automatiquement.

Les données generées par les seeder fournissent 3 utilisateurs avec lequel vous pouvez vous connecter:
utilisateur de role admin:

**email:** admin@admin

**password:** admin

utilisateurs standart:

**email:** user1@user1

**password:** user1

**email:** user2@user2

**password:** user2


L'utilisateur user1 a par defaut deux restaurants, un contact-support et un commentaire qu'on peut retrouver dans son tableau de bord.
L'utilisateur user2 n'a rien.


sur la page d'accueil du site sont presentés 4 restaurants dans un carousel qui comprend des liens vers leurs pages respectives ainsi qu'un lien vers la pages des restaurants (vous trouverez egalement ce lien dans la navbar)





# Installation


Pour installer le projet, vous devez d'abord cloner le depot git dans un dossier de votre choix:
Ensuite, il faut se placer dans le bon repertoire et ouvrir deux terminals: vous pouvez pour cela directement ouvrir les terminals dans le dossier restaurant depuis l'explorateur de fichiers ou taper la commande suivante depuis la source du dossier:
```shell
cd restaurant
```

vous devez donc vous trouver dans le repertoire "~/resto-poussier/restaurant"

Copiez le contenu du fichier .env.example dans un fichier .env à la racine du projet laravel (restaurant/.env)

il faut ensuite lancer les commandes suivantes pour installer les librairies necessaires:
```shell
composer install
npm install
```

Il faut egallement creer un fichier .env a la racine du projet (.../restaurant/.env) et y copier le contenu du fichier .env.example. Ensuite, vous devez modifier les parametres de connexion a la base de donnée (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) pour qu'ils correspondent a votre configuration locale.

Ensuite, vous devez creer la base de donnée et lancer les migrations et les seeders avec la commande suivante:
```shell
php artisan migrate:fresh --seed
```

ensuite, il faut generer le lien depuis le dossier public vers le storage pour les images en tapant cette commande:
```shell
php artisan storage:link
```

Enfin, il faut construire la partie front-end avec la commande suivante:
```shell
npm run dev
```

Une fois le projet pret, vous pouvez lancer le serveur local avec la commande suivante dans un second terminal (meme dossier resto-poussier/restaurant):
```shell
php artisan serve
```
Ensuite, vous pouvez vous rendre sur l'url http://127.0.0.1:8000/ pour acceder au site (l'url devrai correspondre à l'url que renvoi la commande serve, sinon, preferez celle renvoyée par la commande)


# Utilisation

la nav-bar en haut du site permet la navigation, selon si l'utilisateur est connecté et selon son role, les liens et l'affichage des pages disponibles sont modifiés.

pour ajouter un commentaire à un restaurant, l'utilisateur doit etre connecté et avoir le role 'user', il faut ensuite se rendre sur une page de restaurant et descendre jusqu'au formulaire de commentaire.

pour créer un contact support, l'utilisateur doit soit etre un 'user' soit ne pas etre connecté, puis se rendre sur la page "nous contacter" grace à la nav-bar (un utilisateur connecté aura deja son nom et son email de rempli)

pour créer un restaurant l'utilisateur doit etre de type 'user' et se rendre dans son tableau de bord -> créer un restaurant

un utilisateur peut editer / supprimer ses restaurants depuis la page "voir tous mes restaurants" disponible dans le tableau de bord (uniquement s'il en a)
l'icone editer dans la derniere colone envoi sur un formulaire d'edition du restaurant concerné (c'est le seul formulaire d'edition qui n'est pas en ajax car il est trop consequent)

l'utilisateur peut egalement modifier le contenu de ses contact-support / commentaires depuis le tableau de bord avec les liens "voir tous mes ..." (en ajax cette fois)

L'admin peut rendre actif/ inactif les tags, foodTypes et restaurants depuis son tableau de bord (en ajax) en cliquant sur le bouton approprié
Si des restaurants / tags / foodtypes sont inactif, l'information est affichée sur le tableau de bord

l'admin peut egallement supprimer les tags et foodTypes

Il peut enfin changer le role d'un utilisateur egalement depuis le crud disponible à partir du tableau de bord.



# Choix personnels

- j'ai choisi de mettre deux niveaux d'authentification (auth et admin) ou admin serait un adminisatreur ou le possesseur du site, auth les users
- j'ai choisi de mettre les middelware dans les controllers pour regrouper les routes par fonction et non par niveau d'authentification (plus de clareté à mon sens)
- Les tags et les food_types sont gérés sous forme de models avec leur table dans la base de donnée. et sont liés par une relation many to many au model restaurant (ce qui a necessité l'ajout de 2 tables de liaison: tag_restaurant et food_type_restaurant)
- Les Seeders permettent tous de generer une quantité voulue de donnée grace aux factories, quelque soit le seeder, s'il est utilisé seul, il va generer les données (meme dans d'autres tables) dont il a besoin. (les seeders de Tag et FoodType generent d'abord des instances predefinies puis basculent sur de l'aleatoire)
- les restaurants ont une image dont le chemin/nom de fichier est stocké dans la base de donnée puis recuperée lors de l'affichage dans le systeme de fichier. J'ai pour cela utilisé la facade Storage avec le driver local (qui stocke les fichiers dans le repertoire storage/app/). Je n'ai pas compris pourquoi mais je n'ai pas réussi à supprimer des images une fois qu'elles sont stockées, la fonction Storage::delete() est pourtant dans le code mais elle n'aboutit pas.
- J'ai créé un systeme de popup pour faire des retours à l'utilisateur sur ses actions mais je me suis rendu compte plus tard que pour un vrai site, ce ne serait pas très propre, il aurai mieu fallut les gerer en javascript alors qu'ils sont actuellement generés via livewire. J'ai neanmoins gardé ce systeme qui fonctionne.
- j'ai créé une classe pour les imports d'images à partir d'une ancienne classe (php uniquement) que j'ai du quasiment integralement reprendre car elle ne fonctionnait pas bien et qu'elle n'etait pas bien adaptée à laravel.
- j'ai perseveré à créer un crud entierement generique dans un composant livewire, qui permet que si l'application s'etend, il n'y aurai normalement plus a faire de crud mais seulement une ou deux fonctions dans un controlleur. Le composant permet, à partir des tableaux renvoyés par les methodes getCrud() des controllers de recuperer toutes les données de la BDD (la table est recuperée directement dans le composant -> $modelName) et de permetre l'edition (ajax avec la fonction editForm du controller ou redirect vers la fonction edit) la suppression et le tri suivant les valeurs du tableau. Cette partie m'a pris enormement de temps (sur la fin de projet qui plus est) et m'a retardé pour les fonctionnalités secondaires que je voulais integrer mais j'ai appris beaucoup et c'est du code que je pourrait certainement réutiliser sur d'autre projet (bien que le format des array qui transitent serait peut etre à retravailler)
- j'ai essayé de rendre le site au maximum responsive avec tailwind (la nav bar etant deja gerée par jetStream) mais je ne me suis pas penché sur le responsive du crud generique (qui est assez complexe et qui aurait été compliqué a gerer sans javascript)
- j'ai fait du javascript uniquement pour le carousel de la page d'acceuil et ai essayé de tout faire avec laravel. Ce n'est pas forcement ce que j'aurai fait sur un autre projet mais je voulais monter le plus possible en competence sur ce framework. Il est possible que mon utilisation de livewire soitt un peu forcée par moment alors qu'AlpineJS se marie très bien avec et m'aurait permis de faire parfois les meme choses (je n'ai pas eu le temps de m'y pencher)
- le systeme de controllers est réparti en 3 dossiers, AdminControllers, AuthControllers et la racine (qui contient les controllers de la partie publique du site)
les deux controllers reservés aux users utilisent le middleware web qui est hydraté par les fonctionnalités de JetStream et Fortify
- j'ai adapté le tableau de bord de livewire en y mettant des composant blade (admin-dashboard et user-dashboard) en fonction du role.
- J'ai créé un composant blade pour les icones et un autre pour la banner des pages publiques.




# Outils externes, tutoriels

## Ressources diverses
- Pour la classe Core\Image qui me permet de gerer les imports d'image et de créer des thumbnails, c'est une classe que j'ai créée il y a un an donc je ne me rappelle plus exactement ou j'ai pioché toutes les informations pour la construire mais: je me suis aidé d'un TP de Quentin Roy (introduction aux technologies du web L3 MIASHS) qui fournissait un code basique sur la création de thumbnails, je me suis egalement aidé d'une ressource un peu plus complexe sur internet qui gerait les images sous forme d'objet avec des verifications de tpes et une legere gestion des erreurs et j'ai transformé ces connaissances en créant ma propre classe. Voici le commentaire original du script que j'ai importé:
```php
/* Script realise par Emacs
 * Crée le 19/12/2004
 * Maj : 23/06/2008
 * Licence GNU / GPL
 * webmaster@apprendre-php.com
 * http://www.apprendre-php.com
 * http://www.hugohamon.com
 */
```
J'ai ensuite modifié cette classe importée pour qu'elle corresponde au projet

- J'ai telecharger des icones depuis la librairie gratuite [Feather](https://feathericons.com/) qui se trouvent dans le dossier public/images/icons pour que ce soit un peu plus joli.

## Tutoriels

### Tutoriels laravel
- J'ai visionné certains tutoriels de [Grafikart](https://grafikart.fr/) sur laravel en commencant à reproduire ce qu'il faisait dans un autre projet mais, une partie etant depreciée (tutoriel sur laravel 5), j'ai vite abandonné la reproduction de son tutoriel pour piocher des astuces / possibilités qui me semblaient utile. Je regardais ensuite la documentation actuelle pour voir comment laravel proposait actuellement de construire ces elements. J'ai notament procedé de cette facon pour la partie authentification pour laquelle j'ai du tatonner.

-J'ai suivi le [tutoriel de Grafikart sur livewire](https://grafikart.fr/tutoriels/livewire-laravel-1923) qui m'a inspiré l'idée du crud generique. 


### Stack overflow
- J'ai été confronté à un probleme quand j'ai voulu configurer le seeding pour les tables de liaisons entre restaurants et tags / foodtypes:
je vais continuer en parlant de la table tag_restaurant, la resolution est identique pour food_type_restaurant:
J'ai du obliger l'unicité des combinaisons restaurant_id et tag_id pour eviter que le seeder ne place plusieur fois la meme association (c'est a dire qu'il pouvait donner plusieurs fois le meme tag à un meme restaurant). Le probleme a été de respecter cette contrainte dans la factory, en effet, je me suis rendu compte que la DB n'est pas mise à jour à chaque iteration de la factory si on utilise count() (cf TagRestaurantSeeder), donc je ne pouvais pas verifier les données precedemment créees (aleatoirement) lors du seed, seulement d'un seed à l'autre. Pour garder un fonctionnement aleatoire (rester generique), j'ai du utiliser un attribut d'instance statique que je ne pouvais pas instancier dans le constructeur pour une raison que j'ignore (si le constructeur est rempli, la fonction count() n'agit plus). Je l'ai donc instancié dans la methode definition de la factory. J'ai trouvé cette idée sur [Stack Overflow](https://stackoverflow.com/a/48129745/19847322) mais je l'ai amelioré car le contexte du salon du forum etait legerement different, et je trouvait sa syntaxe peu claire. J'ai egalement du utiliser le systeme de Collection car les array posaient probleme dans mon cas (pas de possibilité de doublons pour les keys ce qui pose probleme dans le many to many)

---------------------------------------------------------------------
## Outils

### Github Copilot
Pour ce devoir, j'ai utilisé github copilot car c'est un outil (gratuit pour les etudiants) qui d'après les medias que je visionne (twitter, youtube...) est largement encouragé par les developpeurs. Je l'avais ajouté à mon editeur peu de temps après le debut du projet (plus par coincidence que par correlation) et ai donc saisi l'oportunité de ce gros projet pour l'essayer. 

Il y a plusieurs facon de le manipuler mais j'ai seulement utilisé les suggestion dynamiques (texte en gris sur l'editeur, "tab" pour accepter la suggestion). Je sais qu'il existe un raccourci "ctrl+enter" qui permet de proposer plusieurs suggestions pour le fichier entier mais ce raccourci entrait en conflit avec le raccourci natif VS-Code qui me permet d'ajouter une ligne en dessous (raccourci dont je me sert quasiment tout le temps) et j'ai preferé ecraser manuellement le raccourci github plutot que changer mon raccourci habituel (pour une fonctionnalité qui ne me parraissait pas necessaire pour l'instant: trop d'information, trop global pour debuter un language et l'outil).

 **Les inconvenients de Copilot sont:**
 - il utilise parfois des syntaxes depreciées (anciennes version de laravel) ou plus alambiquées -> en regardant la documentation on peut eviter ce probleme, ce que j'ai fait de toute facon lorsque je me trouvait devant une syntaxe ou un appel inconnu

 - parfois il propose plusieurs fois la meme chose ou une suggestion qui ne nous interresse pas: environ un tier des suggstions ne sont pas satisfaisante et donc perdent du temps à analyser -> en commencant à ecrire le corps des fonctions cette proportion baisse enormement car il voit facilement ce qu'on souhaite faire une fois la definition et les premieres lignes de la methode ecrite

 **J'ai continué à utiliser copilot après quelques jours d'essai pour 3 raisons:** 
 - premierement ca me gagne enormement de temps sur les choses que je maitrise: par exemple la generation de route, les getters, les setters, toutes les taches repetitives et basiques me prennent une dixaine de seconde à verifier au lieu d'une minute à ecrire. C'est egallement un gain de temps pour les methodes plus longues que je commence à ecrire car, sachant déjà comment le faire, je n'ai qu'a verifier que ce qu'il me propose pour la suite correspond à ce que j'allai faire. Si ce n'est pas le cas, je n'ai qu'a continuer à ecrire.

 - deuxiemement bien utilisé il a un apport educatif. Copilot se base si j'ai bien compris sur les repo public de Git-hub, et propose des suggestions probables en fonction du contexte proche (commentaire du dessus, fonctions autour, classe) et eloigné (projet local, mais ca j'ai parfois des doutes). Il m'a donc parfois donné des suggestions auquelles je n'aurai pas pensé tout seul mais qui m'ont appris beaucoup en me renseignant dans la documentation. 

 - cet outil (ou un autre du style) sera indispensable dans ma vie professionnelle, il l'est déjà pour ceux du metier d'après ce que j'ai vu sur internet, et plus vite je le prendrait en main, plus vite je pourrait exploiter ses avantages et comprendre ses limites.

**En conclusion:**

 Github copilot pourrait, vu de l'exterieur, ressembler à du copier coller / du plagiat ou une maniere de tricher, mais à mon sens, si je n'avait pas compris ce qu'il proposait et utilisait ses suggestions à tort et à travers, j'aurrai pu passer une semaine à resoudre les erreurs et me serrait retrouvé avec une application totalement differente que ce que vous demandez ou une application qui ne fonctionne pas. Je ne veux pas que vous pensiez que la quantitée de travail fournie est entierement due à l'outil, j'ai réellement passé enormement de temps sur ce devoir car j'ai besoin de ces connaissances pour mon avenir professionnel et je ne peut me permettre de perdre un temps en copier coller maintenant que je devrai rattraper dans quelques mois de toute facon car je dois faire un site en laravel d'ici cet été.

**Dans la section suivante je detaille les suggestions de Copilot que je n'aurai pas (ou pas aussi vite) decouvert sans Github Copilot:**

- copilot ajoute "{!! csrf_token() !!}" dans le form de la view auth.login, ne comprennant pas à quoi ca servait j'ai regardé la documentation [lien vers la doc](https://laravel.com/docs/10.x/csrf) j'ai compris que ca protegeait les requetes [post, put, patch et delete] d'une usurpation, j'ai donc preferé la syntaxe propre à blade "@csrf" de la doc qui fait automatiquement le champ hidden, le middelware web se charge (avec le middleware VerifyCsrfToken) de faire la verification de session
- copilot m'a proposé la fonction pluck() (qui renvoi un tableau d'ensemble clé => valeur) que je ne connaissait pas mais qui ne m'interressait pas pour le cas present au final car il ne peut conserver de doublons pour les clés. Je l'ai utilisé plus tard dans le projet lorsue le cas s'y pretait
- propose @livewire('component') pour les composants au lieu de <livewire:composant > (syntaxe du tuto que j'avais vu mais qui ne semblait pas marcher), apres un tour sur la doc j'ai decidé d'utiliser cette syntaxe qui semble plus simple et plus claire une fois qu'on a compris le principe
- il m'a proposé with() pour les redirect mais je ne comprennais pas au debut que les variables etaient dans la session et comment les recuperer, je ne l'ai donc pas utilisé jusqu'a ce que je soit face a un probleme avec mon systeme de popup et, une fois avoir navigué entre la documentation et stack-overflow j'ai finit par comprendre comment ca fonctionnait et que ca simplifiait enormement les bidouillages que je faisait pour reproduire le systeme.