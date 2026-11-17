<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $date = Carbon::today();

        $tenants = Tenant::all();

        // Return the array directly (Laravel handles JSON conversion)
        return response()->json([
            'success' => true,
            'data' => $tenants,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:tenants,name'],
           'unit' => ['required', 'integer'],
            'total_heads' => ['required', 'integer'],
            'rent_amount' => ['required', 'integer'],
            'internet_plan' => ['required'],
            'move_in_date' => ['required', 'date']
        ]);

        $tenant = Tenant::create($validated);

        return response()->json($tenant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        return response()->json([
            'success' => true,
            'data' => $tenant,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
