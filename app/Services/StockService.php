<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Branch;
use App\Models\Branchstock;
use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockService
{
    public function balance(Branch $branch, Item $item)
    {
        return (float) (Branchstock::where('branch_id', $branch->id)
            ->where('item_id', $item->id)
            ->value('quantity') ?? 0);
    }

    public function receive(Branch $branch, Item $item, float $qty, string $type, ?string $notes = null, ?string $referenceId = null): StockMovement
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero');
        }

        return DB::transaction(function () use ($branch, $item, $qty, $type, $notes, $referenceId) {
            $stock = Branchstock::firstOrCreate(
                ['branch_id' => $branch->id, 'item_id' => $item->id],
                ['quantity' => 0, 'reorder_level' => 0]
            );
            $stock = Branchstock::where('id', $stock->id)->lockForUpdate()->first();

            $movement = StockMovement::create([
                'branch_id' => $branch->id,
                'item_id' => $item->id,
                'quantity_change' => $qty,
                'type' => $type,
                'notes' => $notes,
                'reference_id' => $referenceId,
                'user_id' => auth()->id(),
            ]);

            $stock->increment('quantity', $qty);
            return $movement;
        });
    }

    public function issue(Branch $branch, Item $item, float $qty, string $type, ?string $notes = null, ?string $referenceId = null): StockMovement
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero');
        }

        return DB::transaction(function () use ($branch, $item, $qty, $type, $notes, $referenceId) {
            $stock = Branchstock::where('branch_id', $branch->id)
                ->where('item_id', $item->id)
                ->lockForUpdate()
                ->first();

            if (!$stock || $stock->quantity < $qty) {
                throw new InsufficientStockException(
                    "Only " . ($stock->quantity ?? 0) . " unit(s) of \"{$item->name}\" available at \"{$branch->name}\"."
                );
            }

            $movement = StockMovement::create([
                'branch_id' => $branch->id,
                'item_id' => $item->id,
                'quantity_change' => -$qty,
                'type' => $type,
                'notes' => $notes,
                'reference_id' => $referenceId,
                'user_id' => auth()->id(),
            ]);

            $stock->decrement('quantity', $qty);
            return $movement;
        });
    }

    public function transfer(Branch $from, Branch $to, Item $item, float $qty, ?string $notes = null, ?string $referenceId = null): array
    {
        if ($from->id === $to->id) {
            throw new \InvalidArgumentException('source and destination branch must differ');
        }

        return DB::transaction(function () use ($from, $to, $item, $qty, $notes, $referenceId) {
            $referenceId = $referenceId ?? (string) Str::uuid();
            $out = $this->issue($from, $item, $qty, 'transfer_out', $notes, $referenceId);
            $in = $this->receive($to, $item, $qty, 'transfer_in', $notes, $referenceId);

            return [$out, $in];
        });
    }

    public function adjust(Branch $branch, Item $item, float $countedQuantity, ?string $notes = null): ?StockMovement
    {
        $delta = $countedQuantity - $this->balance($branch, $item);
        if ($delta > 0) {
            return $this->receive($branch, $item, $delta, 'adjustment', $notes);
        }
        if ($delta < 0) {
            return $this->issue($branch, $item, abs($delta), 'adjustment', $notes);
        }
        return null;
    }
}
