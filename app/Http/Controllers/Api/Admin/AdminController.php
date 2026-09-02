<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    Service, Equipment, Project, TeamMember, Reference, Product,
    Article, Testimonial, GalleryItem, Page, QuoteRequest,
    ContactMessage, SeoSetting, Setting, PageView
};

class AdminController extends Controller
{
    private array $map = [
        'services' => Service::class,
        'equipment' => Equipment::class,
        'projects' => Project::class,
        'team' => TeamMember::class,
        'references' => Reference::class,
        'products' => Product::class,
        'news' => Article::class,
        'testimonials' => Testimonial::class,
        'gallery' => GalleryItem::class,
        'pages' => Page::class,
    ];

    private array $fillable = [
        'services' => ['name','slug','short_description','description','image','features','cta_label','cta_url','sort_order','is_published','seo_title','seo_description'],
        'equipment' => ['name','slug','category','brand','model','year','description','image','specifications','capacity','power','dimensions','weight','applications','availability','status','location','seo_title','seo_description','is_published'],
        'projects' => ['title','slug','client','sector','service','location','project_date','description','results','video_url','is_featured','is_published','seo_title','seo_description'],
        'team' => ['first_name','last_name','name','job_title','department','photo','bio','expertise','years_experience','linkedin_url','sort_order','is_published'],
        'references' => ['name','logo','sector','projects','description','website_url','sort_order','is_published'],
        'products' => ['name','slug','category','description','image','availability','price_type','is_published','seo_title','seo_description'],
        'news' => ['title','slug','category','excerpt','content','cover_image','author','reading_time','published_at','is_published','seo_title','seo_description'],
        'testimonials' => ['quote','name','job_title','company','avatar','sort_order','is_published'],
        'gallery' => ['title','category','image','video_url','alt','sort_order','is_published'],
        'pages' => ['title','slug','content','featured_image','is_published','seo_title','seo_description'],
    ];

    private array $required = [
        'services' => ['name'],
        'equipment' => ['name', 'category'],
        'projects' => ['title', 'project_date'],
        'team' => ['name', 'job_title'],
        'references' => ['name'],
        'products' => ['name'],
        'news' => ['title', 'content'],
        'testimonials' => ['quote', 'name'],
        'gallery' => ['category'],
        'pages' => ['title'],
    ];

    public function dashboard()
    {
        return response()->json([
            'data' => [
                'quotes_new' => QuoteRequest::where('status', 'new')->count(),
                'quotes_total' => QuoteRequest::count(),
                'unread_messages' => ContactMessage::where('is_read', false)->count(),
                'equipment' => Equipment::count(),
                'projects' => Project::count(),
                'articles' => Article::count(),
            ],
        ]);
    }

    public function notifications()
    {
        return response()->json(['data' => [
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'new_quotes' => QuoteRequest::where('status', 'new')->where('is_read', false)->count(),
            'messages' => ContactMessage::where('is_read', false)->orderByDesc('created_at')->take(5)->get(),
            'quotes' => QuoteRequest::where('status', 'new')->where('is_read', false)->orderByDesc('created_at')->take(5)->get(),
        ]]);
    }

