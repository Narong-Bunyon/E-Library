<!-- Standardized Create Book Form -->
<form method="POST" id="createBookForm" action="{{ route('author.books.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="createTitle" class="form-label">Book Title *</label>
                <input type="text" class="form-control" id="createTitle" name="title" required>
                <div class="invalid-feedback">
                    Please provide a book title.
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="createIsbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="createIsbn" name="isbn" placeholder="e.g., 978-3-16-148410-0">
                <div class="form-text">Optional: International Standard Book Number</div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="createCategory" class="form-label">Category *</label>
                <select class="form-control" id="createCategory" name="category_id" required>
                    <option value="">Select Category</option>
                    @php
                        $categories = \App\Models\Category::all();
                    @endphp
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Please select a category.
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="createLanguage" class="form-label">Language</label>
                <select class="form-control" id="createLanguage" name="language">
                    <option value="">Select Language</option>
                    <option value="en">English</option>
                    <option value="kh">Khmer</option>
                    <option value="fr">French</option>
                    <option value="zh">Chinese</option>
                    <option value="es">Spanish</option>
                    <option value="de">German</option>
                    <option value="ja">Japanese</option>
                </select>
                <div class="form-text">Primary language of the book</div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="createPublisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" id="createPublisher" name="publisher" placeholder="Publisher name">
                <div class="form-text">Optional: Publisher information</div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="createPublishedDate" class="form-label">Published Date</label>
                <input type="date" class="form-control" id="createPublishedDate" name="published_date">
                <div class="form-text">Original publication date</div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="createPages" class="form-label">Pages</label>
                <input type="number" class="form-control" id="createPages" name="pages" min="1" placeholder="Number of pages">
                <div class="form-text">Total page count</div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="createAccessLevel" class="form-label">Access Level</label>
                <select class="form-control" id="createAccessLevel" name="access_level">
                    <option value="0">Public</option>
                    <option value="1">Private</option>
                    <option value="2">Premium</option>
                </select>
                <div class="form-text">Who can access this book</div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="createDescription" class="form-label">Description *</label>
            <textarea class="form-control" id="createDescription" name="description" rows="4" required placeholder="Provide a detailed description of your book..."></textarea>
            <div class="form-text">Minimum 50 characters recommended</div>
            <div class="invalid-feedback">
                Please provide a book description.
            </div>
        </div>
        
        <div class="mb-3">
            <label for="createExcerpt" class="form-label">Excerpt</label>
            <textarea class="form-control" id="createExcerpt" name="excerpt" rows="2" maxlength="500" placeholder="Brief summary or excerpt from your book..."></textarea>
            <div class="form-text">Short summary (max 500 characters)</div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="createCoverImage" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="createCoverImage" name="cover_image" accept="image/*">
                <div class="form-text">Recommended: 1200x800px, Max 2MB (JPG, PNG, GIF)</div>
                <div class="preview-container mt-2" id="createCoverPreview" style="display: none;">
                    <img src="" alt="Cover preview" class="img-thumbnail" style="max-height: 150px;">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="createFile" class="form-label">Book File</label>
                <input type="file" class="form-control" id="createFile" name="file_path" accept=".pdf,.epub,.mobi">
                <div class="form-text">Supported formats: PDF, EPUB, MOBI (Max 10MB)</div>
                <div class="file-info mt-2" id="createFileInfo" style="display: none;">
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="createStatus" class="form-label">Status</label>
            <select class="form-control" id="createStatus" name="status">
                <option value="0">Draft</option>
                <option value="1">Published</option>
            </select>
            <div class="form-text">Draft: Only you can see it. Published: Everyone can access it.</div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary" id="createSubmitBtn">
            <i class="fas fa-save me-2"></i>Create Book
        </button>
    </div>
</form>
