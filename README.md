# NEX-GEN — LIVRAISON

Plateforme de livraison pensée pour les boutiques, commerçants et livreurs en Côte d’Ivoire. Le client final ne télécharge aucune application : il consulte un suivi sécurisé depuis son téléphone.

## MVP en cours

- Comptes Boutique, Livreur et Administrateur plateforme
- Création, programmation et attribution de commandes
- Statuts rapides pour les livreurs et bilan journalier
- Position GPS, repères textuels et ouverture de navigation
- Suivi client public via un token révocable et non devinable
- Confirmation/correction de position par le client

## Architecture

| Élément | Choix | Rôle |
| --- | --- | --- |
| `backend/` | Laravel 12, PHP 8.2+ | API REST, interface boutique, sécurité et données |
| `mobile/` | Flutter (prochaine phase) | Application Android/iOS pour livreurs |
| Base de données | MySQL 8+ ou PostgreSQL 15+ | Données relationnelles et historiques |
| Hébergement initial | VPS classique | Monolithe simple à administrer |

Le projet reste volontairement monolithique : aucune infrastructure de microservices n’est requise pour le MVP.

## Démarrage du backend

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan test
php artisan serve
```

Configurez d’abord la base de données dans `.env`. Ne versionnez jamais ce fichier : seul `.env.example` est fourni.

## Documentation

- [Architecture et modèle de données](docs/architecture.md)
- [Contrat API initial](docs/api.md)

---

Projet développé sous la structure **NEX-GEN**.
