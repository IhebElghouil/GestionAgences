// Add event listeners to all filter inputs
document.querySelectorAll('.column-filter').forEach(input => {
    input.addEventListener('input', filterTable);
});

// Filter function with debounce
let filterTimeout;
function filterTable() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(doFiltering, 300);
}

function doFiltering() {
    const table = document.getElementById("myTable");
    const rows = table.querySelectorAll('tbody tr');
    const filters = Array.from(document.querySelectorAll('.column-filter'))
        .filter(input => input.value.trim() !== '')
        .map(input => ({
            columnIndex: parseInt(input.dataset.column),
            value: normalizeArabic(input.value.trim())
        }));

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let shouldDisplay = filters.length === 0;
        
        if (filters.length > 0) {
            shouldDisplay = true;
            for (const filter of filters) {
                const cell = cells[filter.columnIndex];
                if (cell) {
                    const cellText = normalizeArabic(cell.textContent || cell.innerText);
                    if (!cellText.includes(filter.value)) {
                        shouldDisplay = false;
                        break;
                    }
                }
            }
        }
        
        row.style.display = shouldDisplay ? "" : "none";
    });
}

// Normalize Arabic text for better searching
function normalizeArabic(text) {
    return text
        .normalize('NFKD')
        .replace(/[\u064B-\u065F]/g, '')
        .toUpperCase();
}

// Clear all filters
function clearFilters() {
    document.querySelectorAll('.column-filter').forEach(input => {
        input.value = '';
    });
    filterTable();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    filterTable();
});