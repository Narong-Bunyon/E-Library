@extends('layouts.admin')

@section('title', $reportData['title'] . ' - E-Library')

@section('page-title', $reportData['title'])

@section('content')
<div class="container-fluid p-4">
    <!-- Report Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">{{ $reportData['title'] }}</h2>
            <p class="text-muted mb-0">Generated on {{ now()->format('M d, Y H:i') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.download', $id) }}" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i> Download CSV
            </a>
            <a href="{{ route('reports.share', $id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-share me-1"></i> Share Report
            </a>
            <a href="{{ route('reports') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Reports
            </a>
        </div>
    </div>

    <!-- Report Statistics -->
    @if(!empty($reportData['stats']))
    <div class="row mb-4">
        @foreach($reportData['stats'] as $key => $value)
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="fas fa-chart-bar fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-1 fw-bold">{{ number_format($value) }}</h3>
                            <p class="text-muted mb-1">{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Report Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                Report Data
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            @if($reportData['data']->isNotEmpty())
                                @php
                                    $firstItem = $reportData['data']->first();
                                    $headers = array_keys($firstItem->toArray());
                                @endphp
                                @foreach($headers as $header)
                                    <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if($reportData['data']->isNotEmpty())
                            @foreach($reportData['data'] as $item)
                            <tr>
                                @foreach($headers as $header)
                                    <td>
                                        @php
                                            $value = $item->$header;
                                            if (is_object($value)) {
                                                $value = method_exists($value, 'name') ? $value->name : (method_exists($value, 'title') ? $value->title : json_encode($value));
                                            } elseif (is_bool($value)) {
                                                $value = $value ? 'Yes' : 'No';
                                            } elseif ($value instanceof \Carbon\Carbon) {
                                                $value = $value->format('M d, Y H:i');
                                            }
                                        @endphp
                                        {{ $value ?? 'N/A' }}
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ count($headers ?? 1) }}" class="text-center py-4">
                                    <p class="text-muted mb-0">No data available for this report.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($reportData['data'] instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Showing {{ $reportData['data']->firstItem() }} to {{ $reportData['data']->lastItem() }} of {{ $reportData['data']->total() }} entries
                        </small>
                    </div>
                    <div>
                        {{ $reportData['data']->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
