<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompetenceResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserCompetencesController extends Controller
{
    
    /**
    * @OA\POST(
    *      path="/api/users/{id}/competences",
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

    public function store(Request $request, User $user) {
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

    /**
     * @OA\DELETE(
     *      path="/api/deleteCompetence/{user}/{competence}",
     *      summary="Delete user competence",
     *      description="Removes a specific competence from a user",
     *      tags={"User Competences"},
     *      @OA\Parameter(
     *          name="competence",
     *          in="path",
     *          required=true,
     *          description="Competence ID to remove",
     *          @OA\Schema(type="integer")
     *      ),
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response="200", description="Competence removed"),
     *      @OA\Response(response="404", description="Competence not found"),
     * )
     */
    public function destroy(User $user, $competence) {
        try {
            if (!$user->competences()->where('competences.id', $competence)->exists()) {
                return response()->json([
                    'message' => 'Competence not found for this user'
                ], 404);
            }

            $user->competences()->detach($competence);

            return response()->json([
                'message' => 'Competence removed successfully',
                'competences' => CompetenceResource::collection($user->competences)
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
