# NEX-GEN — LIVRAISON

Plateforme de gestion de livraisons pensée pour les boutiques, commerçants et livreurs en Côte d’Ivoire.

## Objectif

Simplifier la création, l’attribution et le suivi des livraisons, depuis la boutique jusqu’à la confirmation de réception par le client. Le client final n’a pas besoin d’installer une application : il suit sa livraison au moyen d’un lien sécurisé.

## MVP

- Création et programmation des livraisons par les boutiques
- Attribution des courses aux livreurs
- Application mobile dédiée aux livreurs
- Gestion de tournées et mise à jour des statuts
- Suivi client via un lien web sécurisé
- Confirmation de livraison avec position GPS
- Bilan quotidien des livraisons

Cycle initial d’une livraison : `Demandée → Acceptée → Colis récupéré → En cours de livraison → Livrée`.

## Architecture prévue

- **Backend et interface boutique :** Laravel (monolithe)
- **Application livreur :** Flutter
- **API :** REST
- **Base de données :** MySQL ou PostgreSQL

Le projet privilégie une architecture simple, professionnelle et maintenable par un développeur seul, avec l’objectif de devenir une solution SaaS commercialisable.

---

Projet développé sous la structure **NEX-GEN**.
