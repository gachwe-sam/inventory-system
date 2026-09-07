<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class PurchasesController extends Controller
{
    public function index()
    {
        return view('purchases.index');
    }

    public function data(Request $request)
    {
        $purchases = Purchase::with('items')
            ->orderBy('id', 'desc')
            ->paginate($request->integer('size', 15));

        $rows = collect($purchases->items())->map(fn (Purchase $purchase) => [
            'name_html' => '<a href="' . route('purchases.show', $purchase) . '">' . e($purchase->name) . '</a>',
            'items' => $purchase->items->pluck('name')->join(', ') ?: '—',
            'actions_html' => view('purchases.partials.actions', compact('purchase'))->render(),
        ]);

        return response()->json(['data' => $rows, 'last_page' => $purchases->lastPage()]);
    }

    public function create()
        {
            $allItems = Item::orderBy('name')->get();
            return view('purchases.create', compact('allItems'));
        }

    public function show(Purchase $purchase)
    {
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $allItems = Item::orderBy('name')->get();
        $purchase->load('items');

        return view('purchases.edit', compact('purchase', 'allItems'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $this->validatePurchase($request, $purchase);

        $purchase->update(['name' => $validated['name']]);

        $purchase->items()->sync(
            collect($validated['items'])->mapWithKeys(fn ($row) => [
                $row['item_id'] => [
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['unit_price'] ?? null,
                ],
            ])
        );

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted.');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePurchase($request);

        $purchase = Purchase::create(['name' => $validated['name']]);

        foreach ($validated['items'] as $row) {
            $purchase->items()->attach($row['item_id'], [
                'quantity' => $row['quantity'],
                'unit_price' => $row['unit_price'] ?? null,
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase created.');
    }

    private function validatePurchase(Request $request, ?Purchase $purchase = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ]);
    }
}
