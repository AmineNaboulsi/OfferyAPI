<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{

    /**
    * @OA\POST(
    *      path="/login",
    *      summary="SignIn",
    *      description="SignIn",
    *      @AO\Parameter(
    *            name="email",
    *            in="body",
    *            required=true,
    *            @OA\Schema(
    *                type="string"
    *            )
    *      ),
    *       @AO\Parameter(
    *            name="password",
    *            in="body",
    *            required=true,
    *            @OA\Schema(
    *                type="string"
    *            )
    *      ),
    *      tags={"Auth"},
    *      @OA\Info(),
    *      @OA\Response(response="200", description="login successfuly"),
    *      @OA\Response(response="404", description="Account not found"),
    * )
    */
    public function signin(Request $Request){
       try{
          $credentials = $Request->validate([
             "email" => ['required' , 'email' ,'string' ] ,
             "password" => ['required' , 'string']
          ]);

          if(Auth::attempt($credentials)){
             $user = Auth::user();
             $token = $user->createToken('token-api') ;
             return response()->json([
                "message" => "login successfuly",
                "token" => $token->plainTextToken
             ]);
          }else{
             return response()->json([
                "message" => "Account not found"
             ]);
          }

       }catch(\Exception $e){
          return response()->json([
             "message" => $e->getMessage()
          ]);
       }
    }

    /**
    * @OA\POST(
    *      path="/register",
    *      summary="Register",
    *      description="Register a new user",
    *      tags={"Auth"},
    *      @OA\Response(response="200", description="User registered successfully"),
    *      @OA\Response(response="422", description="Validation errors")
    * )
    */
    public function register(Request $request){
       try{
          $validatedData = $request->validate([
             "name" => ['required', 'string', 'max:255'],
             "email" => ['required', 'string', 'email', 'max:255', 'unique:users'],
             "password" => ['required', 'string', 'min:8'],
          ]);

          $user = User::create([
             'name' => $validatedData['name'],
             'email' => $validatedData['email'],
             'password' => bcrypt($validatedData['password']),
          ]);

          $token = $user->createToken('token-api');

          return response()->json([
             "message" => "User registered successfully",
             "token" => $token->plainTextToken
          ], 201);

       }catch(\Exception $e){
          return response()->json([
             "message" => $e->getMessage()
          ], 422);
       }
    }
}
