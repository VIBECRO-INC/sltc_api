# SLTC API — Checklist avant production

## Application

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` générée
- [ ] HTTPS activé
- [ ] `APP_URL` correct
- [ ] `FRONTEND_URLS` limité aux domaines réels

## Base de données

- [ ] MySQL/MariaDB configuré
- [ ] migrations exécutées avec `--force`
- [ ] compte admin production créé
- [ ] mot de passe admin de développement supprimé/changé

## Sanctum

- [ ] tokens fonctionnels
- [ ] Bearer token transmis par le frontend
- [ ] logout testé
- [ ] expiration/revocation selon politique retenue

## Sécurité

- [ ] rate limiting actif sur login/contact/devis
- [ ] validation serveur vérifiée
- [ ] uploads contrôlés avant activation d’un endpoint upload
- [ ] logs surveillés
- [ ] sauvegardes configurées
- [ ] permissions admin validées

## Frontend

- [ ] `VITE_API_URL` pointe vers l’API production
- [ ] CORS autorise uniquement le frontend réel
- [ ] erreurs 401/403/422/429/500 testées
- [ ] images production testées
- [ ] formulaires devis/contact testés

## SEO / contenu

- [ ] pages publiées
- [ ] SEO rempli
- [ ] images/ALT fournis
- [ ] contenus validés par SLTC

## Tests d’acceptation

- [ ] home
- [ ] services
- [ ] flotte
- [ ] réalisations
- [ ] équipe
- [ ] références
- [ ] produits
- [ ] actualités
- [ ] galerie
- [ ] témoignages
- [ ] devis
- [ ] contact
- [ ] login admin
- [ ] dashboard
- [ ] CRUD admin
- [ ] pipeline devis
- [ ] messages
- [ ] SEO
- [ ] settings
