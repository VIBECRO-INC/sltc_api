<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuoteRequest extends Model {
    protected $fillable=['reference','need_type','description','project_location','needed_at','first_name','last_name','company','email','phone','whatsapp','status','is_read','source','consent'];
    protected $casts=['needed_at'=>'date','consent'=>'boolean'];
}