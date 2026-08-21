<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Testimonial extends Model {
    protected $fillable=['quote','name','job_title','company','avatar','sort_order','is_published'];
    protected $casts=['is_published'=>'boolean'];
}