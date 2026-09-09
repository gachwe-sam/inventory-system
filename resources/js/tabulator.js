import { TabulatorFull as Tabulator } from 'tabulator-tables';
import 'tabulator-tables/dist/css/tabulator_bootstrap5.min.css';

function initDataTable(el) {
    const columns = JSON.parse(el.dataset.columns);

    el.tabulator = new Tabulator(el, {
        ajaxURL: el.dataset.ajaxUrl,
        pagination: true,
        paginationMode: 'remote',
        paginationSize: 15,
        paginationSizeSelector: [15, 25, 50, 100],
        ajaxResponse: (url, params, response) => response,
        layout: 'fitColumns',
        placeholder: 'No records found.',
        columns,
    });
}


document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-tabulator]').forEach(initDataTable);
});
