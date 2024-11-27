document.addEventListener('DOMContentLoaded', function () {
    // Configuración inicial de la tabla
    const tableConfig = {
        searchInput: document.getElementById('table-search'),
        productsTable: document.querySelector('table'),
        sortButtons: document.querySelectorAll('[data-sort]'),
        sortState: {
            column: null,
            isAsc: true
        }
    };

    function initializeTableFeatures() {
        // Búsqueda en tiempo real
        if (tableConfig.searchInput) {
            tableConfig.searchInput.addEventListener('input', debounce(function (e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = tableConfig.productsTable.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const id = row.querySelector('td[data-id]')?.textContent || '';
                    const nombre = row.querySelector('td[data-nombre]')?.textContent || '';
                    const text = `${id} ${nombre}`.toLowerCase();

                    row.classList.toggle('hidden', !text.includes(searchTerm));
                });

                updateEmptyState();
            }, 300));
        }

        // Ordenamiento de columnas
        tableConfig.sortButtons.forEach(button => {
            button.addEventListener('click', function () {
                const column = this.dataset.sort;
                const isAsc = tableConfig.sortState.column === column ? !tableConfig.sortState.isAsc :
                    true;

                // Actualizar estado
                tableConfig.sortState.column = column;
                tableConfig.sortState.isAsc = isAsc;

                sortTable(column, isAsc);
                updateSortIcons();
            });
        });

        // Inicializar estado vacío
        updateEmptyState();
    }

    function sortTable(column, isAsc) {
        const tbody = tableConfig.productsTable.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-state)'));

        rows.sort((a, b) => {
            const aVal = a.querySelector(`td[data-${column}]`)?.textContent || '';
            const bVal = b.querySelector(`td[data-${column}]`)?.textContent || '';

            return isAsc ?
                aVal.localeCompare(bVal, undefined, {
                    numeric: true
                }) :
                bVal.localeCompare(aVal, undefined, {
                    numeric: true
                });
        });

        tbody.append(...rows);
    }

    function updateSortIcons() {
        tableConfig.sortButtons.forEach(button => {
            const column = button.dataset.sort;
            const isCurrentColumn = column === tableConfig.sortState.column;
            const ascIcon = button.querySelector('.sort-asc');
            const descIcon = button.querySelector('.sort-desc');

            if (isCurrentColumn) {
                ascIcon.classList.toggle('hidden', !tableConfig.sortState.isAsc);
                descIcon.classList.toggle('hidden', tableConfig.sortState.isAsc);
            } else {
                ascIcon.classList.add('hidden');
                descIcon.classList.add('hidden');
            }
        });
    }

    function updateEmptyState() {
        const tbody = tableConfig.productsTable.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr:not(.empty-state)');
        const visibleRows = Array.from(rows).filter(row => !row.classList.contains('hidden'));
        let emptyState = tbody.querySelector('.empty-state');

        if (visibleRows.length === 0) {
            if (!emptyState) {
                emptyState = document.createElement('tr');
                emptyState.className = 'empty-state';
                emptyState.innerHTML = `
       <td colspan="3" class="px-6 py-8 text-center">
           <div class="flex flex-col items-center">
               <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                         d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>
               <span class="mt-2 text-gray-500">No se encontraron productos</span>
           </div>
       </td>
   `;
            }
            tbody.appendChild(emptyState);
        } else if (emptyState) {
            emptyState.remove();
        }
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Inicializar características de la tabla
    document.addEventListener('DOMContentLoaded', initializeTableFeatures);
});