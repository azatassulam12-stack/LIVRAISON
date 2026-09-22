# Architecture — Phase 1

## Décision

Le MVP est un monolithe Laravel. Il expose une API REST consommée par la future application Flutter livreur et sert ensuite l’interface web de la boutique. Cette décision limite les coûts, les déploiements et la maintenance pour un développeur seul.

## Isolation des données

Chaque commande appartient à une `store` (boutique). Toutes les requêtes authentifiées de la boutique et du livreur sont filtrées par `store_id`. Un livreur n’accède qu’aux commandes qui lui sont attribuées.

## Modèle de données initial

| Table | Finalité |
| --- | --- |
| `stores` | Entreprises clientes de la plateforme |
| `users` | Administrateur plateforme, administrateur boutique et livreur |
| `deliveries` | Commande/livraison, adresse, montant, statut et token de suivi |
| `delivery_status_histories` | Journal immuable des changements de statut |
| `driver_locations` | Positions GPS échantillonnées pendant une tournée |

`deliveries.tracking_token` est un UUID aléatoire. Il ne contient pas l’identifiant de la commande. Le lien public est désactivable et peut expirer.

## Statuts MVP

`pending`, `collected`, `waiting`, `on_tour`, `en_route`, `arrived_in_area`, `delivered`, `customer_absent`, `customer_unreachable`, `address_not_found`, `refused`, `rescheduled`, `cancelled`.

Les statuts de succès et d’échec sont conservés dans l’historique. La V1 ne met pas en place d’optimisation automatique de tournée ; elle doit d’abord fiabiliser l’adresse, l’attribution et la preuve de résultat.

## Vie privée GPS

Les positions du livreur sont reçues uniquement pendant une tournée active. La page publique ne doit afficher une position précise que pour une commande `en_route` et seulement si la boutique l’active ultérieurement. L’API initiale n’expose pas les coordonnées de tournée au public.
