@extends('layouts.admin')

@section('title', 'Backup Management - E-Library')

@section('page-title', 'Backup Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-database me-2"></i>
                Backup Management
            </h4>
            <p class="text-muted mb-0">Create, schedule, and restore system backups</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" onclick="createBackup()">
                <i class="fas fa-plus me-1"></i>
                Create Backup
            </button>
            <button class="btn btn-primary" onclick="scheduleBackup()">
                <i class="fas fa-clock me-1"></i>
                Schedule Backup
            </button>
        </div>
    </div>

    <!-- Backup Overview Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="backup-card">
                <div class="backup-icon">
                    <i class="fas fa-archive"></i>
                </div>
                <div class="backup-content">
                    <h3 class="backup-number">12</h3>
                    <p class="backup-label">Total Backups</p>
                    <small class="text-muted">Last: 2 hours ago</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="backup-card">
                <div class="backup-icon">
                    <i class="fas fa-hdd"></i>
                </div>
                <div class="backup-content">
                    <h3 class="backup-size">2.4 GB</h3>
                    <p class="backup-label">Storage Used</p>
                    <small class="text-muted">Of 10 GB available</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="backup-card">
                <div class="backup-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="backup-content">
                    <h3 class="backup-number">Daily</h3>
                    <p class="backup-label">Auto Backup</p>
                    <small class="text-muted">Next: 8 hours</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="backup-card success">
                <div class="backup-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="backup-content">
                    <h3 class="backup-number">100%</h3>
                    <p class="backup-label">Success Rate</p>
                    <small class="text-muted">Last 30 days</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="action-card text-center" onclick="createBackup('database')">
                                <div class="action-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <h6>Database Backup</h6>
                                <small class="text-muted">Backup database only</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="action-card text-center" onclick="createBackup('files')">
                                <div class="action-icon">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <h6>Files Backup</h6>
                                <small class="text-muted">Backup uploaded files</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="action-card text-center" onclick="createBackup('full')">
                                <div class="action-icon">
                                    <i class="fas fa-server"></i>
                                </div>
                                <h6>Full Backup</h6>
                                <small class="text-muted">Database + Files</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="action-card text-center" onclick="downloadBackup()">
                                <div class="action-icon">
                                    <i class="fas fa-download"></i>
                                </div>
                                <h6>Download Backup</h6>
                                <small class="text-muted">Get latest backup</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup Settings & Schedule -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Backup Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Backup Type</label>
                        <select class="form-select" name="backup_type">
                            <option value="full" selected>Full Backup (Database + Files)</option>
                            <option value="database">Database Only</option>
                            <option value="files">Files Only</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Compression</label>
                        <select class="form-select" name="compression">
                            <option value="gzip" selected>GZIP (.gz)</option>
                            <option value="zip">ZIP (.zip)</option>
                            <option value="none">No Compression</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Retention Period (days)</label>
                        <input type="number" class="form-control" name="retention_days" value="30" min="1" max="365">
                        <small class="text-muted">Keep backups for this many days</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="include_uploads" name="include_uploads" checked>
                            <label class="form-check-label" for="include_uploads">
                                Include Upload Files
                                <small class="text-muted d-block">Backup user uploaded content</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="encrypt_backup" name="encrypt_backup">
                            <label class="form-check-label" for="encrypt_backup">
                                Encrypt Backups
                                <small class="text-muted d-block">Add password protection</small>
                            </label>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary" onclick="saveBackupSettings()">
                        <i class="fas fa-save me-1"></i>
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Backup Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="enable_schedule" name="enable_schedule" checked>
                            <label class="form-check-label" for="enable_schedule">
                                Enable Automatic Backups
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Backup Frequency</label>
                        <select class="form-select" name="frequency">
                            <option value="hourly">Hourly</option>
                            <option value="daily" selected>Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Backup Time</label>
                        <input type="time" class="form-control" name="backup_time" value="02:00">
                        <small class="text-muted">When to run automatic backups</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Notifications</label>
                        <select class="form-select" name="email_notifications">
                            <option value="always" selected>Always notify</option>
                            <option value="on_failure">Only on failure</option>
                            <option value="never">Never notify</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notification Email</label>
                        <input type="email" class="form-control" name="notification_email" value="admin@elibrary.com">
                    </div>
                    
                    <button class="btn btn-success" onclick="updateSchedule()">
                        <i class="fas fa-clock me-1"></i>
                        Update Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Backup History
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Search backups..." style="width: 200px;">
                        <select class="form-select form-select-sm" style="width: 150px;">
                            <option>All Types</option>
                            <option>Full</option>
                            <option>Database</option>
                            <option>Files</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Backup Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-archive me-2 text-primary"></i>
                                            <div>
                                                <strong>backup_2024_02_13_02_00_full</strong>
                                                <small class="text-muted d-block">Full system backup</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">Full</span></td>
                                    <td>245.6 MB</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>2 hours ago</td>
                                    <td>3 min 24 sec</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="downloadBackupItem('backup_2024_02_13_02_00_full')">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="viewBackupDetails('backup_2024_02_13_02_00_full')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="restoreBackup('backup_2024_02_13_02_00_full')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBackup('backup_2024_02_13_02_00_full')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-database me-2 text-info"></i>
                                            <div>
                                                <strong>backup_2024_02_12_02_00_database</strong>
                                                <small class="text-muted d-block">Database only</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Database</span></td>
                                    <td>124.3 MB</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>1 day ago</td>
                                    <td>1 min 45 sec</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="downloadBackupItem('backup_2024_02_12_02_00_database')">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="viewBackupDetails('backup_2024_02_12_02_00_database')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="restoreBackup('backup_2024_02_12_02_00_database')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBackup('backup_2024_02_12_02_00_database')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-folder me-2 text-warning"></i>
                                            <div>
                                                <strong>backup_2024_02_11_02_00_files</strong>
                                                <small class="text-muted d-block">Files only</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Files</span></td>
                                    <td>1.2 GB</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>2 days ago</td>
                                    <td>5 min 12 sec</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="downloadBackupItem('backup_2024_02_11_02_00_files')">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="viewBackupDetails('backup_2024_02_11_02_00_files')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="restoreBackup('backup_2024_02_11_02_00_files')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBackup('backup_2024_02_11_02_00_files')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-archive me-2 text-primary"></i>
                                            <div>
                                                <strong>backup_2024_02_10_02_00_full</strong>
                                                <small class="text-muted d-block">Full system backup</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">Full</span></td>
                                    <td>238.9 MB</td>
                                    <td><span class="badge bg-danger">Failed</span></td>
                                    <td>3 days ago</td>
                                    <td>-</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-info" onclick="viewBackupDetails('backup_2024_02_10_02_00_full')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteBackup('backup_2024_02_10_02_00_full')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Backup pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Backup Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Backup Type</label>
                    <select class="form-select" id="backup_type_modal">
                        <option value="full">Full Backup (Database + Files)</option>
                        <option value="database">Database Only</option>
                        <option value="files">Files Only</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Backup Name (Optional)</label>
                    <input type="text" class="form-control" id="backup_name" placeholder="Leave blank for auto-generated name">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description (Optional)</label>
                    <textarea class="form-control" id="backup_description" rows="3" placeholder="Add notes about this backup"></textarea>
                </div>
                
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="immediate_download" name="immediate_download">
                        <label class="form-check-label" for="immediate_download">
                            Download backup immediately after creation
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="startBackupCreation()">
                    <i class="fas fa-play me-1"></i>
                    Start Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Restore Backup Modal -->
<div class="modal fade" id="restoreBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restore Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Restoring a backup will overwrite current data. This action cannot be undone.
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Backup to Restore</label>
                    <input type="text" class="form-control" id="restore_backup_name" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Restore Options</label>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="restore_option" id="restore_full" value="full" checked>
                        <label class="form-check-label" for="restore_full">
                            Full Restore (Database + Files)
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="restore_option" id="restore_database" value="database">
                        <label class="form-check-label" for="restore_database">
                            Database Only
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="restore_option" id="restore_files" value="files">
                        <label class="form-check-label" for="restore_files">
                            Files Only
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="create_backup_before_restore">
                        <label class="form-check-label" for="create_backup_before_restore">
                            Create backup of current state before restoring
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmRestore()">
                    <i class="fas fa-undo me-1"></i>
                    Restore Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Backup Progress Modal -->
<div class="modal fade" id="backupProgressModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Backup in Progress</h5>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 id="backup_status">Creating backup...</h6>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="backup_progress" style="width: 0%"></div>
                </div>
                <small id="backup_details" class="text-muted">Initializing backup process...</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.backup-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.backup-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.backup-card.success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.backup-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #667eea;
}

