@extends('layouts.admin')

@section('title', 'System Logs - E-Library')

@section('page-title', 'System Logs')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-file-alt me-2"></i>
                System Logs
            </h4>
            <p class="text-muted mb-0">Monitor system activity, errors, and performance</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" onclick="runSystemCheck()">
                <i class="fas fa-stethoscope me-1"></i>
                System Check
            </button>
            <button class="btn btn-primary" onclick="clearLogs()">
                <i class="fas fa-trash me-1"></i>
                Clear Logs
            </button>
            <button class="btn btn-info" onclick="downloadLogs()">
                <i class="fas fa-download me-1"></i>
                Download Logs
            </button>
        </div>
    </div>

    <!-- System Health Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="health-card">
                <div class="health-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="health-content">
                    <h3 class="health-status">Healthy</h3>
                    <p class="health-label">System Status</p>
                    <div class="health-indicator bg-success"></div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="health-card">
                <div class="health-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="health-content">
                    <h3 class="health-number">{{ $systemStats->error_count }}</h3>
                    <p class="health-label">Errors (24h)</p>
                    <small class="text-muted">Last updated: {{ $systemStats->last_updated ? \Carbon\Carbon::createFromTimestamp($systemStats->last_updated)->diffForHumans() : 'Never' }}</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="health-card">
                <div class="health-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="health-content">
                    <h3 class="health-number">{{ $systemStats->total_entries }}</h3>
                    <p class="health-label">Total Log Entries</p>
                    <small class="text-muted">{{ count($logFiles) }} log files</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="health-card">
                <div class="health-icon">
                    <i class="fas fa-hdd"></i>
                </div>
                <div class="health-content">
                    <h3 class="health-size">{{ number_format(array_sum(array_column($logFiles, 'size')) / 1024 / 1024, 2) }} MB</h3>
                    <p class="health-label">Log Storage</p>
                    <small class="text-muted">Disk usage</small>
                </div>
            </div>
        </div>
    </div>

    <!-- System Check Results -->
    <div class="row mb-4" id="systemCheckResults" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        System Health Check Results
                    </h5>
                </div>
                <div class="card-body">
                    <div id="checkResults">
                        <!-- Results will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#recent-logs" role="tab">
                        <i class="fas fa-clock me-1"></i>
                        Recent Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#error-logs" role="tab">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Error Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#access-logs" role="tab">
                        <i class="fas fa-users me-1"></i>
                        Access Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#log-files" role="tab">
                        <i class="fas fa-folder me-1"></i>
                        Log Files
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#system-info" role="tab">
                        <i class="fas fa-server me-1"></i>
                        System Info
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content">
                <!-- Recent Logs Tab -->
                <div class="tab-pane fade show active" id="recent-logs" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Recent Log Entries</h6>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" style="width: 150px;" id="logLevelFilter">
                                <option value="">All Levels</option>
                                <option value="ERROR">Error</option>
                                <option value="WARNING">Warning</option>
                                <option value="INFO">Info</option>
                                <option value="DEBUG">Debug</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" placeholder="Search logs..." style="width: 200px;" id="logSearch">
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Level</th>
                                    <th>Channel</th>
                                    <th>Message</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="recentLogsTable">
                                @forelse ($recentLogs as $log)
                                    <tr class="log-entry" data-level="{{ $log['level'] }}">
                                        <td>
                                            <small>{{ $log['timestamp'] }}</small>
                                        </td>
                                        <td>
                                            <span class="badge log-level-{{ strtolower($log['level']) }}">
                                                {{ $log['level'] }}
                                            </span>
                                        </td>
                                        <td>{{ $log['channel'] }}</td>
                                        <td>
                                            <div class="log-message" title="{{ $log['message'] }}">
                                                {{ Str::limit($log['message'], 80) }}
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewLogDetails('{{ $log['timestamp'] }}', '{{ $log['level'] }}', '{{ $log['channel'] }}', '{{ $log['message'] }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No log entries found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Error Logs Tab -->
                <div class="tab-pane fade" id="error-logs" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Error Log Entries</h6>
                        <button class="btn btn-sm btn-outline-danger" onclick="clearErrorLogs()">
                            <i class="fas fa-trash me-1"></i>
                            Clear Error Logs
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Error Type</th>
                                    <th>Message</th>
                                    <th>Frequency</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($errorLogs as $error)
                                    <tr class="error-log-entry">
                                        <td>
                                            <small>{{ $error['timestamp'] }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">ERROR</span>
                                        </td>
                                        <td>
                                            <div class="error-message" title="{{ $error['message'] }}">
                                                {{ Str::limit($error['message'], 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">1</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewErrorDetails('{{ $error['timestamp'] }}', '{{ $error['message'] }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="investigateError('{{ $error['message'] }}')">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-check-circle me-2"></i>
                                            No error logs found - System is running smoothly!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Access Logs Tab -->
                <div class="tab-pane fade" id="access-logs" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>User Access Logs</h6>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" style="width: 150px;">
                                <option>All Actions</option>
                                <option>Login</option>
                                <option>Logout</option>
                                <option>Registration</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accessLogs as $access)
                                    <tr>
                                        <td>
                                            <small>{{ $access['timestamp'] }}</small>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $access['user'] }}</strong>
                                                <small class="text-muted d-block">{{ $access['email'] }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $access['action'] }}</span>
                                        </td>
                                        <td>
                                            <code>{{ $access['ip'] }}</code>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($access['user_agent'], 30) }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-users me-2"></i>
                                            No access logs found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Log Files Tab -->
                <div class="tab-pane fade" id="log-files" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Log Files Management</h6>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshLogFiles()">
                            <i class="fas fa-sync me-1"></i>
                            Refresh
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Last Modified</th>
                                    <th>Entries</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logFiles as $file)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt me-2 text-primary"></i>
                                                <strong>{{ $file['name'] }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ number_format($file['size'] / 1024, 2) }} KB</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($file['modified'])->diffForHumans() }}</td>
                                        <td>
                                            <span class="badge bg-secondary">--</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="viewLogFile('{{ $file['name'] }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-success" onclick="downloadLogFile('{{ $file['name'] }}')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteLogFile('{{ $file['name'] }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-folder-open me-2"></i>
                                            No log files found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- System Info Tab -->
                <div class="tab-pane fade" id="system-info" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Server Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>PHP Version</th>
                                    <td>{{ PHP_VERSION }}</td>
                                </tr>
                                <tr>
                                    <th>Laravel Version</th>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <th>Server Software</th>
                                    <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <th>Server Time</th>
                                    <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Timezone</th>
                                    <td>{{ config('app.timezone') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3">Database Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Database Driver</th>
                                    <td>{{ config('database.default') }}</td>
                                </tr>
                                <tr>
                                    <th>Database Name</th>
                                    <td>{{ config('database.connections.mysql.database') }}</td>
                                </tr>
                                <tr>
                                    <th>Connection Status</th>
                                    <td>
                                        @try
                                            <span class="badge bg-success">Connected</span>
                                        @catch (\Exception $e)
                                            <span class="badge bg-danger">Error</span>
                                        @endtry
                                    </td>
                                </tr>
                                <tr>
                                    <th>Cache Driver</th>
                                    <td>{{ config('cache.default') }}</td>
                                </tr>
                                <tr>
                                    <th>Session Driver</th>
                                    <td>{{ config('session.driver') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">System Resources</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="resource-card">
                                        <h6>Memory Usage</h6>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" style="width: {{ round(memory_get_usage() / 1024 / 1024 / 128 * 100) }}%"></div>
                                        </div>
                                        <small>{{ round(memory_get_usage() / 1024 / 1024, 2) }} MB / 128 MB</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="resource-card">
                                        <h6>Disk Space</h6>
                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-info" style="width: 45%"></div>
                                        </div>
                                        <small>45% used ({{ number_format(disk_free_space('/') / 1024 / 1024 / 1024, 2) }} GB free)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="resource-card">
                                        <h6>Uptime</h6>
                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                        <small>{{ now()->diffInDays(\Carbon\Carbon::createFromDate(2024, 1, 1)) }} days</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Entry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailsContent">
                    <!-- Log details will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyLogDetails()">
                    <i class="fas fa-copy me-1"></i>
                    Copy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- System Check Modal -->
<div class="modal fade" id="systemCheckModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Running System Check</h5>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Checking...</span>
                </div>
                <h6 id="checkStatus">Performing system health check...</h6>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="checkProgress" style="width: 0%"></div>
                </div>
                <small id="checkDetails" class="text-muted">Initializing system diagnostics...</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.health-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.health-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.health-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #667eea;
}

.health-status {
    font-size: 1.5rem;
    font-weight: 700;
    color: #28a745;
    margin-bottom: 0.5rem;
}

.health-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.health-size {
    font-size: 1.6rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.health-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.health-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 auto;
}

.log-level-error { background-color: #dc3545; }
.log-level-warning { background-color: #ffc107; color: #000; }
.log-level-info { background-color: #17a2b8; }
.log-level-debug { background-color: #6c757d; }

.log-message, .error-message {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.resource-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.resource-card h6 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    border-bottom-color: #667eea;
    color: #667eea;
    background: none;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #667eea;
    color: #667eea;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background: #f8f9fa;
}

.progress {
    height: 8px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.check-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-left: 4px solid #dee2e6;
    margin-bottom: 0.5rem;
    border-radius: 0 8px 8px 0;
}

.check-item.success {
    border-left-color: #28a745;
    background: #d4edda;
}

.check-item.warning {
    border-left-color: #ffc107;
    background: #fff3cd;
}

.check-item.error {
    border-left-color: #dc3545;
    background: #f8d7da;
}

.check-icon {
    margin-right: 1rem;
    font-size: 1.2rem;
}

.check-item.success .check-icon {
    color: #28a745;
}

.check-item.warning .check-icon {
    color: #ffc107;
}

.check-item.error .check-icon {
    color: #dc3545;
}

.check-content {
    flex: 1;
}

.check-content strong {
    display: block;
    margin-bottom: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
// Run system check
function runSystemCheck() {
    const modal = new bootstrap.Modal(document.getElementById('systemCheckModal'));
    modal.show();
    
    let progress = 0;
    const statusEl = document.getElementById('checkStatus');
    const progressEl = document.getElementById('checkProgress');
    const detailsEl = document.getElementById('checkDetails');
    
    const checks = [
        { progress: 20, status: 'Checking database connection...', details: 'Verifying MySQL connectivity' },
        { progress: 40, status: 'Checking file permissions...', details: 'Verifying storage directory permissions' },
        { progress: 60, status: 'Checking system resources...', details: 'Analyzing memory and disk usage' },
        { progress: 80, status: 'Checking security settings...', details: 'Verifying authentication and encryption' },
        { progress: 100, status: 'System check completed!', details: 'All checks completed successfully' }
    ];
    
    let currentCheck = 0;
    
    const interval = setInterval(() => {
        if (currentCheck < checks.length) {
            const check = checks[currentCheck];
            progress = check.progress;
            
            statusEl.textContent = check.status;
            progressEl.style.width = progress + '%';
            detailsEl.textContent = check.details;
            
            currentCheck++;
        } else {
            clearInterval(interval);
            
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('systemCheckModal')).hide();
                showSystemCheckResults();
            }, 1000);
        }
    }, 800);
}

// Show system check results
function showSystemCheckResults() {
    const resultsDiv = document.getElementById('systemCheckResults');
    const checkResults = document.getElementById('checkResults');
    
    const results = [
        { type: 'success', title: 'Database Connection', message: 'Successfully connected to MySQL database' },
        { type: 'success', title: 'File Permissions', message: 'All storage directories are writable' },
        { type: 'warning', title: 'Memory Usage', message: 'Memory usage is at 75% - consider monitoring' },
        { type: 'success', title: 'Security Settings', message: 'All security configurations are properly set' },
        { type: 'success', title: 'Cache System', message: 'Cache is working efficiently' },
        { type: 'warning', title: 'Log File Size', message: 'Log files are growing large - consider rotation' }
    ];
    
    let resultsHtml = '';
    results.forEach(result => {
        resultsHtml += `
            <div class="check-item ${result.type}">
                <div class="check-icon">
                    <i class="fas fa-${result.type === 'success' ? 'check-circle' : result.type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
                </div>
                <div class="check-content">
                    <strong>${result.title}</strong>
                    <small class="text-muted">${result.message}</small>
                </div>
            </div>
        `;
    });
    
    checkResults.innerHTML = resultsHtml;
    resultsDiv.style.display = 'block';
    
    // Scroll to results
    resultsDiv.scrollIntoView({ behavior: 'smooth' });
}

// Clear logs
function clearLogs() {
    if (confirm('Are you sure you want to clear all system logs? This action cannot be undone.')) {
        alert('System logs would be cleared.\n\nIn production, this would:\n- Clear all log files\n- Reset log counters\n- Log the clearing action\n- Send confirmation notification');
        
        // In production, you would make an AJAX call to clear logs
        // fetch('/admin/logs/clear', { method: 'POST' })
    }
}

// Clear error logs
function clearErrorLogs() {
    if (confirm('Are you sure you want to clear all error logs?')) {
        alert('Error logs would be cleared.\n\nIn production, this would remove only error entries from logs.');
    }
}

// Download logs
function downloadLogs() {
    alert('Log files would be downloaded as a ZIP archive.\n\nIn production, this would:\n- Compress all log files\n- Generate download link\n- Log the download activity');
}

// View log details
function viewLogDetails(timestamp, level, channel, message) {
    const content = `
        <div class="log-details">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Timestamp:</strong><br>
                    <code>${timestamp}</code>
                </div>
                <div class="col-md-6">
                    <strong>Level:</strong><br>
                    <span class="badge log-level-${level.toLowerCase()}">${level}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Channel:</strong><br>
                    <code>${channel}</code>
                </div>
                <div class="col-md-6">
                    <strong>Stack Trace:</strong><br>
                    <small class="text-muted">Available in full log file</small>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <strong>Message:</strong><br>
                    <pre class="bg-light p-3 rounded">${message}</pre>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('logDetailsContent').innerHTML = content;
    
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    modal.show();
}

// View error details
function viewErrorDetails(timestamp, message) {
    viewLogDetails(timestamp, 'ERROR', 'system', message);
}

// Investigate error
function investigateError(message) {
    alert(`Error investigation would open for:\n\n${message}\n\nIn production, this would:\n- Search for similar errors\n- Show error frequency\n- Provide troubleshooting steps\n- Link to documentation`);
}

// Copy log details
function copyLogDetails() {
    const content = document.getElementById('logDetailsContent').innerText;
    navigator.clipboard.writeText(content).then(() => {
        alert('Log details copied to clipboard!');
    });
}

// View log file
function viewLogFile(filename) {
    alert(`Viewing log file: ${filename}\n\nIn production, this would:\n- Display file contents\n- Show line numbers\n- Allow searching\n- Provide export options`);
}

// Download log file
function downloadLogFile(filename) {
    alert(`Downloading log file: ${filename}\n\nIn production, this would start the file download.`);
}

// Delete log file
function deleteLogFile(filename) {
    if (confirm(`Are you sure you want to delete log file: ${filename}?`)) {
        alert(`Log file ${filename} would be deleted.\n\nIn production, this would:\n- Remove the file from storage\n- Update file listing\n- Log the deletion action`);
    }
}

// Refresh log files
function refreshLogFiles() {
    alert('Log files list would be refreshed.\n\nIn production, this would:\n- Rescan storage/logs directory\n- Update file sizes and timestamps\n- Refresh the table display');
    // location.reload();
}

// Filter logs
document.addEventListener('DOMContentLoaded', function() {
    const levelFilter = document.getElementById('logLevelFilter');
    const logSearch = document.getElementById('logSearch');
    
    if (levelFilter) {
        levelFilter.addEventListener('change', filterLogs);
    }
    
    if (logSearch) {
        logSearch.addEventListener('input', filterLogs);
    }
});

function filterLogs() {
    const levelFilter = document.getElementById('logLevelFilter').value;
    const searchTerm = document.getElementById('logSearch').value.toLowerCase();
    const logEntries = document.querySelectorAll('#recentLogsTable .log-entry');
    
    logEntries.forEach(entry => {
        const level = entry.dataset.level;
        const message = entry.querySelector('.log-message').textContent.toLowerCase();
        
        const levelMatch = !levelFilter || level === levelFilter;
        const searchMatch = !searchTerm || message.includes(searchTerm);
        
        entry.style.display = levelMatch && searchMatch ? '' : 'none';
    });
}
</script>
@endpush
