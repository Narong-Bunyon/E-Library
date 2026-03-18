<script>
// Create Book Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createBookForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Book created successfully!');
                form.reset();
                bootstrap.Modal.getInstance(document.getElementById('createBookModal')).hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating book: ' + error.message);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
});

// Open modal function
function openCreateModal() {
    document.getElementById('createBookForm').reset();
    new bootstrap.Modal(document.getElementById('createBookModal')).show();
}
</script>