.backup-card.success .backup-icon {
    color: white;
}

.backup-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.backup-card.success .backup-number {
    color: white;
}

.backup-size {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.backup-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.backup-card.success .backup-label {
    color: white;
}

.action-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #667eea;
}

.action-icon {
    font-size: 2.5rem;
    color: #667eea;
    margin-bottom: 1rem;
}

.action-card h6 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background: #f8f9fa;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-check.form-switch {
    padding: 1rem 0;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.form-check.form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.progress {
    height: 8px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
@endpush

@push('scripts')
<script>
// Create backup
function createBackup(type = null) {
    if (type) {
        document.getElementById('backup_type_modal').value = type;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('createBackupModal'));
    modal.show();
}

// Start backup creation
function startBackupCreation() {
    const type = document.getElementById('backup_type_modal').value;
    const name = document.getElementById('backup_name').value;
    const description = document.getElementById('backup_description').value;
    const immediateDownload = document.getElementById('immediate_download').checked;
    
    // Close create modal
    bootstrap.Modal.getInstance(document.getElementById('createBackupModal')).hide();
    
    // Show progress modal
    const progressModal = new bootstrap.Modal(document.getElementById('backupProgressModal'));
    progressModal.show();
    
    // Simulate backup progress
    simulateBackupProgress(type, immediateDownload);
}

// Simulate backup progress
function simulateBackupProgress(type, immediateDownload) {
    let progress = 0;
    const statusEl = document.getElementById('backup_status');
    const progressEl = document.getElementById('backup_progress');
    const detailsEl = document.getElementById('backup_details');
    
    const steps = [
        { progress: 10, status: 'Preparing backup...', details: 'Initializing backup process...' },
        { progress: 25, status: 'Backing up database...', details: 'Creating database dump...' },
        { progress: 50, status: 'Backing up files...', details: 'Compressing uploaded files...' },
        { progress: 75, status: 'Compressing backup...', details: 'Creating compressed archive...' },
        { progress: 90, status: 'Finalizing backup...', details: 'Verifying backup integrity...' },
        { progress: 100, status: 'Backup completed!', details: 'Backup successfully created and stored.' }
    ];
    
    let currentStep = 0;
    
    const interval = setInterval(() => {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            progress = step.progress;
            
            statusEl.textContent = step.status;
            progressEl.style.width = progress + '%';
            detailsEl.textContent = step.details;
            
            currentStep++;
        } else {
            clearInterval(interval);
            
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('backupProgressModal')).hide();
                
                if (immediateDownload) {
                    downloadBackup();
                } else {
                    alert('Backup created successfully!\n\nIn production, this would show the backup in the history table.');
                }
                
                // Reset progress
                progress = 0;
                statusEl.textContent = 'Creating backup...';
                progressEl.style.width = '0%';
                detailsEl.textContent = 'Initializing backup process...';
            }, 1000);
        }
    }, 800);
}

