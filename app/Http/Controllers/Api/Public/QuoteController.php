<?php
namespace App\Http\Controllers\Api\Public;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use App\Models\QuoteRequest;
use Illuminate\Support\Str;
class QuoteController extends Controller {
    public function store(StoreQuoteRequest $request) {
        $data=$request->validated();
        $data['reference']='SLTC-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
        $quote=QuoteRequest::create($data);
        return response()->json(['message'=>'Votre demande de devis a bien été envoyée.','data'=>$quote],201);
    }
}