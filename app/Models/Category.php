<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'parent_id'];

    protected static function booted(): void
    {
        
        static::deleting(function (Category $category) {
            if ($category->isForceDeleting()) {
                return;
            }

            $descendantIds = $category->descendantIds();

            if ($descendantIds->isNotEmpty()) {
                static::whereIn('id', $descendantIds)->delete();
            }
        });
    }

    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    
    public function items()
    {
        return $this->hasMany(Item::class);
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

    
    public static function descendantIdsOf(int $categoryId): Collection
    {
        $rows = DB::select(<<<'SQL'
            WITH RECURSIVE descendants AS (
                SELECT id, parent_id
                FROM categories
                WHERE parent_id = ? AND deleted_at IS NULL

                UNION ALL

                SELECT c.id, c.parent_id
                FROM categories c
                INNER JOIN descendants d ON c.parent_id = d.id
                WHERE c.deleted_at IS NULL
            )
            SELECT id FROM descendants
        SQL, [$categoryId]);

        return collect($rows)->pluck('id');
    }

    
    public function itemsIncludingDescendants()
    {
        return Item::whereIn('category_id', $this->descendantAndSelfIds());
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
                FROM categories
                WHERE parent_id IS NULL AND deleted_at IS NULL

                UNION ALL

                SELECT c.id, CONCAT(o.sort_path, CHAR(1), c.name)
                FROM categories c
                INNER JOIN ordered o ON c.parent_id = o.id
                WHERE c.deleted_at IS NULL
            )
            SELECT id FROM ordered ORDER BY sort_path
        SQL);

        return collect($rows)->pluck('id');
    }

}
