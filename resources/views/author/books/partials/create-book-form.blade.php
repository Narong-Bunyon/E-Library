<!-- Create Book Form -->
<form method="POST" id="createBookForm" action="{{ route('author.books.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="title" class="form-label">Book Title *</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description *</label>
            <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Minimum 50 characters..."></textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="access_level" class="form-label">Access Level</label>
                <select class="form-control" id="access_level" name="access_level">
                    <option value="0">Public</option>
                    <option value="1" selected>Private</option>
                    <option value="2">Premium</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="0" selected>Draft</option>
                    <option value="1">Published</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="pages" class="form-label">Pages</label>
                <input type="number" class="form-control" id="pages" name="pages" min="1">
            </div>
            <div class="col-md-6 mb-3">
                <label for="language" class="form-label">Language</label>
                <select class="form-control" id="language" name="language">
                    <option value="">Select Language</option>
                    <option value="en">English</option>
                    <option value="kh">Khmer</option>
                    <option value="fr">French</option>
                    <option value="zh">Chinese</option>
                    <option value="es">Spanish</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" placeholder="978-3-16-148410-0">
            </div>
            <div class="col-md-6 mb-3">
                <label for="published_date" class="form-label">Published Date</label>
                <input type="date" class="form-control" id="published_date" name="published_date">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="cover_image" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
                <label for="file_path" class="form-label">Book File</label>
                <input type="file" class="form-control" id="file_path" name="file_path" accept=".pdf,.epub,.mobi">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Book</button>
    </div>
</form>
