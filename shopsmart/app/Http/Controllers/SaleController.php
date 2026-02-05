<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'user'])->latest()->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function invoices()
    {
        $sales = Sale::where('status', 'completed')
            ->with(['customer', 'user'])
            ->latest()
            ->paginate(20);
        return view('sales.invoices', compact('sales'));
    }

    public function returns()
    {
        $sales = Sale::where('status', 'refunded')
            ->with(['customer', 'user'])
            ->latest()
            ->paginate(20);
        return view('sales.returns', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
