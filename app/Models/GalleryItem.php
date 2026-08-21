<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GalleryItem extends Model {
    protected $fillable=['title','category','image','video_url','alt','sort_order','is_published'];
    protected $casts=['is_published'=>'boolean'];
}