# PROMPT DE CONTINUATION — API Admin SLTC INTER SARL (Laravel 12)

Colle ce document tel quel à OpenCode (ou à Claude Code) comme instruction de départ.
Il décrit exactement ce qui est déjà fait, ce qu'il reste à faire, et les conventions
strictes à respecter pour que le résultat reste cohérent avec le travail déjà réalisé.

---

## 0. CONTEXTE

Projet : API back-office (admin) pour le site corporate de **SLTC INTER SARL**
(location d'engins BTP, transport/logistique, terrassement, levage, commerce
d'équipements de sécurité), basé sur le cahier des charges fourni par le client.

Stack imposée :
- **Laravel 12**
- **Laravel Sanctum** pour l'authentification API (tokens, pas de sessions cookie SPA)
- **MySQL / MariaDB**
- Stockage fichiers : disque `public` (`storage/app/public`, lié via `php artisan storage:link`)
- Réponses API : JSON uniquement, format standardisé (voir section 4)

Un lot de fichiers a déjà été écrit à la main (pas encore testés car `composer`/`artisan`
ne sont pas disponibles dans l'environnement où ils ont été rédigés). **Ta première tâche
est de les intégrer dans un vrai projet Laravel 12 et de les faire fonctionner**, puis de
compléter ce qui manque en suivant strictement les mêmes conventions.

---

## 1. CE QUI EST DÉJÀ FAIT (fourni dans l'archive `sltc-api-backend.zip`)

### 1.1 Migrations — 100% terminées (23 fichiers dans `database/migrations/`)

Toutes les tables du cahier des charges (section 42 "Administration") sont déjà migrées :

| Table | Contenu |
|---|---|
| `users` (altération) | + role, phone, avatar, is_active |
| `pages` | contenu flexible JSON (hero, promesse, cta...) par clé (`home`, `about`...) |
| `seo_settings` | title/meta_description/og_image/canonical/structured_data par `page_key` |
| `admin_notifications` | notifications dashboard (nouvelle demande, nouveau message) |
| `services` | Nos 5 expertises |
| `equipment_categories`, `equipments`, `equipment_images` | Flotte |
| `product_categories`, `products` | Commerce & sécurité |
| `realisations`, `realisation_equipment` (pivot), `realisation_images` | Réalisations |
| `team_members` | Équipe |
| `references` | Partenaires/clients (logos) |
| `news_categories`, `news`, `news_images` | Actualités |
| `testimonials` | Témoignages clients |
| `gallery_items` | Galerie chantiers (enum category: engins/transport/travaux/equipe/chantiers/materiel) |
| `quote_requests`, `quote_request_notes` | Demandes de devis + pipeline commercial |
| `contact_messages` | Formulaire de contact général |

**Ne modifie pas le schéma sans raison. Si un champ manque, ajoute une migration
additive plutôt que d'éditer les fichiers existants (ils seront peut-être déjà
exécutés en base au moment où tu interviens).**

### 1.2 Models — 10 sur 23 déjà créés (`app/Models/`)

Faits : `Page`, `SeoSetting`, `AdminNotification`, `Service`, `EquipmentCategory`,
`Equipment`, `EquipmentImage`, `ProductCategory`, `Product`.

Convention de slug automatique : trait `App\Models\Concerns\HasSlug` (déjà écrit).
Usage :
```php
use App\Models\Concerns\HasSlug;

class MonModel extends Model
{
    use HasSlug;
    protected string $slugSource = 'title'; // ou 'name', selon le modèle — sinon défaut 'name'
    // ...
    public function getRouteKeyName(): string { return 'slug'; }
}
```
Le trait génère un slug unique automatiquement à la création si `slug` est vide.
Les modèles avec route binding par slug (Service, EquipmentCategory, Equipment,
ProductCategory, Product) définissent `getRouteKeyName()` → `'slug'`. Fais pareil
pour tous les modèles publics avec URL (Realisation, TeamMember n'a PAS besoin de
slug — utilise l'id —, News, GalleryItem n'a pas besoin de slug non plus).

**Modèles restants à créer** (mêmes conventions que ceux déjà faits) :

- `Realisation` (fillable: service_id, title, slug, client, sector, location, prestation,
  year, description, results, video_url, cover_image, is_featured, is_published, order —
  cast is_featured/is_published bool — relations: `service()` belongsTo Service,
  `equipments()` belongsToMany via `realisation_equipment`, `images()` hasMany
  RealisationImage orderBy order, `references()` hasMany Reference — HasSlug slugSource='title')
- `RealisationImage` (fillable: realisation_id, path, order — belongsTo Realisation)
- `TeamMember` (fillable: first_name, last_name, function, department, bio, photo,
  expertise, years_experience, linkedin_url, is_published, order — accessor
  `full_name` optionnel)
- `Reference` (fillable: name, logo, website_url, realisation_id, is_active, order —
  belongsTo Realisation nullable)
- `NewsCategory` (fillable: name, slug, order — hasMany News — HasSlug)
- `News` (fillable: news_category_id, author_id, title, slug, excerpt, content, image,
  reading_time, is_published, published_at — cast is_published bool, published_at
  datetime — belongsTo NewsCategory, belongsTo User as `author`, hasMany NewsImage —
  scope `published()` : `where('is_published', true)->whereNotNull('published_at')` —
  HasSlug slugSource='title')
- `NewsImage` (fillable: news_id, path, order)
- `Testimonial` (fillable: name, function, company, content, photo, rating,
  is_published, order)
- `GalleryItem` (fillable: title, image, category, is_published, order)
- `QuoteRequest` (fillable: need_type, description, location, needed_at, full_name,
  email, phone, company, status, assigned_to, source — cast needed_at date —
  hasMany QuoteRequestNote, belongsTo User as `assignee` via assigned_to — constants
  pour le pipeline : `const STATUSES = ['nouveau','qualification','devis','negociation','gagne','perdu'];`)
- `QuoteRequestNote` (fillable: quote_request_id, user_id, note — belongsTo QuoteRequest, belongsTo User)
- `ContactMessage` (fillable: full_name, email, phone, subject, message, is_read — cast is_read bool)
- Mettre à jour `User` (déjà généré par Laravel par défaut) : ajouter au fillable
  `role`, `phone`, `avatar`, `is_active` ; ajouter `use Laravel\Sanctum\HasApiTokens;`
  au trait use ; ajouter des accessors `isSuperAdmin()`/`isAdmin()` si utile ; caster
  `is_active` en bool.

### 1.3 Ce qui N'EST PAS encore fait — À TOI DE JOUER

1. **Installation Laravel 12 propre** + Sanctum (`composer require laravel/sanctum`,
   publier la config, migration Sanctum).
2. **Copier les fichiers fournis** dans le nouveau projet (mêmes chemins exacts :
   `app/Models/...`, `database/migrations/...`).
3. **Terminer les 13 modèles restants** listés en 1.2.
4. **Trait `App\Traits\ApiResponser`** — réponses JSON standardisées (voir section 4
   pour le format exact attendu).
5. **Controller de base abstrait** `App\Http\Controllers\Api\Admin\AdminCrudController`
   — logique CRUD générique réutilisable (pagination, recherche, filtres, tri) — voir
   section 5 pour le contrat attendu. Tous les controllers CRUD "simples" en héritent.
6. **Middleware** `App\Http\Middleware\EnsureIsAdmin` — vérifie que
   `$request->user()->is_active === true` et que le rôle est `admin` ou `super_admin`.
   L'enregistrer comme alias `admin` dans `bootstrap/app.php` (Laravel 12 utilise la
   nouvelle syntaxe sans `Kernel.php` — utiliser `->withMiddleware()`).
