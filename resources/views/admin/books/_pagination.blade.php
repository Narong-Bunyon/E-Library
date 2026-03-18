@if ($books->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Showing <strong>{{ $books->firstItem() }}</strong> to <strong>{{ $books->lastItem() }}</strong> of <strong>{{ $books->total() }}</strong> books
        </div>
        <div class="d-flex gap-2">
            @if ($books->onFirstPage())
                <button class="btn btn-outline-secondary" disabled id="prevBtn">Previous</button>
            @else
                <button class="btn btn-outline-primary" id="prevBtn" data-url="{{ $books->previousPageUrl() }}">Previous</button>
            @endif
            
            @if ($books->hasMorePages())
                <button class="btn btn-outline-primary" id="nextBtn" data-url="{{ $books->nextPageUrl() }}">Next</button>
            @else
                <button class="btn btn-outline-secondary" disabled id="nextBtn">Next</button>
            @endif
        </div>
    </div>
@endif
