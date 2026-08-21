# Installation — API Admin SLTC

## 1. Créer le projet Laravel 12

```bash
composer create-project laravel/laravel sltc-api
cd sltc-api
composer require laravel/sanctum
php artisan install:api   # ou : publier config sanctum + migration manuellement
```

## 2. Copier les fichiers fournis

Copie le contenu de ce dossier dans ton projet, en respectant l'arborescence :

```
app/Models/...              -> app/Models/
app/Http/Controllers/...    -> app/Http/Controllers/
app/Http/Middleware/...     -> app/Http/Middleware/
app/Traits/...              -> app/Traits/
app/Http/Resources/...      -> app/Http/Resources/
database/migrations/...     -> database/migrations/
database/seeders/...        -> database/seeders/
routes/api.php              -> routes/api.php (fusionner si déjà existant)
```

## 3. Configurer `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sltc_api
DB_USERNAME=root
DB_PASSWORD=

FRONTEND_URL=http://localhost:3000
SANCTUM_STATEFUL_DOMAINS=localhost:3000

FILESYSTEM_DISK=public
```

## 4. Lancer les migrations + lien de stockage

```bash
php artisan migrate
php artisan storage:link
php artisan db:seed --class=AdminUserSeeder
```

## 5. Démarrer

```bash
php artisan serve
```

L'API est disponible sur `http://127.0.0.1:8000/api/admin/...`

## 6. Tester l'authentification

```bash
curl -X POST http://127.0.0.1:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@sltc-inter.bj","password":"CHANGE_ME"}'
```

La réponse contient un `token` à utiliser en header sur toutes les routes admin :
```
Authorization: Bearer {token}
Accept: application/json
```
