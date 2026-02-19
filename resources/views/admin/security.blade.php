@extends('layouts.admin')

@section('title', 'Security Settings - E-Library')

@section('page-title', 'Security Settings')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-shield-alt me-2"></i>
                Security Settings
            </h4>
            <p class="text-muted mb-0">Manage system security, authentication, and protection settings</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" onclick="runSecurityAudit()">
                <i class="fas fa-check-circle me-1"></i>
                Run Security Audit
            </button>
            <button class="btn btn-warning" onclick="exportSecurityReport()">
                <i class="fas fa-download me-1"></i>
                Export Report
            </button>
        </div>
    </div>

    <!-- Security Overview Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="security-card overall">
                <div class="security-icon">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div class="security-content">
                    <h3 class="security-score">85%</h3>
                    <p class="security-label">Overall Security</p>
                    <div class="security-progress">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="security-card">
                <div class="security-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="security-content">
                    <h3 class="security-number">12</h3>
                    <p class="security-label">Active Policies</p>
                    <small class="text-muted">Last updated: 2 hours ago</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="security-card">
                <div class="security-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="security-content">
                    <h3 class="security-number">3</h3>
                    <p class="security-label">Security Alerts</p>
                    <small class="text-muted">2 critical, 1 warning</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="security-card">
                <div class="security-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="security-content">
                    <h3 class="security-number">247</h3>
                    <p class="security-label">Failed Logins</p>
                    <small class="text-muted">Last 24 hours</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Settings Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#authentication" role="tab">
                        <i class="fas fa-key me-1"></i>
                        Authentication
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#password" role="tab">
                        <i class="fas fa-lock me-1"></i>
                        Password Policy
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#session" role="tab">
                        <i class="fas fa-clock me-1"></i>
                        Session Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#firewall" role="tab">
                        <i class="fas fa-fire me-1"></i>
                        Firewall & Access
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#monitoring" role="tab">
                        <i class="fas fa-eye me-1"></i>
                        Monitoring
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content">
                <!-- Authentication Tab -->
                <div class="tab-pane fade show active" id="authentication" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="mb-3">Authentication Methods</h5>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="two_factor_auth" name="two_factor_auth" checked>
                                    <label class="form-check-label" for="two_factor_auth">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Two-Factor Authentication</span>
                                            <small class="text-muted">Require 2FA for admin users</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="email_verification" name="email_verification" checked>
                                    <label class="form-check-label" for="email_verification">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Email Verification</span>
                                            <small class="text-muted">Verify email addresses</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="social_login" name="social_login">
                                    <label class="form-check-label" for="social_login">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Social Login</span>
                                            <small class="text-muted">Allow Google, Facebook login</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="ldap_auth" name="ldap_auth">
                                    <label class="form-check-label" for="ldap_auth">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>LDAP Authentication</span>
                                            <small class="text-muted">Enterprise directory integration</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <h5 class="mb-3">Login Security</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Max Login Attempts</label>
                                <input type="number" class="form-control" name="max_login_attempts" value="5" min="1" max="20">
                                <small class="text-muted">Number of failed attempts before lockout</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Lockout Duration (minutes)</label>
                                <input type="number" class="form-control" name="lockout_duration" value="15" min="1" max="1440">
                                <small class="text-muted">How long to lock account after failed attempts</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="ip_whitelist" name="ip_whitelist">
                                    <label class="form-check-label" for="ip_whitelist">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>IP Whitelist</span>
                                            <small class="text-muted">Restrict access to specific IPs</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="captcha_login" name="captcha_login" checked>
                                    <label class="form-check-label" for="captcha_login">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>reCAPTCHA on Login</span>
                                            <small class="text-muted">Prevent automated attacks</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Policy Tab -->
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="mb-3">Password Requirements</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Minimum Password Length</label>
                                <input type="number" class="form-control" name="min_password_length" value="8" min="6" max="32">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="require_uppercase" name="require_uppercase" checked>
                                    <label class="form-check-label" for="require_uppercase">
                                        Require Uppercase Letters
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="require_lowercase" name="require_lowercase" checked>
                                    <label class="form-check-label" for="require_lowercase">
                                        Require Lowercase Letters
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="require_numbers" name="require_numbers" checked>
                                    <label class="form-check-label" for="require_numbers">
                                        Require Numbers
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="require_symbols" name="require_symbols">
                                    <label class="form-check-label" for="require_symbols">
                                        Require Special Characters
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <h5 class="mb-3">Password Management</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password Expiry (days)</label>
                                <input type="number" class="form-control" name="password_expiry" value="90" min="0" max="365">
                                <small class="text-muted">0 = never expires</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password History</label>
                                <input type="number" class="form-control" name="password_history" value="5" min="0" max="20">
                                <small class="text-muted">Number of previous passwords to remember</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="force_password_change" name="force_password_change">
                                    <label class="form-check-label" for="force_password_change">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Force Password Change</span>
                                            <small class="text-muted">Require change on first login</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="password_strength_meter" name="password_strength_meter" checked>
                                    <label class="form-check-label" for="password_strength_meter">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Password Strength Meter</span>
                                            <small class="text-muted">Show strength indicator</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Session Management Tab -->
                <div class="tab-pane fade" id="session" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="mb-3">Session Settings</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Session Timeout (minutes)</label>
                                <input type="number" class="form-control" name="session_timeout" value="120" min="5" max="1440">
                                <small class="text-muted">Auto-logout after inactivity</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Remember Me Duration (days)</label>
                                <input type="number" class="form-control" name="remember_duration" value="30" min="1" max="365">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="single_session" name="single_session">
                                    <label class="form-check-label" for="single_session">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Single Session Only</span>
                                            <small class="text-muted">One active session per user</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="session_ip_binding" name="session_ip_binding" checked>
                                    <label class="form-check-label" for="session_ip_binding">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>IP Binding</span>
                                            <small class="text-muted">Bind session to IP address</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <h5 class="mb-3">Active Sessions</h5>
                            
                            <div class="session-list">
                                <div class="session-item">
                                    <div class="session-info">
                                        <div class="session-user">
                                            <strong>Admin User</strong>
                                            <small class="text-muted">admin@elibrary.com</small>
                                        </div>
                                        <div class="session-details">
                                            <small class="text-muted">IP: 192.168.1.100</small><br>
                                            <small class="text-muted">Started: 2 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="session-actions">
                                        <span class="badge bg-success">Current</span>
                                        <button class="btn btn-sm btn-outline-danger">Terminate</button>
                                    </div>
                                </div>
                                
                                <div class="session-item">
                                    <div class="session-info">
                                        <div class="session-user">
                                            <strong>John Doe</strong>
                                            <small class="text-muted">john@example.com</small>
                                        </div>
                                        <div class="session-details">
                                            <small class="text-muted">IP: 192.168.1.105</small><br>
                                            <small class="text-muted">Started: 1 day ago</small>
                                        </div>
                                    </div>
                                    <div class="session-actions">
                                        <span class="badge bg-warning">Idle</span>
                                        <button class="btn btn-sm btn-outline-danger">Terminate</button>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="btn btn-outline-danger btn-sm mt-3" onclick="terminateAllSessions()">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                Terminate All Sessions
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Firewall & Access Tab -->
                <div class="tab-pane fade" id="firewall" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="mb-3">Firewall Rules</h5>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="enable_firewall" name="enable_firewall" checked>
                                    <label class="form-check-label" for="enable_firewall">
                                        Enable Firewall Protection
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Blocked IP Addresses</label>
                                <textarea class="form-control" name="blocked_ips" rows="4" placeholder="Enter IP addresses, one per line">192.168.1.50
