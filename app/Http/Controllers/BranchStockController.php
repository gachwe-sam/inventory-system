<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Models\Branch;
use App\Models\Branchstock as BranchStock;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;

class BranchStockController extends Controller
{
    public function __construct(private StockService $stock)
    {
    }
    public function edit(BranchStock $branchStock)
    {
        $this->authorize('update', $branchStock);

        return view('branch-stock.edit', ['stock' => $branchStock, 'back' => $this->backUrl($branchStock)]);
    }
    public function update(Request $request, BranchStock $branchStock)
    {
        $this->authorize('update', $branchStock);

        $validated = $request->validate([
            'reorder_level' => 'required|numeric|min:0',
        ]);
        $branchStock->update($validated);

        return $this->redirectAfterAction($branchStock)->with('success', 'Reorder level updated.');
    }
    public function receiveForm(BranchStock $branchStock)
    {
        $this->authorize('receive', $branchStock);

        return view('branch-stock.receive', ['stock' => $branchStock, 'back' => $this->backUrl($branchStock)]);
    }
    public function receive(Request $request, BranchStock $branchStock)
    {
        $this->authorize('receive', $branchStock);

        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);

        $this->stock->receive(
            $branchStock->branch,
            $branchStock->item,
            $validated['quantity'],
            'purchase',
            $validated['notes'] ?? null,
        );

        return $this->redirectAfterAction($branchStock)->with('success', 'Stock received.');
    }
    public function issueForm(BranchStock $branchStock)
    {
        $this->authorize('issue', $branchStock);

        return view('branch-stock.issue', ['stock' => $branchStock, 'back' => $this->backUrl($branchStock)]);
    }
    public function issue(Request $request, BranchStock $branchStock)
    {
        $this->authorize('issue', $branchStock);

        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);
        try {
            $this->stock->issue(
                $branchStock->branch,
                $branchStock->item,
                $validated['quantity'],
                'sale',
                $validated['notes'] ?? null
            );
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }
        return $this->redirectAfterAction($branchStock)->with('success', 'Stock issued.');
    }
    public function transferForm(BranchStock $branchStock)
    {
        $this->authorize('transfer', $branchStock);

        $branches = Branch::where('id', '!=', $branchStock->branch_id)->orderBy('name')->get();
        return view('branch-stock.transfer', ['stock' => $branchStock, 'branches' => $branches, 'back' => $this->backUrl($branchStock)]);
    }
    public function transfer(Request $request, BranchStock $branchStock)
    {
        $this->authorize('transfer', $branchStock);

        $validated = $request->validate([
            'to_branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);

        $toBranch = Branch::findOrFail($validated['to_branch_id']);

        try {
            $this->stock->transfer(
                $branchStock->branch,
                $toBranch,
                $branchStock->item,
                $validated['quantity'],
                $validated['notes'] ?? null
            );
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }
        return $this->redirectAfterAction($branchStock)->with('success', 'Stock transferred.');
    }
    public function history(BranchStock $branchStock)
    {
        $this->authorize('view', $branchStock);

        $movements = StockMovement::where('branch_id', $branchStock->branch_id)
            ->where('item_id', $branchStock->item_id)
            ->with('user')
            ->latest('created_at')
            ->paginate(10);

        return view('branch-stock.history', ['stock' => $branchStock, 'movements' => $movements, 'back' => $this->backUrl($branchStock)]);
    }

    private function redirectAfterAction(BranchStock $stock)
    {
        return auth()->user()->hasRole('admin')
            ? redirect()->route('branches.show', $stock->branch_id)
            : redirect()->route('stock.index', ['branch_id' => $stock->branch_id]);
    }

    private function backUrl(BranchStock $stock): string
    {
        return auth()->user()->hasRole('admin')
            ? route('branches.show', $stock->branch_id)
            : route('stock.index', ['branch_id' => $stock->branch_id]);
    }
}
