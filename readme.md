#AuctionNow

AuctionNow est une platforme web permettant l'utilisation de l'Hôtel des ventes d'un serveur TrinityCore facilement.

AuctionNow est développé avec CodeIgniter 3.

##Principe de fonctionnement

Le joueur se connecte à la platforme avec les mêmes identifiants que sur le serveur de jeu (AuctionNow accède à la même base de données). Une fois le joueur connecté, il choisit parmi la liste de ses personnages lequel interagit avec l'hôtel des ventes (c'est sur ce personnage que sera récupérer l'argent et sur celui-ci que sera envoyé l'item s'il est gagné).

Le joueur connecté accède à la liste des offres actuellement en cours sur le serveur. Cette liste est récupérée via SQL (pour éviter tout conflit avec un thread du core, seul des SELECTs sont éxecutés sur les tables MySQL de TrinityCore). Le joueur a accès aux différents filtres disponibles en jeu pour affiner ses recherches. 

Une fois que le joueur a trouvé une enchère qui l'intéresse, il accède à la page de l'enchère lui permettant d'enchérir ou de faire un "Achat immédiat". Après confirmation, AuctionNow envoie via SOAP (pour éviter tout conflit) une commande custom au serveur qui comprendra en paramètre le GUID de l'enchère modifié, le GUID du personnage, l'offre. Le serveur via la commande reçu route les paramètres vers le système habituel.

##Configuration
En cours de réalisation...