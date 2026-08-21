<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Article extends Model {
    protected $fillable=['title','slug','category','excerpt','content','cover_image','author','reading_time','published_at','is_published','seo_title','seo_description'];
    protected $casts=['published_at'=>'datetime','is_published'=>'boolean'];
    public function gallery(){return $this->morphMany(Media::class,'mediable');}
}