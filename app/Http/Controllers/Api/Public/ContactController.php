<?php
namespace App\Http\Controllers\Api\Public;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
class ContactController extends Controller {
    public function store(StoreContactRequest $request) {
        $message=ContactMessage::create($request->validated());
        return response()->json(['message'=>'Votre message a bien été envoyé.','data'=>$message],201);
    }
}