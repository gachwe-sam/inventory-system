<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','location', 'parent_id','address','manager_id','phone'];

    protected static function booted(): void
    {
        
        static::deleting(function (Branch $branch) {
            if ($branch->isForceDeleting()) {
                return;
            }

            $descendantIds = $branch->descendantIds();

            if ($descendantIds->isNotEmpty()) {
                static::whereIn('id', $descendantIds)->delete();
            }
        });
    }

    
    public function parent()
    {
        return $this->belongsTo(Branch::class, 'parent_id');
    }

    
    public function children()
    {
        return $this->hasMany(Branch::class, 'parent_id');
    }

    
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function stock()
    {
        return $this->hasMany(Branchstock::class);
    }

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function isLeaf(): bool
    {
        return ! $this->children()->exists();
    }

      public function ancestors(): Collection
    {
        $chain = collect();
        $node = $this->parent;

        while ($node) {
            $chain->prepend($node);
            $node = $node->parent;
        }

        return $chain;
    }

    
    public function descendantIds(): Collection
    {
        return static::descendantIdsOf($this->id);
    }

    
    public function descendantAndSelfIds(): Collection
    {
        return $this->descendantIds()->push($this->id);
    }

    
    public static function descendantIdsOf(int $branchId): Collection
    {
        $rows = DB::select(<<<'SQL'
            WITH RECURSIVE descendants AS (
                SELECT id, parent_id
                FROM branches
                WHERE parent_id = ? AND deleted_at IS NULL

                UNION ALL

                SELECT c.id, c.parent_id
                FROM branches c
                INNER JOIN descendants d ON c.parent_id = d.id
                WHERE c.deleted_at IS NULL
            )
            SELECT id FROM descendants
        SQL, [$branchId]);

        return collect($rows)->pluck('id');
    }
    
    public static function tree()
    {
        return static::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('name')
            ->get();
    }

    
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive')->orderBy('name');
    }

    public function scopesearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $term . '%');
    } 


    public static function treeOrderedIds(): Collection
    {
        $rows = DB::select(<<<'SQL'
            WITH RECURSIVE ordered AS (
                SELECT id, CAST(name AS CHAR(2000)) AS sort_path
                FROM branches
                WHERE parent_id IS NULL AND deleted_at IS NULL

                UNION ALL

                SELECT c.id, CONCAT(o.sort_path, CHAR(1), c.name)
                FROM branches c
                INNER JOIN ordered o ON c.parent_id = o.id
                WHERE c.deleted_at IS NULL
            )
            SELECT id FROM ordered ORDER BY sort_path
        SQL);

        return collect($rows)->pluck('id');
    }

}
