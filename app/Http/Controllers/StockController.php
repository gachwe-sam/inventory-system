<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Branchstock;
use App\Models\Item;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(private StockService $stock)
    {
    }

    public function index(Request $request)
    {
        [$branches, $branch] = $this->resolveBranch($request);

        if (! $branch) {
            return view('stock.index', [
                'branches' => $branches,
                'branch' => null,
                'unstockedItems' => collect(),
            ]);
        }

        $stockedItemIds = Branchstock::where('branch_id', $branch->id)->pluck('item_id');
        $unstockedItems = Item::whereNotIn('id', $stockedItemIds)->orderBy('name')->get();

        return view('stock.index', [
            'branches' => $branches,
            'branch' => $branch,
            'unstockedItems' => $unstockedItems,
        ]);
    }

    public function data(Request $request)
    {
        [, $branch] = $this->resolveBranch($request);

        abort_unless($branch, 404);

        $stockedItems = Branchstock::where('branch_id', $branch->id)
            ->with('item')
            ->paginate($request->integer('size', 15));

        $rows = collect($stockedItems->items())->filter(fn ($row) => $row->item)->values()->map(fn ($row) => [
            'item' => $row->item->name,
            'quantity' => $row->quantity,
            'reorder_level' => $row->reorder_level,
            'actions_html' => view('stock.partials.stocked-actions', ['row' => $row])->render(),
        ]);

        return response()->json(['data' => $rows, 'last_page' => $stockedItems->lastPage()]);
    }

    /**
     * @return array{0: \Illuminate\Support\Collection, 1: ?Branch}
     */
    private function resolveBranch(Request $request): array
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $branches = Branch::whereDoesntHave('children')->orderBy('name')->get();

            $branch = $request->filled('branch_id')
                ? $branches->firstWhere('id', (int) $request->input('branch_id'))
                : $branches->first();

            return [$branches, $branch];
        }

        $branch = $user->branch;
        abort_if(! $branch, 403, 'No branch assigned to your account.');

        return [collect(), $branch];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
        ]);

        $branch = Branch::findOrFail($validated['branch_id']);

        $this->authorize('create', [Branchstock::class, $branch]);

        $item = Item::findOrFail($validated['item_id']);

        Branchstock::firstOrCreate(
            ['branch_id' => $branch->id, 'item_id' => $item->id],
            ['quantity' => 0, 'reorder_level' => $validated['reorder_level'] ?? 0]
        );

        if (! empty($validated['quantity'])) {
            $this->stock->receive($branch, $item, (float) $validated['quantity'], 'purchase', 'Initial stock');
        }

        return redirect()->route('stock.index', ['branch_id' => $branch->id])->with('success', 'Item added to branch stock.');
    }
}
