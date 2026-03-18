<tbody>
    @forelse ($books as $book)
        <tr class="book-row">
            <td>
                <input type="checkbox" class="form-check-input book-checkbox" value="{{ $book->id }}" onchange="updateBulkDeleteButton()">
            </td>
            <td>
                <div class="book-cover-container">
                    @if($book->cover_image)
                        <img src="{{ Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/' . $book->cover_image) }}" 
                             alt="{{ $book->title }}" 
                             class="book-cover-img">
                    @else
                        <div class="book-cover-placeholder">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                </div>
            </td>
            <td>
                <div class="book-info">
                    <h6 class="book-title mb-1">{{ $book->title }}</h6>
                    <small class="text-muted">{{ $book->pages ?? 0 }} pages</small>
                </div>
            </td>
            <td>
                <span class="author-name">{{ $book->author->name ?? 'Unknown' }}</span>
            </td>
            <td>
                <span class="badge bg-light text-dark category-badge">{{ $book->categories->first()->name ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="badge {{ $book->status == 1 ? 'bg-success' : 'bg-warning' }} status-badge">
                    {{ $book->status == 1 ? 'Published' : 'Draft' }}
                </span>
            </td>
            <td>
                <div class="download-info">
                    <i class="fas fa-download text-primary"></i>
                    <span class="fw-bold">{{ $book->totalDownloads() }}</span>
                </div>
            </td>
            <td>
                <div class="rating-info">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $book->averageRating() >= $i ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                    <small class="rating-number">{{ number_format($book->averageRating(), 1) }}</small>
                </div>
            </td>
            <td>
                <small class="text-muted">{{ \Carbon\Carbon::parse($book->created_at)?->diffForHumans() ?? 'N/A' }}</small>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editBook({{ $book->id }})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="viewBook({{ $book->id }})" title="Review">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBook({{ $book->id }})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Books Found</h5>
                    <p class="text-muted">No books found matching your criteria.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBookModal">
                        <i class="fas fa-plus me-1"></i>
                        Create First Book
                    </button>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