7. **AuthController** (`app/Http/Controllers/Api/Admin/Auth/AuthController.php`) :
   `POST /api/admin/login`, `POST /api/admin/logout`, `GET /api/admin/me`.
8. **Tous les controllers CRUD** pour les 20 ressources listées en section 3, en
   suivant le pattern de `AdminCrudController`.
9. **Controllers spécifiques** :
   - `DashboardController` : `GET /api/admin/dashboard` → compteurs (total devis par
     statut, total messages non lus, dernières demandes, dernières news...).
   - `QuoteRequestController` : CRUD + `PATCH /api/admin/quote-requests/{id}/status`
     (changement de statut pipeline) + `POST /api/admin/quote-requests/{id}/notes`
     (ajout note de suivi).
   - `ContactMessageController` : liste, show, `PATCH /{id}/read` (marquer lu), destroy.
   - `NotificationController` : liste, `PATCH /{id}/read`, `PATCH /read-all`.
   - `SeoSettingController` : `GET/PUT /api/admin/seo-settings/{page_key}` (upsert par clé).
   - `PageController` : `GET/PUT /api/admin/pages/{key}` (upsert par clé — Hero,
     Promesse, chiffres clés, historique/timeline etc. vivent dans `content` JSON).
10. **`app/Traits/HandlesImageUploads`** : trait avec méthode
    `storeImage(UploadedFile $file, string $folder): string` qui stocke sur le disque
    `public` et retourne le chemin relatif, + `deleteImage(?string $path): void`.
    Réutilisé par tous les controllers qui gèrent des photos (Equipment, Product,
    Realisation, TeamMember, Reference, News, Testimonial, GalleryItem, Service, Page/og_image).
