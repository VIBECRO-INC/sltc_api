<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Project extends Model {
    protected $fillable=['title','slug','client','sector','service','location','project_date','description','results','video_url','is_featured','is_published','seo_title','seo_description'];
    protected $casts=['project_date'=>'date','is_featured'=>'boolean','is_published'=>'boolean'];
    public function equipment(){return $this->belongsToMany(Equipment::class);}
    public function photos(){return $this->morphMany(Media::class,'mediable');}
}