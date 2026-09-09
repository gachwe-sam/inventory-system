function debounce(fn, delay) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

function wireLiveSearch(input) {
    const table = document.getElementById(input.dataset.target)?.tabulator;
    if (!table) return;

    const runSearch = debounce(() => {
        table.setData(undefined, { search: input.value });
    }, 300);

    input.addEventListener('input', runSearch);
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-live-search]').forEach(wireLiveSearch);
});
