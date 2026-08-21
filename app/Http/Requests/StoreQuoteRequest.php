<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreQuoteRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array { return [
        'need_type'=>'required|string|max:100',
        'description'=>'nullable|string|max:10000',
        'project_location'=>'required|string|max:255',
        'needed_at'=>'nullable|date',
        'first_name'=>'required|string|max:100',
        'last_name'=>'nullable|string|max:100',
        'company'=>'nullable|string|max:255',
        'email'=>'required|email|max:255',
        'phone'=>'required|string|max:50',
        'whatsapp'=>'nullable|string|max:50',
        'consent'=>'accepted',
    ]; }
}