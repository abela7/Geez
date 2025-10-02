/**
 * Staff Tasks Module - Create & Edit Pages
 * Handles form interactions and UI enhancements
 */

/**
 * Toggle template name field visibility based on checkbox
 * @param {HTMLInputElement} checkbox - The template checkbox element
 */
function toggleTemplateField(checkbox) {
    const templateGroup = document.getElementById('template-name-group');
    if (templateGroup) {
        templateGroup.style.display = checkbox.checked ? 'block' : 'none';
    }
}

/**
 * Toggle staff assignment section visibility based on checkbox
 * @param {HTMLInputElement} checkbox - The auto-assign checkbox element
 */
function toggleAssignmentSection(checkbox) {
    const assignmentSection = document.getElementById('assignment-section');
    if (assignmentSection) {
        assignmentSection.style.display = checkbox.checked ? 'block' : 'none';
    }
}

/**
 * Add a tag to the tags input field
 * @param {string} tagName - The tag to add
 */
function addTag(tagName) {
    const tagsInput = document.getElementById('tags');
    if (!tagsInput) return;

    const currentTags = tagsInput.value.trim();
    
    // Check if tag already exists
    const existingTags = currentTags ? currentTags.split(',').map(tag => tag.trim()) : [];
    
    if (existingTags.includes(tagName)) {
        return; // Tag already exists, don't add it again
    }
    
    // Add the tag
    if (currentTags) {
        tagsInput.value = currentTags + ', ' + tagName;
    } else {
        tagsInput.value = tagName;
    }
    
    // Focus the input
    tagsInput.focus();
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Only auto-hide success alerts
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease-out';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
    });

    // Add smooth scroll to validation errors
    const firstError = document.querySelector('.form-error');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// Make functions globally available
window.toggleTemplateField = toggleTemplateField;
window.toggleAssignmentSection = toggleAssignmentSection;
window.addTag = addTag;

