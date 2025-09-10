/* ===================================
   Inventory Recipes - Interactive Features
   =================================== */

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inventory Recipes initialized');
    
    // Initialize any interactive features here
    initializeRecipes();
});

function initializeRecipes() {
    // Add any initialization logic here
    console.log('Recipe features initialized');
}

// Recipe Management Functions
function openRecipeModal() {
    // TODO: Open recipe creation modal
    console.log('Opening recipe modal...');
    alert('Recipe creation modal would open here. This is a UI demo - backend functionality will be added later.');
}

function viewRecipe(recipeId) {
    // TODO: Open recipe view modal or navigate to recipe detail page
    console.log('Viewing recipe:', recipeId);
    alert(`View recipe ${recipeId} - Backend functionality will be added later.`);
}

function editRecipe(recipeId) {
    // TODO: Open recipe edit modal or navigate to edit page
    console.log('Editing recipe:', recipeId);
    alert(`Edit recipe ${recipeId} - Backend functionality will be added later.`);
}

function duplicateRecipe(recipeId) {
    // TODO: Confirm and duplicate recipe
    if (confirm('Are you sure you want to duplicate this recipe?')) {
        console.log('Duplicating recipe:', recipeId);
        alert(`Duplicate recipe ${recipeId} - Backend functionality will be added later.`);
    }
}

function deleteRecipe(recipeId) {
    // TODO: Confirm and delete recipe
    if (confirm('Are you sure you want to delete this recipe? This action cannot be undone.')) {
        console.log('Deleting recipe:', recipeId);
        alert(`Delete recipe ${recipeId} - Backend functionality will be added later.`);
    }
}

function calculateRecipeCosts(recipeId) {
    // TODO: Calculate and update recipe costs
    console.log('Calculating costs for recipe:', recipeId);
    alert(`Calculate costs for recipe ${recipeId} - Backend functionality will be added later.`);
}

// Filter Functions
function applyFilters() {
    const searchInput = document.querySelector('.search-box input');
    const categorySelect = document.querySelector('.filter-group select[onchange="applyFilters()"]');
    const difficultySelect = document.querySelectorAll('.filter-group select[onchange="applyFilters()"]')[1];
    const statusSelect = document.querySelectorAll('.filter-group select[onchange="applyFilters()"]')[2];
    
    const params = new URLSearchParams();
    
    if (searchInput && searchInput.value.trim()) {
        params.set('search', searchInput.value.trim());
    }
    
    if (categorySelect && categorySelect.value !== 'all') {
        params.set('category', categorySelect.value);
    }
    
    if (difficultySelect && difficultySelect.value !== 'all') {
        params.set('difficulty', difficultySelect.value);
    }
    
    if (statusSelect && statusSelect.value !== 'all') {
        params.set('status', statusSelect.value);
    }
    
    // Redirect with filters
    const url = new URL(window.location);
    url.search = params.toString();
    window.location.href = url.toString();
}

function clearFilters() {
    // Clear all filter inputs
    const searchInput = document.querySelector('.search-box input');
    const selects = document.querySelectorAll('.filter-group select');
    
    if (searchInput) {
        searchInput.value = '';
    }
    
    selects.forEach(select => {
        select.value = 'all';
    });
    
    // Redirect without filters
    const url = new URL(window.location);
    url.search = '';
    window.location.href = url.toString();
}

// Sorting Functions
function sortBy(column, direction = 'asc') {
    const params = new URLSearchParams(window.location.search);
    params.set('sort_by', column);
    params.set('sort_direction', direction);
    
    const url = new URL(window.location);
    url.search = params.toString();
    window.location.href = url.toString();
}

// Bulk Actions (for future implementation)
function selectAllRecipes() {
    const checkboxes = document.querySelectorAll('.recipe-checkbox');
    const selectAllCheckbox = document.querySelector('.select-all-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.recipe-checkbox:checked');
    const bulkActionsDiv = document.querySelector('.bulk-actions');
    
    if (bulkActionsDiv) {
        if (checkedBoxes.length > 0) {
            bulkActionsDiv.style.display = 'flex';
            bulkActionsDiv.querySelector('.selected-count').textContent = checkedBoxes.length;
        } else {
            bulkActionsDiv.style.display = 'none';
        }
    }
}

function bulkDeleteRecipes() {
    const checkedBoxes = document.querySelectorAll('.recipe-checkbox:checked');
    const recipeIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (recipeIds.length === 0) {
        alert('Please select recipes to delete.');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${recipeIds.length} recipe(s)? This action cannot be undone.`)) {
        console.log('Bulk deleting recipes:', recipeIds);
        alert(`Bulk delete ${recipeIds.length} recipes - Backend functionality will be added later.`);
    }
}

function bulkUpdateStatus(status) {
    const checkedBoxes = document.querySelectorAll('.recipe-checkbox:checked');
    const recipeIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (recipeIds.length === 0) {
        alert('Please select recipes to update.');
        return;
    }
    
    console.log(`Bulk updating ${recipeIds.length} recipes to status:`, status);
    alert(`Bulk update ${recipeIds.length} recipes to ${status} - Backend functionality will be added later.`);
}

// Export Functions
function exportRecipe(recipeId, format = 'pdf') {
    console.log(`Exporting recipe ${recipeId} as ${format}`);
    alert(`Export recipe ${recipeId} as ${format} - Backend functionality will be added later.`);
}

function printRecipe(recipeId) {
    console.log('Printing recipe:', recipeId);
    alert(`Print recipe ${recipeId} - Backend functionality will be added later.`);
}

// Utility Functions
function showNotification(message, type = 'success') {
    // TODO: Show toast notification
    console.log(`${type.toUpperCase()}: ${message}`);
}

function validateRecipeForm(formData) {
    // TODO: Add form validation logic
    return true;
}

// Export functions for global access
window.openRecipeModal = openRecipeModal;
window.viewRecipe = viewRecipe;
window.editRecipe = editRecipe;
window.duplicateRecipe = duplicateRecipe;
window.deleteRecipe = deleteRecipe;
window.calculateRecipeCosts = calculateRecipeCosts;
window.applyFilters = applyFilters;
window.clearFilters = clearFilters;
window.sortBy = sortBy;
window.selectAllRecipes = selectAllRecipes;
window.updateBulkActions = updateBulkActions;
window.bulkDeleteRecipes = bulkDeleteRecipes;
window.bulkUpdateStatus = bulkUpdateStatus;
window.exportRecipe = exportRecipe;
window.printRecipe = printRecipe;