11. **`routes/api.php`** complet (squelette attendu en section 6).
12. **Seeders** : `AdminUserSeeder` (crée un `super_admin` par défaut, credentials
    dans `.env` idéalement, PAS en dur), + seeders de démo pour
    equipment_categories/product_categories/news_categories/services (les catégories
    "métier" fixes du cahier des charges, ex. Terrassement/Nivellement/Compactage/
    Chargement/Transport/Levage pour equipment_categories).
13. **`config/cors.php`** : autoriser l'origine du front (à paramétrer via `.env`,
    variable `FRONTEND_URL`).
14. **Policies (optionnel mais recommandé)** : si tu as le temps, ajoute une policy
    simple pour restreindre certaines actions destructives (delete) aux `super_admin`
    uniquement (ex. suppression d'un compte admin).
15. **Tests** : au minimum un test Feature par module critique (auth, quote-requests,
    upload d'image) avec Pest ou PHPUnit — le projet doit pouvoir être validé par
    `php artisan test`.
16. **Documentation API finale** : mettre à jour `docs/API_DOCUMENTATION.md` (déjà
    initié, à compléter) avec CHAQUE endpoint réellement implémenté, ses paramètres,
    et un exemple de réponse JSON réel (obtenu en testant, pas inventé).

---

## 2. CONVENTIONS STRICTES À RESPECTER

- **Toutes les routes admin sont préfixées `/api/admin/...`** et protégées par
  `auth:sanctum` + middleware `admin` (sauf `login`).
- **Pluriel kebab-case** pour les routes de ressources : `/equipment-categories`,
  `/quote-requests`, `/team-members`, `/news-categories`, `/gallery-items`,
  `/contact-messages`, `/seo-settings`. Exception : `/news` reste `/news` (pas de
  pluriel supplémentaire, "news" est déjà pluriel en anglais).
- **Toutes les listes (`index`) sont paginées**, acceptent `?page=`, `?per_page=`
  (défaut 15, max 100), `?search=` (recherche sur les champs textuels pertinents),
  `?sort=` (ex: `-created_at` pour DESC, `order` pour ASC), et des filtres spécifiques
  par ressource (ex: `?status=nouveau` pour quote-requests, `?category=engins` pour
  gallery-items, `?is_published=1`).
- **Validation** : soit via `FormRequest` dédiées (`app/Http/Requests/Admin/...`),
  soit via règles définies dans le controller — reste cohérent, choisis UNE approche
  et applique-la à tous les modules restants (recommandation : FormRequest pour la
  lisibilité, à partir de maintenant).
- **Upload d'images** : toujours valider `image|mimes:jpg,jpeg,png,webp|max:4096`
  (4 Mo), stocker via le trait `HandlesImageUploads`, retourner l'URL complète dans
  la réponse JSON (`Storage::url($path)` ou `asset('storage/'.$path)`), jamais le
  chemin disque brut.
- **Soft deletes** : PAS utilisé pour l'instant (cahier des charges ne l'exige pas),
  sauf si tu identifies un vrai besoin métier (ex: garder l'historique des devis même
  "supprimés" — dans ce cas ajoute `SoftDeletes` uniquement sur `QuoteRequest`).
- **Codes HTTP** : 200 (succès), 201 (création), 204 (suppression), 401 (non
  authentifié), 403 (non autorisé), 404, 422 (validation).
- **Ne jamais** committer de vrai mot de passe/API key. Utilise `.env.example`.

---

## 3. LISTE COMPLÈTE DES RESSOURCES CRUD ADMIN (20)

1. Users (admins) — `super_admin` uniquement peut créer/supprimer d'autres admins
2. Pages (upsert par clé, pas de delete)
3. SeoSettings (upsert par clé, pas de delete)
4. Services
5. EquipmentCategories
6. Equipments (+ gestion galerie d'images imbriquée)
7. Products
8. ProductCategories
9. Realisations (+ galerie images + association engins)
10. TeamMembers
11. References
12. NewsCategories
13. News (+ galerie images)
14. Testimonials
15. GalleryItems
16. QuoteRequests (pipeline + notes)
17. ContactMessages
18. Notifications (admin_notifications)
19. Dashboard (lecture seule, stats)
20. Auth (login/logout/me)

---

## 4. FORMAT DE RÉPONSE JSON STANDARD

Toutes les réponses passent par `App\Traits\ApiResponser` avec ces deux formes :

**Succès simple :**
```json
{
  "success": true,
  "message": "Équipement créé avec succès.",
  "data": { "...": "..." }
}
```

**Succès paginé (index) :**
```json
{
  "success": true,
  "data": [ { "...": "..." } ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 42,
    "last_page": 3
  }
}
```

**Erreur :**
```json
{
  "success": false,
  "message": "Les données envoyées sont invalides.",
  "errors": { "email": ["Le champ email est requis."] }
}
```

---

## 5. CONTRAT DU CONTROLLER DE BASE `AdminCrudController`

```php
abstract class AdminCrudController extends Controller
{
    protected string $modelClass;
    protected string $resourceClass; // JsonResource
    protected array $searchable = [];   // colonnes concernées par ?search=
    protected array $filterable = [];   // colonnes concernées par des query params exacts
    protected string $defaultSort = '-created_at';
    protected array $with = [];         // relations à eager-load

    public function index(Request $request): JsonResponse { /* pagination + search + filter + sort */ }
    public function show($id): JsonResponse { /* findOrFail + resource */ }
    public function store(Request $request): JsonResponse { /* validate via rulesForStore() puis create */ }
    public function update(Request $request, $id): JsonResponse { /* validate via rulesForUpdate($id) puis update */ }
    public function destroy($id): JsonResponse { /* delete + réponse 204/200 */ }

    abstract protected function rulesForStore(): array;
    abstract protected function rulesForUpdate($id): array;
}
```

Chaque controller enfant (ex: `TeamMemberController`) ne fait que définir ces
propriétés/méthodes + surcharger `store()`/`update()` uniquement quand il faut gérer
un upload de photo ou des relations (galeries, pivot equipments).

---

## 6. SQUELETTE ATTENDU DE `routes/api.php`

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Auth\AuthController;
use App\Http\Controllers\Api\Admin\{
    DashboardController, ServiceController, EquipmentCategoryController, EquipmentController,
    ProductCategoryController, ProductController, RealisationController, TeamMemberController,
    ReferenceController, NewsCategoryController, NewsController, TestimonialController,
    GalleryItemController, QuoteRequestController, ContactMessageController,
    NotificationController, SeoSettingController, PageController, UserController
};

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::apiResource('users', UserController::class);
        Route::apiResource('services', ServiceController::class);
        Route::apiResource('equipment-categories', EquipmentCategoryController::class);
        Route::apiResource('equipments', EquipmentController::class);
        Route::apiResource('product-categories', ProductCategoryController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('realisations', RealisationController::class);
        Route::apiResource('team-members', TeamMemberController::class);
        Route::apiResource('references', ReferenceController::class);
        Route::apiResource('news-categories', NewsCategoryController::class);
        Route::apiResource('news', NewsController::class);
        Route::apiResource('testimonials', TestimonialController::class);
        Route::apiResource('gallery-items', GalleryItemController::class);
        Route::apiResource('contact-messages', ContactMessageController::class)->except(['store']);

        Route::apiResource('quote-requests', QuoteRequestController::class)->except(['store']);
        Route::patch('/quote-requests/{quoteRequest}/status', [QuoteRequestController::class, 'updateStatus']);
        Route::post('/quote-requests/{quoteRequest}/notes', [QuoteRequestController::class, 'addNote']);

        Route::patch('/contact-messages/{contactMessage}/read', [ContactMessageController::class, 'markRead']);

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
        Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead']);

        Route::get('/seo-settings/{pageKey}', [SeoSettingController::class, 'show']);
        Route::put('/seo-settings/{pageKey}', [SeoSettingController::class, 'upsert']);

        Route::get('/pages/{key}', [PageController::class, 'show']);
        Route::put('/pages/{key}', [PageController::class, 'upsert']);
    });
});
```

> ⚠️ Ces routes sont pour le **back-office (admin)** uniquement. Les routes
> **publiques** consommées par le site vitrine (ex: `GET /api/services`,
> `GET /api/equipments`, `POST /api/quote-requests` pour soumettre le formulaire
> public, `POST /api/contact-messages`) sont **hors périmètre de ce prompt** — à
> discuter séparément avec le client, mais l'architecture (models, migrations) les
> supporte déjà nativement.

---

## 7. ORDRE DE TRAVAIL RECOMMANDÉ

1. `laravel new` (ou récupérer le projet existant du client) + copier les fichiers fournis.
2. `composer require laravel/sanctum` + config + migration Sanctum.
3. `php artisan migrate` — vérifier que les 23 migrations passent sans erreur.
4. Créer les 13 modèles restants.
5. Écrire `ApiResponser`, `HandlesImageUploads`, `AdminCrudController`, `EnsureIsAdmin`.
6. AuthController + route login/logout/me — tester avec Postman/curl.
7. Dérouler les 20 controllers un par un, en testant chaque module au fur et à mesure
   (ne pas tout écrire puis tout tester à la fin).
8. Seeders (admin user + catégories de base).
9. CORS + `.env.example` propre.
10. Compléter `docs/API_DOCUMENTATION.md` avec les vrais endpoints testés.
11. Tests automatisés minimum.

**Ne saute pas d'étape et ne code pas "tout d'un coup" — valide chaque module avant
de passer au suivant, exactement comme le pattern déjà utilisé pour les migrations.**
