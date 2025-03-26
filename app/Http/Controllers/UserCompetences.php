<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompetenceResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserCompetences extends Controller
{
    
    /**
    * @OA\POST(
    *      path="/api/users/{user}/competences",
     *     summary="Add competences to a user",
     *     description="Attaches one or more competences to a user",
     *     tags={"User Competences"},
    *     @OA\RequestBody(
     *         required=true,
     *         description="Competences to attach to user",
     *         @OA\JsonContent(
     *             required={"competences"},
     *             @OA\Property(
     *                 property="competences",
     *                 type="array",
     *                 description="Array of competence objects",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id"},
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         description="Competence ID"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
    *      security={{"bearerAuth":{}}},
    *      @OA\Response(response="200", description="add offres"),
    * )
    */

    public function AddCompetence(Request $request , User $user) {
        try{
            $validateCompetence = $request->validate([
                'competences' => 'required|array' ,
                'competences.*.id' => 'required|exists:competences,id'
            ]);

            foreach ($validateCompetence['competences'] as $competenceId) {
                $user->competences()->attach($competenceId);
            }

            return CompetenceResource::collection($user->competences);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
