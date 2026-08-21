# SLTC INTER SARL — API Laravel 12

Cette archive contient la base complète de l'API REST destinée au site corporate B2B de SLTC INTER SARL.

## Documents importants

- `OPENCODE_FRONTEND_INTEGRATION.md` : contrat à donner à OpenCode pour connecter le frontend.
- `API_CONTRACT.md` : résumé technique des endpoints.
- `PRODUCTION_CHECKLIST.md` : contrôles obligatoires avant mise en production.

## Installation

Prérequis :

- PHP 8.2+
- Composer
- MySQL/MariaDB
- extensions PHP nécessaires à Laravel

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Configurer `.env`, puis :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Base API :

```text
http://127.0.0.1:8000/api/v1
```

## Compte de développement

```text
email: admin@sltc-inter.bj
password: ChangeMe_123!
```

Ce compte est uniquement destiné au développement. Le mot de passe doit être changé avant production.

## V1 couverte

- authentification admin Sanctum
- dashboard
- services
- flotte
- réalisations
- équipe
- références
- produits
- actualités
- témoignages
- galerie
- pages
- demandes de devis
- pipeline des leads
- messages
- SEO
- paramètres

## Important

Cette archive fournit le code source et le contrat d'intégration. Une API ne peut pas être déclarée "production-ready" indépendamment de son environnement : il faut encore configurer le serveur, la base, le domaine, HTTPS, SMTP, CORS, les sauvegardes et les données réelles de SLTC, puis exécuter la recette.

Les fonctionnalités V2/V3/V4 du cahier des charges (CRM complet, réservations, contrats, factures, géolocalisation, application mobile, API partenaires) restent volontairement hors V1.
