<script>
// Standardized Form Handling for Book Management
class BookFormHandler {
    constructor() {
        this.init();
    }

    init() {
        this.setupCreateForm();
        this.setupEditForm();
        this.setupFilePreviews();
        this.setupFormValidation();
    }

    // CREATE FORM HANDLING
    setupCreateForm() {
        const createForm = document.getElementById('createBookForm');
        if (createForm) {
            createForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleCreateSubmit(createForm);
            });
        }
    }

    handleCreateSubmit(form) {
        const submitBtn = document.getElementById('createSubmitBtn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showAlert('success', 'Book created successfully!');
                form.reset();
                this.clearPreviews();
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createBookModal'));
                if (modal) modal.hide();
                
                // Reload page after delay
                setTimeout(() => location.reload(), 1500);
            } else {
                this.showAlert('error', 'Error creating book: ' + (data.message || 'Unknown error'));
                if (data.errors) {
                    this.showValidationErrors(form, data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showAlert('error', 'Error creating book');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    // EDIT FORM HANDLING
    setupEditForm() {
        const editForm = document.getElementById('editBookForm');
        if (editForm) {
            editForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleEditSubmit(editForm);
            });
        }
    }

    handleEditSubmit(form) {
        const submitBtn = document.getElementById('editSubmitBtn');
        const originalText = submitBtn.innerHTML;
        const bookId = document.getElementById('editBookId').value;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';

        const formData = new FormData(form);

        fetch(`/author/books/${bookId}`, {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showAlert('success', 'Book updated successfully!');
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editBookModal'));
                if (modal) modal.hide();
                
                // Reload page after delay
                setTimeout(() => location.reload(), 1500);
            } else {
                this.showAlert('error', 'Error updating book: ' + (data.message || 'Unknown error'));
                if (data.errors) {
                    this.showValidationErrors(form, data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showAlert('error', 'Error updating book');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    // POPULATE EDIT FORM
    populateEditForm(book) {
        // Set book ID
        document.getElementById('editBookId').value = book.id;
        
        // Populate form fields
        document.getElementById('editTitle').value = book.title || '';
        document.getElementById('editIsbn').value = book.isbn || '';
        document.getElementById('editCategory').value = book.category_id || '';
        document.getElementById('editLanguage').value = book.language || '';
        document.getElementById('editPublisher').value = book.publisher || '';
        document.getElementById('editPublishedDate').value = book.published_date || '';
        document.getElementById('editPages').value = book.pages || '';
        document.getElementById('editAccessLevel').value = book.access_level || '0';
        document.getElementById('editDescription').value = book.description || '';
        document.getElementById('editExcerpt').value = book.excerpt || '';
        document.getElementById('editStatus').value = book.status || '0';
        
        // Show current cover image if exists
        if (book.cover_image) {
            const currentCover = document.getElementById('editCurrentCover');
            const coverImg = currentCover.querySelector('img');
            const coverSrc = book.cover_image.startsWith('http') ? book.cover_image : `/storage/${book.cover_image}`;
            coverImg.src = coverSrc;
            currentCover.style.display = 'block';
        }
        
        // Show current file info if exists
        if (book.file_path) {
            const currentFile = document.getElementById('editCurrentFile');
            const fileBadge = currentFile.querySelector('span');
            const fileName = book.file_path.split('/').pop();
            fileBadge.textContent = fileName;
            currentFile.style.display = 'block';
        }
        
        // Set form action
        const form = document.getElementById('editBookForm');
        form.action = `/author/books/${book.id}`;
    }

    // FILE PREVIEWS
    setupFilePreviews() {
        // Create form cover preview
        const createCoverInput = document.getElementById('createCoverImage');
        if (createCoverInput) {
            createCoverInput.addEventListener('change', (e) => {
                this.showImagePreview(e.target, 'createCoverPreview');
            });
        }

        // Edit form cover preview
        const editCoverInput = document.getElementById('editCoverImage');
        if (editCoverInput) {
            editCoverInput.addEventListener('change', (e) => {
                this.showImagePreview(e.target, 'editCoverPreview');
            });
        }

        // Create form file info
        const createFileInput = document.getElementById('createFile');
        if (createFileInput) {
            createFileInput.addEventListener('change', (e) => {
                this.showFileInfo(e.target, 'createFileInfo');
            });
        }

        // Edit form file info
        const editFileInput = document.getElementById('editFile');
        if (editFileInput) {
            editFileInput.addEventListener('change', (e) => {
                this.showFileInfo(e.target, 'editFileInfo');
            });
        }
    }

    showImagePreview(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.querySelector('img').src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }

    showFileInfo(input, infoId) {
        const info = document.getElementById(infoId);
        const file = input.files[0];
        
        if (file) {
            const size = this.formatFileSize(file.size);
            info.querySelector('small').textContent = `${file.name} (${size})`;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    clearPreviews() {
        const previews = document.querySelectorAll('.preview-container, .file-info');
        previews.forEach(preview => {
            preview.style.display = 'none';
        });
    }

    // FORM VALIDATION
    setupFormValidation() {
        const forms = ['createBookForm', 'editBookForm'];
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        this.validateField(input);
                    });
                    input.addEventListener('input', () => {
                        if (input.classList.contains('is-invalid')) {
                            this.validateField(input);
                        }
                    });
                });
            }
        });
    }

    validateField(field) {
        const isValid = field.checkValidity();
        
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }
        
        return isValid;
    }

    showValidationErrors(form, errors) {
        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });

        // Show new errors
        Object.keys(errors).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('is-invalid');
                const feedback = field.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = Array.isArray(errors[fieldName]) ? errors[fieldName][0] : errors[fieldName];
                }
            }
        });
    }

    // ALERT SYSTEM
    showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        
        const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="alert-icon me-2">
                    <i class="fas fa-${icon}"></i>
                </div>
                <div class="alert-message flex-grow-1">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 5000);
    }
}

// Initialize the form handler when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.bookFormHandler = new BookFormHandler();
});

// Global functions for backward compatibility
function openEditModal(bookId) {
    fetch(`/author/books/${bookId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.bookFormHandler.populateEditForm(data.book);
                const modal = new bootstrap.Modal(document.getElementById('editBookModal'));
                modal.show();
            } else {
                window.bookFormHandler.showAlert('error', 'Error loading book details for editing');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.bookFormHandler.showAlert('error', 'Error loading book details for editing');
        });
}

function openCreateModal() {
    document.getElementById('createBookForm').reset();
    window.bookFormHandler.clearPreviews();
    const modal = new bootstrap.Modal(document.getElementById('createBookModal'));
    modal.show();
}
</script>
