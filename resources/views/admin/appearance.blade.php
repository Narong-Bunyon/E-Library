@extends('layouts.admin')

@section('title', 'Appearance Settings - E-Library')

@section('page-title', 'Appearance Settings')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-palette me-2"></i>
                Appearance Settings
            </h4>
            <p class="text-muted mb-0">Customize the look and feel of your E-Library</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="saveAppearanceSettings()">
                <i class="fas fa-save me-1"></i>
                Save Changes
            </button>
            <button class="btn btn-outline-secondary" onclick="resetToDefault()">
                <i class="fas fa-undo me-1"></i>
                Reset to Default
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Theme Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-paint-brush me-2"></i>
                        Theme Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Current Theme</label>
                        <div class="d-flex align-items-center gap-3">
                            @foreach ($availableThemes as $key => $theme)
                                <div class="form-check">
                                    <input type="radio" 
                                           class="form-check-input" 
                                           name="theme" 
                                           value="{{ $key }}" 
                                           @if ($currentTheme === $key) checked @endif>
                                    <label class="form-check-label">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-preview me-2">
                                                @php
                                                $colors = [
                                                    'default' => '#007bff',
                                                    'dark' => '#343a40',
                                                    'light' => '#f8f9fa',
                                                    'modern' => '#6f42c1',
                                                ];
                                                $themeColor = $colors[$key] ?? '#007bff';
                                                @endphp
                                                <i class="fas fa-palette" style="color: {{ $themeColor }};"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $theme }}</strong>
                                                <small class="text-muted">
                                                    @php
                                                    $descriptions = [
                                                        'default' => 'Clean and professional blue theme',
                                                        'dark' => 'Easy on the eyes dark theme',
                                                        'light' => 'Clean and minimal light theme',
                                                        'modern' => 'Contemporary purple theme with gradients',
                                                    ];
                                                    $themeDescription = $descriptions[$key] ?? 'Default theme';
                                                    @endphp
                                                    {{ $themeDescription }}
                                                </small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layout Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-th-large me-2"></i>
                        Layout Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="sidebar_collapsed" 
                                   name="sidebar_collapsed"
                                   @if ($layoutSettings['sidebar_collapsed']) checked @endif>
                            <label class="form-check-label" for="sidebar_collapsed">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Collapsed Sidebar</span>
                                    <small class="text-muted">Hide sidebar by default</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="show_notifications" 
                                   name="show_notifications"
                                   @if ($layoutSettings['show_notifications']) checked @endif>
                            <label class="form-check-label" for="show_notifications">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Show Notifications</span>
                                    <small class="text-muted">Display system notifications</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="compact_mode" 
                                   name="compact_mode"
                                   @if ($layoutSettings['compact_mode']) checked @endif>
                            <label class="form-check-label" for="compact_mode">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Compact Mode</span>
                                    <small class="text-muted">Use compact layout</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branding Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trademark me-2"></i>
                        Branding Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Site Name</label>
                        <input type="text" 
                               class="form-control" 
                               name="site_name" 
                               value="{{ $branding['site_name'] }}"
                               placeholder="Enter site name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Logo URL</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="logo_url" 
                                   value="{{ $branding['logo_url'] }}"
                                   placeholder="Enter logo URL">
                            <button class="btn btn-outline-secondary" type="button" onclick="uploadLogo()">
                                <i class="fas fa-upload"></i>
                                Upload
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Favicon URL</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="favicon_url" 
                                   value="{{ $branding['favicon_url'] }}"
                                   placeholder="Enter favicon URL">
                            <button class="btn btn-outline-secondary" type="button" onclick="uploadFavicon()">
                                <i class="fas fa-upload"></i>
                                Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="preview-container">
                        <div class="preview-header">
                            <h6>E-Library Admin Panel</h6>
                            <p class="text-muted">Preview of your selected theme and layout settings</p>
                        </div>
                        <div class="preview-body" id="previewContainer">
                            <!-- This will be updated dynamically with JavaScript -->
                            <div class="preview-sidebar">
                                <div class="preview-logo">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                                <div class="preview-nav">
                                    <div class="preview-nav-item active">Dashboard</div>
                                    <div class="preview-nav-item">Books</div>
                                    <div class="preview-nav-item">Users</div>
                                </div>
                            </div>
                            <div class="preview-main">
                                <div class="preview-content">
                                    <h5>Welcome to E-Library</h5>
                                    <p>This is how your admin panel will appear with the selected settings.</p>
                                    <div class="preview-cards">
                                        <div class="preview-card">
                                            <i class="fas fa-book"></i>
                                            <span>Total Books</span>
                                        </div>
                                        <div class="preview-card">
                                            <i class="fas fa-users"></i>
                                            <span>Total Users</span>
                                        </div>
                                        <div class="preview-card">
                                            <i class="fas fa-download"></i>
                                            <span>Downloads</span>
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
</div>

