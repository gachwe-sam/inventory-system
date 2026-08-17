<?php

namespace App\Models;

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
        // Soft-deleting a category should also soft-delete every category
        // nested underneath it, so a "deleted" branch doesn't keep showing
        // up as a dangling child in the tree.
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

    /**
     * The category this one is nested under. Null for a top-level category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Direct children only (one level down), e.g. Beverages -> [Cold Beverage, Hot Beverage].
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Items filed directly under this exact category.
     * Does NOT include items filed under a descendant category — see itemsIncludingDescendants().
     */
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

    /**
     * IDs of every category nested under this one, at any depth, NOT including this category itself.
     *
     * Uses a recursive CTE (WITH RECURSIVE) so the whole subtree is fetched in a single query
     * regardless of how deep the nesting goes, rather than walking level-by-level in PHP.
     * Requires MySQL 8+/MariaDB 10.2+/Postgres/SQLite 3.8.3+ (this app runs MySQL 8.0.46).
     */
    public function descendantIds(): Collection
    {
        return static::descendantIdsOf($this->id);
    }

    /**
     * Same as descendantIds(), plus the category's own ID.
     * This is the ID list you filter items by: "Beverages or anything under Beverages".
     */
    public function descendantAndSelfIds(): Collection
    {
        return $this->descendantIds()->push($this->id);
    }

    /**
     * Recursive-CTE lookup of every descendant ID of a given category ID.
     * Static so it can be reused without instantiating a model (e.g. from a route-model-bound ID).
     */
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

    /**
     * All items belonging to this category OR any of its descendants at any depth.
     * This is the "show me all Beverages items" query: category_id IN (Beverages, Cold Beverage, Hot Beverage, ...).
     */
    public function itemsIncludingDescendants()
    {
        return Item::whereIn('category_id', $this->descendantAndSelfIds());
    }

    /**
     * Root categories (no parent) with their children eager-loaded, for rendering a tree.
     */
    public static function tree()
    {
        return static::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('name')
            ->get();
    }

    /**
     * Self-referencing eager-load relation used by tree() to pull the whole subtree in one round trip.
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive')->orderBy('name');
    }
}
