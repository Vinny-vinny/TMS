export const sortTasks = (sortBy, sortOrder, field) => {
    if (sortBy.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = field;
        sortOrder.value = 'asc';
    }
};
export const getSortIcon = (sortBy, sortOrder, field) => {
    if (sortBy === field) {
        return sortOrder === 'asc' ? 'bi bi-arrow-up' : 'bi bi-arrow-down';
    }
    return 'bi bi-arrow-down-up';
};