// Schedule backup
function scheduleBackup() {
    alert('Backup scheduling interface would open.\n\nIn production, this would allow you to:\n- Set cron jobs\n- Configure backup frequencies\n- Set retention policies\n- Configure off-site storage');
}

// Download backup
function downloadBackup() {
    alert('Backup download would start.\n\nIn production, this would:\n- Generate download link\n- Stream the backup file\n- Log the download activity\n- Update download statistics');
}

// Download specific backup item
function downloadBackupItem(backupName) {
    alert(`Downloading backup: ${backupName}\n\nIn production, this would start the file download.`);
}

// View backup details
function viewBackupDetails(backupName) {
    alert(`Backup Details for: ${backupName}\n\nIn production, this would show:\n- File list and sizes\n- Backup configuration\n- Creation details\n- Integrity checksum\n- Restoration options`);
}

// Restore backup
function restoreBackup(backupName) {
    document.getElementById('restore_backup_name').value = backupName;
    
    const modal = new bootstrap.Modal(document.getElementById('restoreBackupModal'));
    modal.show();
}

// Confirm restore
function confirmRestore() {
    const backupName = document.getElementById('restore_backup_name').value;
    const restoreOption = document.querySelector('input[name="restore_option"]:checked').value;
    const createBackupBefore = document.getElementById('create_backup_before_restore').checked;
    
    bootstrap.Modal.getInstance(document.getElementById('restoreBackupModal')).hide();
    
    let message = `Restoring backup: ${backupName}\n\n`;
    message += `Restore option: ${restoreOption}\n`;
    
    if (createBackupBefore) {
        message += `A backup of current state will be created first.\n\n`;
    }
    
    message += `In production, this would:\n`;
    message += `- Verify backup integrity\n`;
    message += `- Create current state backup (if requested)\n`;
    message += `- Restore database and/or files\n`;
    message += `- Update system configuration\n`;
    message += `- Log restoration activity\n`;
    message += `- Send confirmation notification`;
    
    alert(message);
}

// Delete backup
function deleteBackup(backupName) {
    if (confirm(`Are you sure you want to delete backup: ${backupName}?\n\nThis action cannot be undone.`)) {
        alert(`Backup ${backupName} has been deleted.\n\nIn production, this would:\n- Remove backup files from storage\n- Update backup history\n- Log deletion activity\n- Update storage statistics`);
        
        // In a real application, you would remove the row from the table
        // location.reload();
    }
}

// Save backup settings
function saveBackupSettings() {
    alert('Backup settings have been saved successfully!\n\nIn production, this would update your backup configuration.');
}

// Update schedule
function updateSchedule() {
    alert('Backup schedule has been updated successfully!\n\nIn production, this would:\n- Update cron jobs\n- Modify backup timing\n- Send confirmation notification');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation for numeric inputs
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            const min = parseInt(this.min);
            const max = parseInt(this.max);
            const value = parseInt(this.value);
            
            if (value < min) this.value = min;
            if (value > max) this.value = max;
        });
    });
    
    // Add confirmation for critical actions
    document.querySelectorAll('.btn-outline-danger').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to perform this action?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