10.0.0.25</textarea>
                                <small class="text-muted">These IPs will be blocked from accessing the system</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Allowed IP Addresses</label>
                                <textarea class="form-control" name="allowed_ips" rows="4" placeholder="Enter IP addresses, one per line">192.168.1.0/24
10.0.0.0/8</textarea>
                                <small class="text-muted">Only these IPs will be allowed (whitelist mode)</small>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <h5 class="mb-3">Rate Limiting</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">API Rate Limit (requests/minute)</label>
                                <input type="number" class="form-control" name="api_rate_limit" value="60" min="1" max="1000">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Login Rate Limit (attempts/minute)</label>
                                <input type="number" class="form-control" name="login_rate_limit" value="5" min="1" max="100">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="ddos_protection" name="ddos_protection" checked>
                                    <label class="form-check-label" for="ddos_protection">
                                        DDoS Protection
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="bot_protection" name="bot_protection" checked>
                                    <label class="form-check-label" for="bot_protection">
                                        Bot Protection
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monitoring Tab -->
                <div class="tab-pane fade" id="monitoring" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="mb-3">Security Monitoring</h5>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="log_security_events" name="log_security_events" checked>
                                    <label class="form-check-label" for="log_security_events">
                                        Log Security Events
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="email_alerts" name="email_alerts" checked>
                                    <label class="form-check-label" for="email_alerts">
                                        Email Security Alerts
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="suspicious_activity_detection" name="suspicious_activity_detection" checked>
                                    <label class="form-check-label" for="suspicious_activity_detection">
                                        Suspicious Activity Detection
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alert Threshold</label>
                                <select class="form-select" name="alert_threshold">
                                    <option value="low">Low - Only critical events</option>
                                    <option value="medium" selected>Medium - Important events</option>
                                    <option value="high">High - All security events</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <h5 class="mb-3">Recent Security Events</h5>
                            
                            <div class="security-events">
                                <div class="event-item critical">
                                    <div class="event-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="event-content">
                                        <strong>Multiple Failed Login Attempts</strong>
                                        <small class="text-muted">IP: 192.168.1.50 - 10 attempts in 5 minutes</small>
                                        <div class="event-time">2 minutes ago</div>
                                    </div>
                                </div>
                                
                                <div class="event-item warning">
                                    <div class="event-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="event-content">
                                        <strong>Suspicious Activity Detected</strong>
                                        <small class="text-muted">Unusual access pattern from user john@example.com</small>
                                        <div class="event-time">1 hour ago</div>
                                    </div>
                                </div>
                                
                                <div class="event-item info">
                                    <div class="event-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="event-content">
                                        <strong>Password Changed</strong>
                                        <small class="text-muted">Admin user changed password</small>
                                        <div class="event-time">3 hours ago</div>
                                    </div>
                                </div>
                                
                                <div class="event-item success">
                                    <div class="event-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="event-content">
                                        <strong>2FA Enabled</strong>
                                        <small class="text-muted">User jane@example.com enabled 2FA</small>
                                        <div class="event-time">5 hours ago</div>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="btn btn-outline-primary btn-sm mt-3" onclick="viewAllEvents()">
                                <i class="fas fa-list me-1"></i>
                                View All Events
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button class="btn btn-primary" onclick="saveSecuritySettings()">
                    <i class="fas fa-save me-1"></i>
                    Save Security Settings
                </button>
                <button class="btn btn-outline-secondary" onclick="resetToDefaults()">
                    <i class="fas fa-undo me-1"></i>
                    Reset to Defaults
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.security-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.security-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.security-card.overall {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.security-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #667eea;
}

