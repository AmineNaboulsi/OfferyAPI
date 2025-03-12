<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;

class OfferController extends Controller
{
     /**
    * @OA\GET(
    *      path="/offers",
    *      summary="Offres",
    *      description="get all offres",
    *      tags={"Auth"},
    *      @OA\Response(response="200", description="get all offres"),
    *      @OA\Response(response="404", description="No offers found"),
    * )
    */
    public function index()
    {
        $offers = Offer::with('user')->latest()->get();
        return response()->json($offers);
    }

     /**
    * @OA\POST(
    *      path="/offers",
    *      summary="Offres",
    *      description="get all offres",
    *      tags={"Auth"},
    *      @OA\Response(response="200", description="get all offres"),
    *      @OA\Response(response="404", description="No offers found"),
    * )
    */
    public function store(StoreOfferRequest $request)
    {
        //['full-time', 'part-time', 'contract', 'freelance', 'internship']
        try{
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'salary' => 'required|numeric|min:0',
                'job_type' => 'required|string|max:255',
                'deadline' => 'required|date_format:d-m-Y|after:today',
                'is_active' => 'boolean',
            ]);
            $offer = Offer::create([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'company_name' => $request->company_name,
                'salary' => $request->salary,
                'job_type' => $request->job_type,
                'deadline' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->deadline)->format('Y-m-d'),
                'is_active' => $request->is_active ?? true,
                'user_id' => auth()->id(),
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Offer created successfully',
                'data' => $offer
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' =>  $e->getMessage()
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        return response()->json([
            'status' => true,
            'data' => $offer->load('user')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        if ($offer->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $offer->update([
            'title' => $request->title ?? $offer->title,
            'description' => $request->description ?? $offer->description,
            'location' => $request->location ?? $offer->location,
            'company_name' => $request->company_name ?? $offer->company_name,
            'salary' => $request->salary ?? $offer->salary,
            'job_type' => $request->job_type ?? $offer->job_type,
            'deadline' => $request->deadline ?? $offer->deadline,
            'is_active' => $request->is_active ?? $offer->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Offer updated successfully',
            'data' => $offer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        // Check if user owns the offer
        if ($offer->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $offer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Offer deleted successfully'
        ]);
    }
}
