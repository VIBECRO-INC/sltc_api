<?php
namespace App\Http\Controllers\Api\Public;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Service,Equipment,Project,TeamMember,Reference,Product,Article,Testimonial,GalleryItem,Page,SeoSetting,Setting,PageView};
class ContentController extends Controller {
    public function home(){return response()->json(['data'=>[
        'services'=>Service::where('is_published',true)->orderBy('sort_order')->get(),
        'featured_equipment'=>Equipment::where('is_published',true)->latest()->take(6)->get(),
        'featured_projects'=>Project::where('is_published',true)->with('photos')->orderByDesc('is_featured')->orderBy('id')->take(6)->get(),
        'team'=>TeamMember::where('is_published',true)->orderBy('sort_order')->get(),
        'references'=>Reference::where('is_published',true)->orderBy('sort_order')->get(),
        'testimonials'=>Testimonial::where('is_published',true)->orderBy('sort_order')->get(),
        'settings'=>Setting::all()->pluck('value','key'),
    ]]);}
    public function services(){return $this->list(Service::class);}
    public function service(Service $service){return response()->json(['data'=>$service]);}
    public function equipment(){return $this->filtered(Equipment::query()->with('photos'));}
    public function equipmentShow(Equipment $equipment){return response()->json(['data'=>$equipment->load('photos')]);}
    public function projects(){return $this->filtered(Project::query()->with('photos'));}
    public function project(Project $project){return response()->json(['data'=>$project->load(['equipment','photos'])]);}
    public function team(){return $this->list(TeamMember::class);}
    public function references(){return $this->list(Reference::class);}
    public function products(){return $this->filtered(Product::query());}
    public function news(){return $this->filtered(Article::query());}
    public function article(Article $article){return response()->json(['data'=>$article->load('gallery')]);}
    public function testimonials(){return $this->list(Testimonial::class);}
    public function gallery(){return $this->filtered(GalleryItem::query());}
    public function page(Page $page){return response()->json(['data'=>$page]);}
    public function settings(){return response()->json(['data'=>Setting::all()->pluck('value','key')]);}
    public function seo(string $slug){return response()->json(['data'=>SeoSetting::where('page_slug',$slug)->firstOrFail()]);}
    public function track(Request $request){PageView::create([
        'path'=>$request->input('path','/'),
        'referrer'=>$request->input('referrer'),
        'user_agent'=>$request->userAgent(),
        'device'=>$this->device($request->userAgent()),
        'source'=>$this->source($request->input('referrer')),
        'ip'=>$request->ip(),
    ]); return response()->json(['message'=>'Tracked']);}
    private function device(?string $ua): string {
        $ua=strtolower((string) $ua);
        if(str_contains($ua,'ipad')||str_contains($ua,'tablet')) return 'tablette';
        if(preg_match('/mobile|android|iphone|ipod/i',$ua)) return 'mobile';
        return 'desktop';
    }
    private function source(?string $referrer): string {
        if(!$referrer) return 'Direct';
        if(str_contains($referrer,'google.')) return 'Google';
        if(str_contains($referrer,'bing.')) return 'Bing';
        if(str_contains($referrer,'facebook.')||str_contains($referrer,'instagram.')||str_contains($referrer,'linkedin.')) return 'Réseaux sociaux';
        return 'Références';
    }
    private function list($model){return $model::where('is_published',true)->orderBy('sort_order')->paginate(100);}
    private function filtered($query){$query->where('is_published',true); if(request('search')) $query->where(function($q){$q->where('name','like','%'.request('search').'%')->orWhere('title','like','%'.request('search').'%');}); return response()->json($query->orderBy('id')->paginate(100));}
}