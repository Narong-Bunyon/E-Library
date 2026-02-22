@extends('layouts.marketing')

@section('title', 'Settings - E‑Library')

@section('content')
<div class="home-content">
<div class="container">
    <!-- Settings Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Settings</h2>
                    <p class="text-muted mb-0">Customize your reading experience and preferences.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Reading Preferences -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">Reading Preferences</h5>
                </div>
                <div class="section-body">
                    <div class="mb-3">
                        <label class="form-label">Default Font Size</label>
                        <select class="form-select">
                            <option>Small</option>
                            <option selected>Medium</option>
                            <option>Large</option>
                            <option>Extra Large</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Theme</label>
                        <select class="form-select">
                            <option selected>Light</option>
                            <option>Dark</option>
                            <option>Auto</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reading Mode</label>
                        <select class="form-select">
                            <option selected>Standard</option>
                            <option>Focus Mode</option>
                            <option>Sepia</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoSave" checked>
                            <label class="form-check-label" for="autoSave">
                                Auto-save reading progress
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="nightMode">
                            <label class="form-check-label" for="nightMode">
                                Night mode for evening reading
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">Notifications</h5>
                </div>
                <div class="section-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                            <label class="form-check-label" for="emailNotifications">
                                Email notifications
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="newBooks" checked>
                            <label class="form-check-label" for="newBooks">
                                New book recommendations
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="readingReminders">
                            <label class="form-check-label" for="readingReminders">
                                Daily reading reminders
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="authorUpdates" checked>
                            <label class="form-check-label" for="authorUpdates">
                                Author updates and new releases
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="socialFeatures">
                            <label class="form-check-label" for="socialFeatures">
                                Social features and community updates
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Privacy Settings -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">Privacy</h5>
                </div>
                <div class="section-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="publicProfile">
                            <label class="form-check-label" for="publicProfile">
                                Make profile public
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="shareReading" checked>
                            <label class="form-check-label" for="shareReading">
                                Share reading activity with friends
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="showProgress">
                            <label class="form-check-label" for="showProgress">
                                Show reading progress on profile
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Profile Visibility</label>
                        <select class="form-select">
                            <option>Everyone</option>
                            <option selected>Friends Only</option>
                            <option>Private</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data & Storage -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-header">
                    <h5 class="section-title">Data & Storage</h5>
                </div>
                <div class="section-body">
                    <div class="mb-4">
                        <h6 class="mb-3">Storage Usage</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Downloads</span>
                            <span class="text-muted">0 MB</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">0 MB of 1 GB used</small>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="mb-3">Cache Management</h6>
                        <button class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-broom me-2"></i>
                            Clear Cache
                        </button>
                        <button class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>
                            Clear All Downloads
                        </button>
                    </div>
                    
                    <div>
                        <h6 class="mb-3">Export Data</h6>
                        <button class="btn btn-outline-primary w-100">
                            <i class="fas fa-download me-2"></i>
                            Download My Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Settings -->
    <div class="section-card">
        <div class="section-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Save Changes</h5>
                    <p class="text-muted mb-0">Click save to apply all your settings changes.</p>
                </div>
                <div>
                    <button class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>
                        Save All Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
