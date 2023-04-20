<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UsersController extends Controller
{
  public function showbyid($id){
    $user = User::find($id);
    return response()->json(["data"=>$user]);
  }

  public function register(Request $request)
    {
        $data = $request->json()->all();

        $itExistsUserName=User::where('email',$data['email'])->first();

        if ($itExistsUserName==null) {
            $user = User::create(
                [
                    'name'=>$data['name'],
                    'email'=>$data['email'],
                    'password'=>Hash::make($data['password'])

                ]
            );

            $token = $user->createToken('web')->plainTextToken;


                return response()->json([
                    'data'=>$user,
                    'token'=> $token

                ],200);
        } else {
               return response()->json([
                'data'=>'User already exists!',
                'status'=> false
            ],200);
}

}


public function newPassword($email)
{
    //verificamos que el email si le corresponda a un usuario
    $usuario = User::where('email', $email)->first();

    //Si no encuentra ningun usuario con ese email nos dira que no existe
    if (!$usuario) 
    {
        return response()->json(['message' => 'El usuario no existe'], 200);
    }
    else
    {
        // Generar una contraseña aleatoria de 6 caracteres
    $nuevaPassword = Str::random(6);
    
    // Actualizar el campo password de la tabla user
    $usuario->password = Hash::make($nuevaPassword);
    //guarda los cambios en la bd
    $usuario->save();
    
    // Enviar respuesta un mensaje, la nueva contraseña y el usuario al que se le hizo el cambio
    return response()->json([
        'mensaje' => 'Contraseña actualizada',
        'nuevaPassword' => $nuevaPassword,
        
    ], 200);
    }

}


}

