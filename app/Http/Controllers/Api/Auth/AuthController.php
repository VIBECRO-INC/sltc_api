<?php
namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller {
    public function login(Request $request) {
        $data=$request->validate(['email'=>'required|email','password'=>'required|string']);
        $user=User::where('email',$data['email'])->first();
        if(!$user || !Hash::check($data['password'],$user->password)) throw ValidationException::withMessages(['email'=>['Identifiants invalides.']]);
        $token=$user->createToken('admin-api')->plainTextToken;
        return response()->json(['message'=>'Connexion réussie','token'=>$token,'user'=>$user]);
    }
    public function logout(Request $request){$request->user()->currentAccessToken()?->delete(); return response()->json(['message'=>'Déconnexion réussie']);}
    public function me(Request $request){return response()->json(['data'=>$request->user()]);}
    public function updateProfile(Request $request){
        $user=$request->user();
        $data=$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email,'.$user->id,
            'avatar'=>'nullable|string|max:255',
        ]);
        $user->update($data);
        return response()->json(['message'=>'Profil mis à jour.','data'=>$user]);
    }
    public function updatePassword(Request $request){
        $user=$request->user();
        $data=$request->validate([
            'current_password'=>'required|string',
            'password'=>'required|string|min:8|confirmed',
        ]);
        if(!Hash::check($data['current_password'],$user->password)) throw ValidationException::withMessages(['current_password'=>['Mot de passe actuel incorrect.']]);
        $user->update(['password'=>Hash::make($data['password'])]);
        return response()->json(['message'=>'Mot de passe modifié avec succès.']);
    }
}