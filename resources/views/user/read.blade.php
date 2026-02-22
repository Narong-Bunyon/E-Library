@extends('layouts.marketing')

@section('title', 'Reading: ' . $book->title . ' - E‑Library')

@section('content')
<div class="reading-page">
    <div class="reading-header">
        <div class="container">
            <div class="reading-header__inner">
                <a href="{{ route('book.show', $book->id) }}" class="reading-header__back">
                    <i class="fas fa-arrow-left"></i> Back to Book
                </a>
                <h1 class="reading-header__title">{{ $book->title }}</h1>
                <span class="reading-header__author">by {{ $book->author->name ?? 'Unknown' }}</span>
            </div>
        </div>
    </div>

    <div class="reading-content">
        <div class="container">
            @if ($book->file_path)
                <div class="reading-viewer">
                    @php
                        $extension = pathinfo($book->file_path, PATHINFO_EXTENSION);
                    @endphp

                    @if ($extension === 'pdf')
                        <iframe 
                            src="{{ asset('storage/' . $book->file_path) }}" 
                            class="reading-viewer__iframe"
                            title="{{ $book->title }}"
                        ></iframe>
                    @else
                        <div class="reading-viewer__fallback">
                            <i class="fas fa-file-alt"></i>
                            <h3>Download to Read</h3>
                            <p>This book is available in {{ strtoupper($extension) }} format.</p>
                            <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn--primary" download>
                                <i class="fas fa-download"></i> Download {{ strtoupper($extension) }}
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="reading-viewer__fallback">
                    <i class="fas fa-book-open"></i>
                    <h3>No File Available</h3>
                    <p>The reading file for this book has not been uploaded yet.</p>
                    @if ($book->description)
                        <div class="reading-viewer__description">
                            <h4>Book Description</h4>
                            <p>{!! nl2br(e($book->description)) !!}</p>
                        </div>
                    @endif
                    <a href="{{ route('book.show', $book->id) }}" class="btn btn--primary">
                        <i class="fas fa-arrow-left"></i> Back to Book Details
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
