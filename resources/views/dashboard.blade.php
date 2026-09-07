@extends('layouts.adminlte')
@section('title', 'Dashboard')

@section('content')
<div class="row g-3">
    @foreach ([
        ['label' => 'Categories', 'route' => 'categories.index', 'icon' => 'bi-diagram-3'],
        ['label' => 'Items', 'route' => 'items.index', 'icon' => 'bi-box-seam'],
        ['label' => 'Suppliers', 'route' => 'suppliers.index', 'icon' => 'bi-truck'],
        ['label' => 'Purchases', 'route' => 'purchases.index', 'icon' => 'bi-cart'],
        ['label' => 'Branches', 'route' => 'branches.index', 'icon' => 'bi-diagram-2'],
        ['label' => 'Branch Stock', 'route' => 'stock.index', 'icon' => 'bi-clipboard-data'],
    ] as $tile)
        <div class="col-md-6 col-xl-4">
            <a href="{{ route($tile['route']) }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <i class="bi {{ $tile['icon'] }} fs-2 text-primary"></i>
                        <span class="fs-5 text-body">{{ $tile['label'] }}</span>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
@endsection
