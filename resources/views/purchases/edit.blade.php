

<script>
    let rowIndex = 0;
    const template = document.getElementById('item-row-template');
    const rowsBody = document.getElementById('item-rows');
    const existingItems = @json($purchase->items->map(fn ($item) => [
        'item_id' => $item->id,
        'quantity' => $item->pivot->quantity,
        'unit_price' => $item->pivot->unit_price,
    ]));

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
