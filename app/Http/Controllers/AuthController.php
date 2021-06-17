<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Response;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request){
        $validateData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);
        
        try {
            $user = User::create([
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password']),
            ]);
        } catch (\Throwable $th) {
            return "error al registrar";
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function update(Request $request){
        $validateData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);
        //$user = User::where('email', $request['email'])->firstOrFail();
        $user = Auth::user();
        
        if(!$name=$request->input('name') || $email=$request->input('email') || $password=$request->input('password')){
            return response()->json(['status'=>'error','code'=>422, 'message'=>'Faltan valores para completar el proceso.'],422);
        }

        $name=$request->input('name');
		$email=$request->input('email');
		$password=$request->input('password');

        if ($request->method() === 'PATCH')
		{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;
            // Actualización parcial de campos.
			if ($name)
			{
				$user->name = $validateData['name'];
				$bandera=true;
			}

			if ($email)
			{
				$user->email= $validateData['email'];
				$bandera=true;
			}

			if ($password)
			{
				$user->password= Hash::make($validateData['password']);
				$bandera=true;
			}
            if ($bandera)
			{
				$user->save();
				return response()->json(['status'=>'success','message'=>'Los datos fueron modificados con éxito.','data'=>$user], 200);
			}
            else
			{
				return response()->json(['code'=>304,'message'=>'Ocurrió un problema al actualizar los datos.'],304);
			}
		}

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'response' => 'Las credenciales son incorrectas',
            ], 401); 
        }
        
        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user'=> $user
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'message' => 'cerrado con éxito',
        ]);
    }

    public function userinfo(Request $request){
        return $request->user();   
    }
}
