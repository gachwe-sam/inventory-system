<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{

    public function index(Request $request)
{
    $orderedIds = Branch::treeOrderedIds();

    $matching = Branch::with('parent')
        ->search($request->input('search'))
        ->whereIn('id', $orderedIds)
        ->get()
        ->sortBy(fn ($branch) => $orderedIds->search($branch->id))
        ->values();

    $perPage = 14;
    $page = $request->integer('page', 1);

    $branches = new LengthAwarePaginator(
        $matching->forPage($page, $perPage)->values(),
        $matching->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    $branchOptions = $this->parentOptions();

    return view('branches.index', compact('branches', 'branchOptions'));
}

    public function create()
    {
        $parentOptions = $this->parentOptions();

        return view('branches.create', compact('parentOptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateBranch($request);

        
        $trashed = Branch::onlyTrashed()
            ->where('parent_id', $validated['parent_id'])
            ->where('name', $validated['name'])
            ->first();

        if ($trashed) {
            $trashed->restore();
        } else {
            Branch::create($validated);
        }

        return redirect()->route('branches.index')->with('success', 'Branch created.');
    }
   
    public function show(Branch $branch)
    {
        $subBranches = $branch->children()->orderBy('name')->get();

        if ($subBranches->isNotEmpty()) {
            return view('branches.show', [
                'branch' => $branch,
                'subBranches' => $subBranches,
                'stock' => null,
            ]);
        }

        $stock = $branch->stock()->with('item')->get();

        return view('branches.show', [
            'branch' => $branch,
            'subBranches' => null,
            'stock' => $stock,
        ]);
    }

    public function edit(Branch $branch)
    {
        $parentOptions = $this->parentOptions($branch);

        return view('branches.edit', compact('branch', 'parentOptions'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $this->validateBranch($request, $branch);
        
        Branch::onlyTrashed()
            ->where('parent_id', $validated['parent_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $branch->id)
            ->forceDelete();

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }

    private function validateBranch(Request $request, ?Branch $branch = null): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
               
                Rule::unique('branches', 'name')
                    ->where(fn ($query) => $query->where('parent_id', $request->input('parent_id')))
                    ->whereNull('deleted_at')
                    ->ignore($branch?->id),
            ],
            'parent_id' => 'nullable|exists:branches,id',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        if ($branch && $validated['parent_id']) {
            $this->guardAgainstCycle($branch, (int) $validated['parent_id']);
        }

        return $validated;
    }

    
    private function guardAgainstCycle(Branch $branch, int $newParentId): void
    {
        if ($newParentId === $branch->id) {
            abort(422, 'A Branch cannot be its own parent.');
        }

        if ($branch->descendantIds()->contains($newParentId)) {
            abort(422, 'A Branch cannot be moved under one of its own subBranches.');
        }
    }

   
    private function parentOptions(?Branch $branch = null)
    {
        $query = Branch::with('parent')->orderBy('name');

        if ($branch) {
            $excluded = $branch->descendantAndSelfIds();
            $query->whereNotIn('id', $excluded);
        }

        return $query->get();
    }

    private function filteredBranch(Request $request)
    {
        return Branch::with('parent')
            ->orderBy('name')
            ->search($request->input('search'));
    }
}