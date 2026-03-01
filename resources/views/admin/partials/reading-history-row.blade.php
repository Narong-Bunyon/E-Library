<tr>
    <td>
        <input type="checkbox" class="form-check-input history-checkbox" value="{{ $item->id }}" onchange="updateBulkActions()">
    </td>
    <td>
        <div class="d-flex align-items-center">
            <div class="user-avatar-sm me-2">{{ substr($item->user->name ?? 'U', 0, 1) }}</div>
            <div>
                <div class="fw-semibold">{{ $item->user->name ?? 'N/A' }}</div>
                <small class="text-muted">{{ $item->user->email ?? 'N/A' }}</small>
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center">
            <div class="book-cover-sm me-2">{{ substr($item->book_title ?? 'B', 0, 1) }}</div>
            <div>
                <div class="fw-semibold">{{ $item->book_title ?? 'N/A' }}</div>
                <small class="text-muted">{{ $item->book_author ?? 'N/A' }}</small>
            </div>
        </div>
    </td>
    <td>
        <small>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('M d, Y') : 'N/A' }}</small>
    </td>
    <td>
        <small>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('M d, Y') : 'N/A' }}</small>
    </td>
    <td>
        <div class="progress-info">
            <div class="progress-text">{{ $item->progress_percentage ?? 0 }}%</div>
            <div class="progress-pages">{{ $item->current_page ?? 0 }}/{{ $item->book_pages ?? 0 }} pages</div>
        </div>
    </td>
    <td>
        <span class="badge bg-{{ $item->status == 'completed' ? 'success' : ($item->status == 'in_progress' ? 'primary' : ($item->status == 'abandoned' ? 'danger' : 'secondary')) }}">
            {{ ucfirst($item->status ?? 'unknown') }}
        </span>
    </td>
    <td>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-info btn-sm" onclick="viewProgressDetails({{ $item->id }})" title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-outline-warning btn-sm" onclick="showAddToFavoritesModal({{ $item->user_id }}, {{ $item->book_id }}, '{{ $item->book_title ?? 'Unknown Book' }}', '{{ $item->user_name ?? 'Unknown User' }}')" title="Add to Favorites">
                <i class="fas fa-heart"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm" onclick="showDeleteHistoryModal({{ $item->id }}, '{{ $item->book_title ?? 'Unknown Book' }}', '{{ $item->user_name ?? 'Unknown User' }}')" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
