<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRoleEffectation;
class UserController extends Controller
{
     /**
    * @OA\POST(
    *      path="/api/upload",
    *      summary="Upload cv to profile",
    *      description="Upload cv to profile",
    *      tags={"User"},
    *      @OA\Response(response="201", description="File uploaded successfully"),
    *      @OA\Response(response="422", description="Fiel required")
    * )
    */
    public function upload(Request $request){
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf',
            ]);

            $userId = auth()->id();

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('files/' . $userId, $fileName);

            // $fileUri = url('storage/' . $path);
            User::where('id', $userId)->update([
                "cv_path" => $fileName
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'fileName' => $fileName,
            ],201);
        } catch(\Exception $e) {
            return response()->json([
                'message' =>  $e->getMessage()
            ], 422);
        }
    }

    public function postule(Request $request){
        try {
            $request->validate([
                'offre' => 'required|integer|exists:offres,id',
                'message' => 'required|string',
            ]);

            $user = auth()->user();
            if($user->cv_path == null){
                return response()->json([
                    'message' => 'Please upload your cv first'
                ], 422);
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('files/' . $userId, $fileName);

            $fileUri = url('storage/' . $path);
            User::where('id', $userId)->update([
                "cv_path" => $fileName
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'fileName' => $fileName,
                'fileUri' => $fileUri,
                'userId' => $userId
            ],201);
        } catch(\Exception $e) {
            return response()->json([
                'message' =>  $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/affect-role",
     *      summary="Affect role to user",
     *      description="Assign a role to a user",
     *      tags={"User"},
     *      @OA\Response(response="200", description="Role assigned successfully"),
     *      @OA\Response(response="422", description="Validation error")
     * )
     */
    public function affectrole(UserRoleEffectation $request)  {
        try {

            $user = auth('api')->user();
            $roleId = $request->role_id;
            $user->role_id = $roleId;
            $user->save();

            return response()->json([
                'message' => 'Role assigned successfully',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