    public function markNotificationsRead(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:quotes,messages',
            'id' => 'nullable|integer',
        ]);

        if ($data['type'] === 'quotes') {
            $q = QuoteRequest::where('status', 'new')->where('is_read', false);
            if (!empty($data['id'])) {
                $q->where('id', $data['id']);
            }
            $q->update(['is_read' => true]);
        } else {
            $m = ContactMessage::where('is_read', false);
            if (!empty($data['id'])) {
                $m->where('id', $data['id']);
            }
            $m->update(['is_read' => true]);
        }

        return response()->json(['message' => 'OK']);
    }

    public function analytics()
    {
        $total = PageView::count();
        $unique = PageView::distinct('ip')->count('ip');
        $last30 = PageView::where('created_at', '>=', now()->subDays(30))->count();

        $byDay = PageView::selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('day')->orderBy('day')->get();

        $topPages = PageView::selectRaw('path, COUNT(*) as count')
            ->groupBy('path')->orderByDesc('count')->take(10)->get();

        $devices = PageView::selectRaw('COALESCE(device, "inconnu") as device, COUNT(*) as count')
            ->groupBy('device')->get();

        $sources = PageView::selectRaw('COALESCE(source, "Direct") as source, COUNT(*) as count')
            ->groupBy('source')->get();

        return response()->json([
            'data' => [
                'total' => $total,
                'unique' => $unique,
                'last_30_days' => $last30,
                'by_day' => $byDay,
                'top_pages' => $topPages,
                'devices' => $devices,
                'sources' => $sources,
            ],
        ]);
    }

    public function index(Request $request)
    {
        $model = $this->model($request->route('resource'));
        return response()->json($model::latest()->paginate(min((int) $request->integer('per_page', 20), 100)));
    }

    public function store(Request $request)
    {
        $resource = $request->route('resource');
        $model = $this->model($resource);
        $request->validate($this->rules($resource));
        $data = $this->emptyToNull($this->normalize($resource, $request->only($this->fillable[$resource])));
        $data = $this->sanitizeNulls($resource, $data);

        if ($this->hasSlug($model)) {
            if (empty($data['slug']) && isset($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            if (empty($data['slug']) && isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }
            if (!empty($data['slug'])) {
                $data['slug'] = $this->uniqueSlug($model, $data['slug']);
            }
        }

        $item = $model::create($data);
        $this->syncPhoto($resource, $item, $request->input('photo'));
        return response()->json(['data' => $item->refresh()], 201);
    }

    public function show(Request $request)
    {
        $model = $this->model($request->route('resource'));
        return response()->json(['data' => $model::findOrFail((int) $request->route('id'))]);
    }

    public function update(Request $request)
    {
        $resource = $request->route('resource');
        $model = $this->model($resource);
        $request->validate($this->rules($resource, true));
        $item = $model::findOrFail((int) $request->route('id'));
        $data = $this->emptyToNull($this->normalize($resource, $request->only($this->fillable[$resource])));
        $data = $this->sanitizeNulls($resource, $data);
        if ($this->hasSlug($model) && !empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($model, $data['slug'], $item->id);
        }
        $item->update($data);
        $this->syncPhoto($resource, $item, $request->input('photo'));
        return response()->json(['data' => $item->fresh()]);
    }

    public function destroy(Request $request)
    {
        $model = $this->model($request->route('resource'));
        $model::findOrFail((int) $request->route('id'))->delete();
        return response()->json(['message' => 'Ressource supprimée.']);
    }

    private function model(?string $resource)
    {
        abort_unless($resource && isset($this->map[$resource]), 404);
        return $this->map[$resource];
    }

    private function hasSlug(string $model): bool
    {
        return Schema::hasColumn((new $model)->getTable(), 'slug');
    }

    private function normalize(string $resource, array $data): array
    {
        if ($resource === 'services' && isset($data['features']) && is_string($data['features'])) {
            $data['features'] = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $data['features'])), fn ($v) => $v !== ''));
        }
        if ($resource === 'references' && isset($data['projects']) && is_string($data['projects'])) {
            $data['projects'] = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $data['projects'])), fn ($v) => $v !== ''));
        }
        return $data;
    }

    private function emptyToNull(array $data): array
    {
        return array_map(fn ($v) => $v === '' ? null : $v, $data);
    }

    private function sanitizeNulls(string $resource, array $data): array
    {
        $table = (new ($this->model($resource)))->getTable();
        if (!Schema::hasTable($table)) return $data;
        $columns = collect(Schema::getColumns($table))->keyBy('name');
        foreach ($data as $key => $value) {
            if ($value === null && isset($columns[$key]) && !$columns[$key]['nullable']) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    private function uniqueSlug(string $model, string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $i = 1;
        while ($model::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.($i++);
        }
        return $slug;
    }

    private function syncPhoto(string $resource, $item, $photo): void
    {
        if (! method_exists($item, 'photos')) {
            return;
        }
        $path = trim((string) $photo);
        if ($path === '') {
            $item->photos()->delete();
            return;
        }
        $item->photos()->delete();
        $item->photos()->create([
            'type' => 'image',
            'path' => $path,
            'alt' => $item->title ?? $item->name ?? null,
            'sort_order' => 0,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:4096'],
        ]);

        $dir = public_path('images');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $request->file('file');
        $name = Str::uuid()->toString().'.'.strtolower($file->getClientOriginalExtension());
        $file->move($dir, $name);

        return response()->json(['path' => '/images/'.$name], 201);
    }

    private function rules(string $resource, bool $partial = false): array
    {
        $prefix = $partial ? 'sometimes|' : '';
        return collect($this->required[$resource] ?? [])
            ->mapWithKeys(fn ($field) => [$field => $prefix.'required'])
            ->all();
    }

    public function quotes()
    {
        return response()->json(QuoteRequest::latest()->paginate(20));
    }

    public function quote(QuoteRequest $quote)
    {
        return response()->json(['data' => $quote]);
    }

    public function quoteStatus(Request $request, QuoteRequest $quote)
    {
        $data = $request->validate([
            'status' => 'required|in:new,qualified,quote,negotiation,won,lost',
        ]);

        $quote->update($data);
        return response()->json(['data' => $quote->fresh()]);
    }

    public function contacts()
    {
        return response()->json(ContactMessage::latest()->paginate(20));
    }

    public function contact(ContactMessage $contact)
    {
        return response()->json(['data' => $contact]);
    }

    public function contactRead(ContactMessage $contact)
    {
        $contact->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['data' => $contact->fresh()]);
    }

    public function seoIndex()
    {
        return response()->json(['data' => SeoSetting::orderBy('page_slug')->get()]);
    }

    public function seoUpdate(Request $request, SeoSetting $seo)
    {
        $seo->update($request->only([
            'page_slug','title','meta_description','canonical_url',
            'og_title','og_description','og_image','robots','schema_json'
        ]));

        return response()->json(['data' => $seo->fresh()]);
    }

    public function settings()
    {
        return response()->json(['data' => Setting::all()->pluck('value', 'key')]);
    }

    public function settingsUpdate(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        return response()->json(['message' => 'Paramètres mis à jour.']);
    }
}