.security-card.overall .security-icon {
    color: white;
}

.security-score {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.security-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.security-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.security-card.overall .security-label {
    color: white;
}

.session-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.session-info {
    flex: 1;
}

.session-user strong {
    display: block;
    margin-bottom: 0.25rem;
}

.session-details {
    font-size: 0.8rem;
}

.session-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.security-events {
    max-height: 300px;
    overflow-y: auto;
}

.event-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border-left: 4px solid #dee2e6;
    background: #f8f9fa;
    margin-bottom: 0.5rem;
    border-radius: 0 8px 8px 0;
}

.event-item.critical {
    border-left-color: #dc3545;
    background: #f8d7da;
}

.event-item.warning {
    border-left-color: #ffc107;
    background: #fff3cd;
}

.event-item.info {
    border-left-color: #17a2b8;
    background: #d1ecf1;
}

.event-item.success {
    border-left-color: #28a745;
    background: #d4edda;
}

.event-icon {
    margin-right: 1rem;
    font-size: 1.2rem;
    margin-top: 0.2rem;
}

.event-item.critical .event-icon {
    color: #dc3545;
}

.event-item.warning .event-icon {
    color: #ffc107;
}

.event-item.info .event-icon {
    color: #17a2b8;
}

.event-item.success .event-icon {
    color: #28a745;
}

