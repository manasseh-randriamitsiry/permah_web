// Import admin styles
import './styles/admin.scss';

// Custom admin JavaScript
document.addEventListener('DOMContentLoaded', () => {
    // Initialize any admin-specific JavaScript here
    
    // Example: Add confirmation for delete actions
    const deleteButtons = document.querySelectorAll('[data-action="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Example: File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = this.parentElement.querySelector('.file-preview');
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.innerHTML = '';
                    if (this.files[0].type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '200px';
                        preview.appendChild(img);
                    } else {
                        preview.textContent = this.files[0].name;
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});
