<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;

class OfferController extends Controller
{
     /**
    * @OA\GET(
    *      path="/api/offers",
    *      summary="Offres",
    *      description="get all offres",
    *      tags={"Offer"},
    *      @OA\Response(response="200", description="get all offres"),
    *      @OA\Response(response="403", description="Unauthenticated"),
    * )
    */
    public function index()
    {
        $offers = Offer::with('user')->latest()->get();
        return response()->json($offers);
    }

    /**
    * @OA\POST(
    *      path="/api/offers",
    *      summary="Add Offer",
    *      description="Add new offer",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              @OA\Property(property="title", type="string"),
    *              @OA\Property(property="description", type="string"),
    *              @OA\Property(property="location", type="string"),
    *              @OA\Property(property="company_name", type="string"),
    *              @OA\Property(property="salary", type="number"),
    *              @OA\Property(property="job_type", type="string"),
    *              @OA\Property(property="deadline", type="string", format="date"),
    *          )
    *      ),
    *      security={{"bearerAuth":{}}},
    *      tags={"Offer"},
    *      @OA\Response(response="201", description="Offer Added successfully"),
    *      @OA\Response(response="403", description="Unauthenticated"),
    * )
    */
    public function store(StoreOfferRequest $request)
    {
        try{
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'job_type' => ['in:full-time,part-time,contract,freelance,internship'],
                'deadline' => 'required|date_format:d-m-Y|after:today',
            ]);
            $offer = Offer::create([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'company_name' => $request->company_name,
                'salary' => $request->salary,
                'job_type' => $request->job_type,
                'deadline' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->deadline)->format('Y-m-d'),
                'is_active' => true,
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
    * @OA\PUT(
    *      path="/api/offers/{id}",
    *      summary="Update Offer",
    *      description="Update an existing offer",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              @OA\Property(property="title", type="string"),
    *              @OA\Property(property="description", type="string"),
    *              @OA\Property(property="location", type="string"),
    *              @OA\Property(property="company_name", type="string"),
    *              @OA\Property(property="salary", type="number"),
    *              @OA\Property(property="job_type", type="string"),
    *              @OA\Property(property="deadline", type="string", format="date"),
    *              @OA\Property(property="is_active", type="boolean")
    *          )
    *      ),
    *      tags={"Offer"},
    *      @OA\Response(response="200", description="Offer updated successfully"),
    *      @OA\Response(response="422", description="Validation errors")
    * )
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
    * @OA\POST(
    *      path="/api/offers/{id}",
    *      summary="Add Offer",
    *      description="Add new offer",
    *      security={{"bearerAuth":{}}},
    *      tags={"Offer"},
    *      @OA\Response(response="200", description="Offer deletedy"),
    *      @OA\Response(response="422", description="Validation errors")
    * )
    */
    public function destroy(Offer $offer)
    {
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