.event-content {
    flex: 1;
}

.event-content strong {
    display: block;
    margin-bottom: 0.25rem;
}

.event-time {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.5rem;
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
</style>
@endpush

@push('scripts')
<script>
// Save security settings
function saveSecuritySettings() {
    // In a real application, this would save to the database
    alert('Security settings have been saved successfully!\n\nIn production, this would update your security configuration.');
}

// Reset to defaults
function resetToDefaults() {
    if (confirm('Are you sure you want to reset all security settings to their default values? This may affect your system security.')) {
        // Reset all form inputs to default values
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            // Set default values based on what should be enabled by default
            const defaults = {
                'two_factor_auth': true,
                'email_verification': true,
                'social_login': false,
                'ldap_auth': false,
                'ip_whitelist': false,
                'captcha_login': true,
                'require_uppercase': true,
                'require_lowercase': true,
                'require_numbers': true,
                'require_symbols': false,
                'force_password_change': false,
                'password_strength_meter': true,
                'single_session': false,
                'session_ip_binding': true,
                'enable_firewall': true,
                'ddos_protection': true,
                'bot_protection': true,
                'log_security_events': true,
                'email_alerts': true,
                'suspicious_activity_detection': true
            };
            
            checkbox.checked = defaults[checkbox.name] || false;
        });
        
        document.querySelectorAll('input[type="number"]').forEach(input => {
            // Set default numeric values
            const defaults = {
                'max_login_attempts': 5,
                'lockout_duration': 15,
                'min_password_length': 8,
                'password_expiry': 90,
                'password_history': 5,
                'session_timeout': 120,
                'remember_duration': 30,
                'api_rate_limit': 60,
                'login_rate_limit': 5
            };
            
            input.value = defaults[input.name] || input.value;
        });
        
        alert('Security settings have been reset to default values.');
    }
}

// Run security audit
function runSecurityAudit() {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Running Audit...';
    button.disabled = true;
    
    // Simulate security audit
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        const auditResults = `
Security Audit Complete

Overall Security Score: 85%
Issues Found: 3
Recommendations: 5

Critical Issues:
- Password policy could be stronger
- Consider enabling IP whitelisting for admin access
- Update SSL certificate (expires in 30 days)

Recommendations:
- Enable LDAP authentication for enterprise users
- Implement automated security scanning
- Set up security incident response team
- Regular security training for staff
- Implement zero-trust architecture

Next audit recommended in 30 days.
        `;
        
        alert(auditResults);
    }, 2000);
}

// Export security report
function exportSecurityReport() {
    // In a real application, this would generate and download a PDF/CSV report
    alert('Security report would be exported as PDF.\n\nIn production, this would generate a comprehensive security report including:\n- Current security settings\n- Recent security events\n- Audit trail\n- Recommendations\n- Compliance status');
}

// Terminate all sessions
function terminateAllSessions() {
    if (confirm('Are you sure you want to terminate all active sessions? This will log out all users including yourself.')) {
        alert('All sessions have been terminated successfully.\n\nIn production, this would invalidate all session tokens and force users to log in again.');
        
        // In a real application, you might redirect to login page
        // window.location.href = '/login';
    }
}

// View all events
function viewAllEvents() {
    alert('Security events log would open in a new page.\n\nIn production, this would show a paginated list of all security events with filtering and search capabilities.');
}

// Initialize tooltips and other interactive elements
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
    
    // Add confirmation for critical settings
    document.querySelectorAll('input[name="enable_firewall"], input[name="single_session"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                const message = this.name === 'enable_firewall' 
                    ? 'Enabling firewall may block legitimate users. Are you sure?'
                    : 'Single session mode will log users out from other devices. Are you sure?';
                
                if (!confirm(message)) {
                    this.checked = false;
                }
            }
        });
    });
});
</script>
@endpush