@push('styles')
<style>
.theme-preview {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
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

.preview-container {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    overflow: hidden;
}

.preview-header {
    background: #f8f9fa;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.preview-body {
    display: flex;
    min-height: 300px;
}

.preview-sidebar {
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 1rem;
}

.preview-sidebar.collapsed {
    width: 60px;
}

.preview-logo {
    text-align: center;
    margin-bottom: 2rem;
}

.preview-nav {
    margin-bottom: 2rem;
}

.preview-nav-item {
    padding: 0.5rem 1rem;
    margin-bottom: 0.5rem;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.preview-nav-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.preview-nav-item.active {
    background: rgba(0, 123, 255, 0.2);
}

.preview-main {
    flex: 1;
    padding: 2rem;
    background: #f8f9fa;
}

.preview-content h5 {
    color: #495057;
    margin-bottom: 1rem;
}

.preview-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

.preview-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-card i {
    font-size: 2rem;
    color: #007bff;
    margin-bottom: 0.5rem;
}

.preview-card span {
    display: block;
    font-weight: 600;
    color: #495057;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.form-check-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.form-check-label span {
    font-weight: 500;
}

.form-check-label small {
    font-size: 0.875rem;
    opacity: 0.8;
}
</style>
@endpush

@push('scripts')
<script>
// Save appearance settings
function saveAppearanceSettings() {
    const formData = new FormData();
    
    // Get theme settings
    const theme = document.querySelector('input[name="theme"]:checked')?.value || 'default';
    formData.append('theme', theme);
    
    // Get layout settings
    formData.append('sidebar_collapsed', document.getElementById('sidebar_collapsed').checked);
    formData.append('show_notifications', document.getElementById('show_notifications').checked);
    formData.append('compact_mode', document.getElementById('compact_mode').checked);
    
    // Get branding settings
    formData.append('site_name', document.querySelector('input[name="site_name"]').value);
    formData.append('logo_url', document.querySelector('input[name="logo_url"]').value);
    formData.append('favicon_url', document.querySelector('input[name="favicon_url"]').value);
    
    fetch('/admin/appearance/save', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Appearance settings saved successfully!');
        } else {
            alert('Error saving appearance settings: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error saving appearance settings: ' + error.message);
    });
}

// Reset to default settings
function resetToDefault() {
    if (confirm('Are you sure you want to reset all appearance settings to default values?')) {
        document.querySelector('input[name="theme"][value="default"]').checked = true;
        document.getElementById('sidebar_collapsed').checked = false;
        document.getElementById('show_notifications').checked = true;
        document.getElementById('compact_mode').checked = false;
        document.querySelector('input[name="site_name"]').value = 'E-Library';
        document.querySelector('input[name="logo_url"]').value = '/images/logo.png';
        document.querySelector('input[name="favicon_url"]').value = '/favicon.ico';
        
        updatePreview();
        alert('Settings reset to default values!');
    }
}

// Upload logo (placeholder)
function uploadLogo() {
    alert('Logo upload feature coming soon!');
}

// Upload favicon (placeholder)
function uploadFavicon() {
    alert('Favicon upload feature coming soon!');
}

// Update preview based on settings
function updatePreview() {
    const theme = document.querySelector('input[name="theme"]:checked')?.value || 'default';
    const sidebarCollapsed = document.getElementById('sidebar_collapsed').checked;
    const compactMode = document.getElementById('compact_mode').checked;
    
    const previewSidebar = document.querySelector('.preview-sidebar');
    const previewMain = document.querySelector('.preview-main');
    
    // Update theme colors
    @php
    $colors = [
        'default' => '#007bff',
        'dark' => '#343a40',
        'light' => '#f8f9fa',
        'modern' => '#6f42c1',
    ];
    $currentThemeColor = $colors[$currentTheme] ?? '#007bff';
    @endphp
    const themeColor = '{{ $currentThemeColor }}';
    previewSidebar.style.background = themeColor;
    
    // Update sidebar state
    if (sidebarCollapsed) {
        previewSidebar.classList.add('collapsed');
    } else {
        previewSidebar.classList.remove('collapsed');
    }
    
    // Update compact mode
    if (compactMode) {
        previewSidebar.classList.add('compact');
        previewMain.classList.add('compact');
    } else {
        previewSidebar.classList.remove('compact');
        previewMain.classList.remove('compact');
    }
}

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    
    // Add event listeners for real-time preview updates
    document.querySelectorAll('input[name="theme"]').forEach(input => {
        input.addEventListener('change', updatePreview);
    });
    
    document.getElementById('sidebar_collapsed').addEventListener('change', updatePreview);
    document.getElementById('show_notifications').addEventListener('change', updatePreview);
    document.getElementById('compact_mode').addEventListener('change', updatePreview);
});

// Handle theme radio button changes
document.querySelectorAll('input[name="theme"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Uncheck all other radio buttons
        document.querySelectorAll('input[name="theme"]').forEach(r => {
            if (r !== radio) r.checked = false;
        });
        // Check the selected one
        radio.checked = true;
        updatePreview();
    });
});
</script>
@endpush
@endsection
