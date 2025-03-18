<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserCompetences extends Controller
{
    //
    public function AddCompetence(Request $request , User $user) {
        try{
            $validateCompetence = $request->validate([
                'competences' => 'required|array' ,
                'competences.*.id' => 'required|exists:competences,id'
            ]);

            foreach ($validateCompetence['competences'] as $competenceId) {
                $user->competences()->attach($competenceId);
            }

            return response()->json($user->competences);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
