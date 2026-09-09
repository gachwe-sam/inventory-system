@extends('layouts.adminlte')
@section('title', 'Edit Purchase')

@php
    $existingItems = $purchase->items->map(fn ($item) => [
        'item_id' => $item->id,
        'quantity' => $item->pivot->quantity,
        'unit_price' => $item->pivot->unit_price,
    ]);
@endphp

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('purchases.update', $purchase) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Purchase Name / Reference</label>
                <input type="text" name="name" value="{{ old('name', $purchase->name) }}" class="form-control" required>
            </div>

            <h5>Items Purchased</h5>
            <table class="table">
                <thead><tr><th>Item</th><th>Quantity</th><th>Unit Price</th><th></th></tr></thead>
                <tbody id="item-rows"></tbody>
            </table>
            <button type="button" id="add-row" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus"></i> Add Item</button>

            <template id="item-row-template">
                <tr>
                    <td>
                        <select name="items[__INDEX__][item_id]" class="form-select" required>
                            <option value="">-- Select item --</option>
                            @foreach ($allItems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="items[__INDEX__][quantity]" class="form-control" min="1" required></td>
                    <td><input type="number" step="0.01" name="items[__INDEX__][unit_price]" class="form-control" min="0"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="bi bi-trash"></i></button></td>
                </tr>
            </template>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Save Purchase</button>
            </div>
        </form>
    </div>
</div>

<script>
    let rowIndex = 0;
    const template = document.getElementById('item-row-template');
    const rowsBody = document.getElementById('item-rows');
    const existingItems = @json($existingItems);

    function addRow(values = {}) {
        const clone = template.content.cloneNode(true);
        clone.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace('__INDEX__', rowIndex);
        });
        if (values.item_id) clone.querySelector('select').value = values.item_id;
        if (values.quantity) clone.querySelectorAll('input')[0].value = values.quantity;
        if (values.unit_price !== undefined && values.unit_price !== null) clone.querySelectorAll('input')[1].value = values.unit_price;
        rowsBody.appendChild(clone);
        rowIndex++;
    }

    document.getElementById('add-row').addEventListener('click', () => addRow());
    rowsBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) e.target.closest('tr').remove();
    });

    if (existingItems.length) {
        existingItems.forEach(addRow);
    } else {
        addRow();
    }
</script>
@endsection
