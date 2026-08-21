<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TeamMember extends Model {
    protected $fillable=['first_name','last_name','name','job_title','department','photo','bio','expertise','years_experience','linkedin_url','sort_order','is_published'];
    protected $casts=['expertise'=>'array','is_published'=>'boolean'];
}