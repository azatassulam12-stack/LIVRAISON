# Contrat API initial

Préfixe : `/api/v1`. Les routes privées utilisent Laravel Sanctum (`Authorization: Bearer <token>`).

| Méthode | Route | Accès | Usage |
| --- | --- | --- | --- |
| POST | `/auth/register-store` | public | Crée une boutique et son administrateur |
| POST | `/auth/login` | public | Connexion boutique/livreur |
| GET | `/auth/me` | connecté | Identité courante |
| GET/POST | `/deliveries` | boutique | Liste/création des commandes de sa boutique |
| PATCH | `/deliveries/{delivery}` | boutique | Modification d’une commande non terminée |
| POST | `/deliveries/{delivery}/assign` | boutique | Attribution à un livreur de la même boutique |
| POST | `/deliveries/{delivery}/status` | livreur attribué/boutique | Changement de statut documenté |
| GET | `/tracking/{token}` | public | Suivi minimal sécurisé |
| POST | `/tracking/{token}/location` | public | Correction GPS ou repère client |

Les réponses d’erreur utilisent le format Laravel standard (`message`, `errors`).
