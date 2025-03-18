<?php

namespace App\Http\Controllers;

use App\Models\Competences;
use App\Http\Requests\StoreCompetencesRequest;
use App\Http\Requests\UpdateCompetencesRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompetencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $competences = Competences::all();
        return response()->json([
            'status' => 'success',
            'data' => $competences
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompetencesRequest $request)
    {
        try{
            $validated = $request->validate([
                "name" => "required|string|unique:competences,name",
                "description" => "required|string",
                "type" => "required|string",
                "level" => "required|string",
                "user_id" => "required|exists:users,id"
            ]);

            if (auth()->check()) {
                $validated['user_id'] = auth()->id();
            }

            $competence = Competences::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Competence created successfully',
                'data' => $competence
            ], Response::HTTP_CREATED);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Competences $competence)
    {
        return response()->json([
            'status' => 'success',
            'data' => $competence
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompetencesRequest $request, Competences $competence)
    {
        try{
            $validated = $request->validate([
                "name" => "required|string|unique:competences,name",
                "description" => "required|string",
                "type" => "required|string",
                "level" => "required|string",
                "user_id" => "required|integer|exists:users,id"
            ]);
            $competence->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Competence updated successfully',
                'data' => $competence
            ], Response::HTTP_OK);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        }
    }

        /**
         * Remove the specified resource from storage.
     */
    public function destroy(Competences $competence)
    {
        $competence->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Competence deleted successfully'
        ], Response::HTTP_OK);
    }
}
