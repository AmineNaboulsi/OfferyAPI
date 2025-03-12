<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update user information
     *
     * @OA\Put(
     *     path="/api/user",
     *     tags={"Users"},
     *     summary="Update user's information name , email",
     *     description="Updates the name and email of the currently authenticated user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User information updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User information updated successfully"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

    public function edit(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.auth()->id() ,
            ]);
            $user = auth()->user();

            $user->update($validatedData);

            return response()->json([
                'message' => 'User information updated successfully',
                'user' => $user
            ]);
        }catch(\Exception $e){
             return response()->json([
                'status' => false,
                'message' =>  $e->getMessage()
            ], 403);
        }
    }

    /**
     * Change user password
     *
     * @OA\Put(
     *     path="/api/user/password",
     *     tags={"Users"},
     *     summary="Change authenticated user's password",
     *     description="Changes the password of the currently authenticated user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","new_password","new_password_confirmation"},
     *             @OA\Property(property="current_password", type="string", example="currentpass123"),
     *             @OA\Property(property="new_password", type="string", minLength=8, example="newpass123"),
     *             @OA\Property(property="new_password_confirmation", type="string", example="newpass123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password changed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Current password is incorrect"
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        try{
            $user = auth()->user();

            $validatedData = $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user->update([
                'password' => bcrypt($validatedData['new_password'])
            ]);

            return response()->json([
                'message' => 'Password changed successfully'
            ]);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
