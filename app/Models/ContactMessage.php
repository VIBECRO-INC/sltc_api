<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ContactMessage extends Model {
    protected $fillable=['name','company','email','phone','subject','message','is_read','read_at'];
    protected $casts=['is_read'=>'boolean','read_at'=>'datetime'];
}