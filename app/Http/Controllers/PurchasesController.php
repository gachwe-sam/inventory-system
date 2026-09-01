<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Purchase;

class PurchasesController extends Controller
{
    public function index()
    {
        $purchases = Purchase::orderBy('id', 'desc')->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePurchase($request);

        purchase::create($validated);

        return redirect()->route('purchase.index')
        ->with('success', 'purchase created successfully.');
    }

    public function show(Purchase $purchase)
    {
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {        

        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $this->validatePurchase($request, $purchase);

        $purchase->update($validated);        

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted.');
    }

    private function validatePurchase(Request $request, Purchase $purchase = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('purchases', 'email')->ignore($purchase?->id),
            ],
            'item_id' => 'nullable|integer|exists:items,id',
        ]);
    }
}
