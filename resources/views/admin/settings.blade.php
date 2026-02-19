@extends('layouts.admin')

@section('title', 'System Settings - E-Library')

@section('page-title', 'System Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        System Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="siteName" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="siteName" value="E-Library">
                                    <div class="form-text">This is the name that appears in the header of your site.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="siteEmail" class="form-label">Site Email</label>
                                    <input type="email" class="form-control" id="siteEmail" value="admin@elibrary.com">
                                    <div class="form-text">Email used for system notifications.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="maxBooks" class="form-label">Maximum Books per User</label>
                                    <input type="number" class="form-control" id="maxBooks" value="10">
                                    <div class="form-text">Maximum number of books a user can borrow at once.</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registrationStatus" class="form-label">User Registration</label>
                                    <select class="form-select" id="registrationStatus">
                                        <option value="open">Open - Anyone can register</option>
                                        <option value="approval">Approval Required - Admin must approve</option>
                                        <option value="closed">Closed - No new registrations</option>
                                    </select>
                                    <div class="form-text">Control how new users can register on your site.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="maintenanceMode" class="form-label">Maintenance Mode</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                        <label class="form-check-label" for="maintenanceMode">
                                            Enable maintenance mode
                                        </label>
                                    </div>
                                    <div class="form-text">When enabled, only administrators can access the site.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="defaultRole" class="form-label">Default User Role</label>
                                    <select class="form-select" id="defaultRole">
                                        <option value="user" selected>Reader</option>
                                        <option value="author">Author</option>
                                    </select>
                                    <div class="form-text">Default role assigned to new registered users.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="siteDescription" class="form-label">Site Description</label>
                                    <textarea class="form-control" id="siteDescription" rows="3">Welcome to E-Library - Your digital reading platform</textarea>
                                    <div class="form-text">Brief description of your library site.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Settings Cards -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-palette me-2"></i>
                        Appearance Settings
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="primaryColor" class="form-label">Primary Color</label>
                        <input type="color" class="form-control form-control-color" id="primaryColor" value="#4f46e5">
                    </div>
                    
                    <div class="mb-3">
                        <label for="theme" class="form-label">Theme</label>
                        <select class="form-select" id="theme">
                            <option value="light" selected>Light</option>
                            <option value="dark">Dark</option>
                            <option value="auto">Auto</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        Email Settings
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="smtpHost" class="form-label">SMTP Host</label>
                        <input type="text" class="form-control" id="smtpHost" placeholder="smtp.gmail.com">
                    </div>
                    
                    <div class="mb-3">
                        <label for="smtpPort" class="form-label">SMTP Port</label>
                        <input type="number" class="form-control" id="smtpPort" value="587">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                            <label class="form-check-label" for="emailNotifications">
                                Enable email notifications
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
