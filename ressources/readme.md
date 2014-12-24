#AuctionNow

_AuctionNow_ est une platforme web permettant l'utilisation de l'Hôtel des ventes d'un serveur TrinityCore facilement.

*_AuctionNow_ est développé avec CodeIgniter 3.*

##Principe de fonctionnement

Le joueur se connecte à la platforme avec les mêmes identifiants que sur le serveur de jeu (_AuctionNow_ accède à la même base de données). Une fois le joueur connecté, il choisit parmi la liste de ses personnages lequel interagit avec l'hôtel des ventes (c'est sur ce personnage que sera récupérer l'argent et sur celui-ci que sera envoyé l'item s'il est gagné).

Le joueur connecté accède à la liste des offres actuellement en cours sur le serveur. Cette liste est récupérée via SQL (pour éviter tout conflit avec un thread du core, seul des SELECTs sont éxecutés sur les tables MySQL de TrinityCore). Le joueur a accès aux différents filtres disponibles en jeu pour affiner ses recherches. 

Une fois que le joueur a trouvé une enchère qui l'intéresse, il accède à la page de l'enchère lui permettant d'enchérir ou de faire un "Achat immédiat". Après confirmation, _AuctionNow_ envoie via SOAP (pour éviter tout conflit) une commande custom au serveur qui comprendra en paramètre le GUID de l'enchère modifié, le GUID du personnage, l'offre. Le serveur via la commande reçu route les paramètres vers le système habituel.

##Mise en place de _AuctionNow_
###Concernant le core

####Activer SOAP
Dans *worldserver.conf*, remplacez le _0 de SOAP.Enabled par 1_, si vous pouvez définir un autre port pour plus de sécurité. Enfin, n'oubliez pas d'ouvrir iptables et de sauver la configuration iptables.

####Ajouter la commande
- Ajoutez le fichier de la commande auctionhouse_cli dans votre dossier *Script/Custom*
- Ajoutez le fichier dans le *CMakeFile.txt* associé
- *Regénérez* le fichier de solution avec CMake
- Ajoutez la ligne nécessaire dans le *ScriptLoader.cpp*
- Dans le fichier *Language.h*, ajoutez à l'enum les constantes _LANG_AUCTIONNOW_SUCESS_, _LANG_AUCTIONNOW_NO_AUCTION_, _LANG_AUCTIONNOW_MISS_MONEY_, _LANG_AUCTIONNOW_BIDDER_IS_OWNER_, _LANG_AUCTIONNOW_BAD_ARGUMENT_.
- Créez les *trinity_strings* associées dans la base de données world avec la requête suivante : _INSERT INTO trinity_string (entry, content_default) VALUES (ID_DE_LANG_AUCTIONNOW_NO_AUCTION, "No auction"), (ID_DE_LANG_AUCTIONNOW_SUCESS, "Sucess"), (ID_DE_LANG_AUCTIONNOW_BIDDER_IS_OWNER, "Bidder is owner"), (ID_DE_LANG_AUCTIONNOW_MISS_MONEY, "Miss money"), (ID_DE_LANG_AUCTIONNOW_BAD_ARGUMENT, "Bad argument");_ N'ajoutez surtout pas de locales à cette trinity_string (le script PHP analyse le retour uniquement en fonction de ces strings, toute tentative d'internationalisation l'empechera de comprendre la réponse du serveur.)
- Ajoutez dans *RBAC.h* la valeur _RBAC_PERM_COMMAND_AUCTIONNOW_ à l'enum et notez sa valeur
- Exécutez sur la base *auth* _INSERT INTO rbac_permissions VALUES (ID_DANS_L_ENUM, "Command: auctionnow_cli");_ (sans oublier de reporter l'id du RBAC dans la commande)
- *Ajoutez la permission* d'exécuter la commande au compte SOAP : _INSERT INTO rbac_account_permissions VALUES (ACCOUNT_ID, ID_DANS_L_ENUM, 1, -1);_

###Concernant le PHP
####Activer SOAP
Activer l'extension SOAP dans le fichier .ini de PHP. Si le paquet n'existe pas, ajoutez le (sous debian : aptitude install php-soap).

####Installation du site
Le site est basé sur le framework CodeIgniter v3.0.
#####Sur une installation CodeIgniter existante

#####Sur une installation non CodeIgniter
