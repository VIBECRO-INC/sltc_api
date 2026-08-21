<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Reference extends Model {
    protected $fillable=['name','logo','sector','projects','description','website_url','sort_order','is_published'];
    protected $casts=['is_published'=>'boolean','projects'=>'array'];
}