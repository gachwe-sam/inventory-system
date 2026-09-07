<?php

namespace App\Providers;

use App\Models\Branchstock;
use App\Policies\BranchstockPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Admins bypass every $this->authorize() / @can check automatically.
        Gate::before(fn ($user, $ability) => $user->hasRole('admin') ? true : null);
        Gate::policy(Branchstock::class, BranchstockPolicy::class);

        // Fix #1: Paginator is a CLASS, called statically — no parentheses
        // after the class name, no lowercase "paginator()" helper exists.
        Paginator::useBootstrapFive();

        // Feeds the sidebar menu to layouts.adminlte automatically —
        // every page using that layout gets $menu without a controller
        // ever passing it.
        View::composer('layouts.adminlte', function ($view) {
            $view->with('menu', [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard', 'icon' => 'bi-speedometer'],
                ['label' => 'Categories', 'route' => 'categories.index', 'pattern' => 'categories.*', 'icon' => 'bi-diagram-3'],
                ['label' => 'Items', 'route' => 'items.index', 'pattern' => 'items.*', 'icon' => 'bi-box-seam'],
                ['label' => 'Suppliers', 'route' => 'suppliers.index', 'pattern' => 'suppliers.*', 'icon' => 'bi-truck'],
                ['label' => 'Purchases', 'route' => 'purchases.index', 'pattern' => 'purchases.*', 'icon' => 'bi-cart'],
                ['label' => 'Branches', 'route' => 'branches.index', 'pattern' => 'branches.*', 'icon' => 'bi-diagram-2'],
                ['label' => 'Adjust Stock', 'route' => 'stock.index', 'pattern' => 'stock.*', 'icon' => 'bi-clipboard-data'],
                ['label' => 'Staff Permissions', 'route' => 'manager.staff.index', 'pattern' => 'manager.*', 'icon' => 'bi-people'],
                ['label' => 'Users', 'route' => 'users.index', 'pattern' => 'users.*', 'icon' => 'bi-person-gear'],
            ]);
        });
    }
}