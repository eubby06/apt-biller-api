<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = Carbon::today();

        if ($request->has('tenant_id')) {
            $invoices = Invoice::where('tenant_id', $request->get('tenant_id'))->with('tenant')->get();
        } else {
            $invoices = Invoice::with('tenant')->get();
        }

        // Return the array directly (Laravel handles JSON conversion)
        return response()->json([
            'success' => true,
            'data' => $invoices,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenant = Tenant::find($request->get('tenant_id'));

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant could not be found',
            ], 304);
        }

        $invoice = Invoice::where('tenant_id', $tenant->id)->orderBy('id', 'desc')->first();

        $validated = $request->validate([
            'tenant_id' => ['required', 'integer'],
            'prev_date' => ['required', 'date'],
            'prev_reading' => ['required', 'integer', 'min:0'],
            'curr_date' => ['required', 'date', 'after_or_equal:prev_date'],
            'curr_reading' => ['required', 'integer', 'min:0']
        ]);

        $prevReading = (int) $request->get('prev_reading');
        $currReading = (int) $request->get('curr_reading');
        $readingDifference = $currReading - $prevReading;
        $rentAmount = (int) $tenant->rent_amount;
        $electricityAmount = $readingDifference * (int) config('rates.electricity');
        $waterAmount = (int) $tenant->total_heads * (int) config('rates.water');

        $internetAmount = match ($tenant->internet_plan) {
            'low_speed' => (int) config('rates.internet_lowspeed'),
            'high_speed' => (int) config('rates.internet_highspeed'),
            default => throw new \InvalidArgumentException('Invalid internet plan.'),
        };

        $total = $rentAmount + $electricityAmount + $waterAmount + $internetAmount;

        $invoice = Invoice::create([
            'tenant_id' => $tenant->id,
            'prev_date' => $invoice ? $invoice->curr_date : $request->get('prev_date'),
            'prev_reading' => $prevReading,
            'curr_date' => $request->get('curr_date'),
            'curr_reading' => $currReading,
            'electricity_rate' => config('rates.electricity'),
            'electricity_amount' => $electricityAmount,
            'total_heads' => $tenant->total_heads,
            'water_price' => config('rates.water'),
            'water_amount' => $waterAmount,
            'rent_amount' => $tenant->rent_amount,
            'internet_amount' => $internetAmount,
            'total' => $total,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'data' => $invoice,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return response()->json([
            'success' => true,
            'data' => $invoice,
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
