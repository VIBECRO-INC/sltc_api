# Contrat API — SLTC

Toutes les réponses sont JSON.

## Pagination

Les listes paginées Laravel retournent `data`, `current_page`, `last_page`, `per_page`, `total`, etc.

## Quote request

`POST /api/v1/quotes`

Exemple :
```json
{
  "need_type": "Engin",
  "description": "Besoin d'une pelle hydraulique pour un chantier.",
  "project_location": "Cotonou",
  "needed_at": "2026-09-15",
  "first_name": "Jean",
  "last_name": "Dupont",
  "company": "Entreprise ABC",
  "email": "jean@example.com",
  "phone": "+22900000000",
  "whatsapp": "+22900000000",
  "consent": true
}
```

## Contact

`POST /api/v1/contact`

```json
{
  "name": "Jean Dupont",
  "company": "Entreprise ABC",
  "email": "jean@example.com",
  "phone": "+22900000000",
  "subject": "Demande d'information",
  "message": "Bonjour..."
}
```
