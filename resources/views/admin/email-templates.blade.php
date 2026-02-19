@extends('layouts.admin')

@section('title', 'Email Templates - E-Library')

@section('page-title', 'Email Templates')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-envelope me-2"></i>
                Email Templates
            </h4>
            <p class="text-muted mb-0">Manage email templates for notifications and communications</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="createNewTemplate()">
                <i class="fas fa-plus me-1"></i>
                Create Template
            </button>
            <button class="btn btn-outline-secondary" onclick="testAllTemplates()">
                <i class="fas fa-paper-plane me-1"></i>
                Test Templates
            </button>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Available Templates
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Welcome Email Template -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="template-card">
                                <div class="template-header">
                                    <div class="template-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="template-info">
                                        <h6 class="template-name">Welcome Email</h6>
                                        <span class="template-type">User Registration</span>
                                    </div>
                                </div>
                                <div class="template-content">
                                    <p class="template-description">Sent to new users when they register an account</p>
                                    <div class="template-variables">
                                        <small class="text-muted">Variables: {name}, {email}, {verification_link}</small>
                                    </div>
                                </div>
                                <div class="template-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editTemplate('welcome')">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="previewTemplate('welcome')">
                                        <i class="fas fa-eye me-1"></i>
                                        Preview
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="testTemplate('welcome')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Test
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Password Reset Template -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="template-card">
                                <div class="template-header">
                                    <div class="template-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div class="template-info">
                                        <h6 class="template-name">Password Reset</h6>
                                        <span class="template-type">Security</span>
                                    </div>
                                </div>
                                <div class="template-content">
                                    <p class="template-description">Sent when users request to reset their password</p>
                                    <div class="template-variables">
                                        <small class="text-muted">Variables: {name}, {reset_link}, {expiry_time}</small>
                                    </div>
                                </div>
                                <div class="template-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editTemplate('password_reset')">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="previewTemplate('password_reset')">
                                        <i class="fas fa-eye me-1"></i>
                                        Preview
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="testTemplate('password_reset')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Test
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Email Verification Template -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="template-card">
                                <div class="template-header">
                                    <div class="template-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="template-info">
                                        <h6 class="template-name">Email Verification</h6>
                                        <span class="template-type">Verification</span>
                                    </div>
                                </div>
                                <div class="template-content">
                                    <p class="template-description">Sent to verify user email addresses</p>
                                    <div class="template-variables">
                                        <small class="text-muted">Variables: {name}, {verification_link}, {expiry_time}</small>
                                    </div>
                                </div>
                                <div class="template-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editTemplate('email_verification')">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="previewTemplate('email_verification')">
                                        <i class="fas fa-eye me-1"></i>
                                        Preview
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="testTemplate('email_verification')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Test
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Book Download Template -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="template-card">
                                <div class="template-header">
                                    <div class="template-icon">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="template-info">
                                        <h6 class="template-name">Book Download</h6>
                                        <span class="template-type">Notification</span>
                                    </div>
                                </div>
                                <div class="template-content">
                                    <p class="template-description">Sent when users download books</p>
                                    <div class="template-variables">
                                        <small class="text-muted">Variables: {name}, {book_title}, {download_link}</small>
                                    </div>
                                </div>
                                <div class="template-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editTemplate('book_download')">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="previewTemplate('book_download')">
                                        <i class="fas fa-eye me-1"></i>
                                        Preview
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="testTemplate('book_download')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Test
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Reading Reminder Template -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="template-card">
                                <div class="template-header">
                                    <div class="template-icon">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="template-info">
                                        <h6 class="template-name">Reading Reminder</h6>
                                        <span class="template-type">Reminder</span>
                                    </div>
                                </div>
                                <div class="template-content">
                                    <p class="template-description">Sent to remind users about their reading progress</p>
                                    <div class="template-variables">
                                        <small class="text-muted">Variables: {name}, {book_title}, {progress}, {continue_link}</small>
                                    </div>
                                </div>
                                <div class="template-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editTemplate('reading_reminder')">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="previewTemplate('reading_reminder')">
                                        <i class="fas fa-eye me-1"></i>
                                        Preview
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="testTemplate('reading_reminder')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Test
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Account Suspended Template -->
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="template-card">
                                <div class="template-header">
                                    <div class="template-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="template-info">
                                        <h6 class="template-name">Account Suspended</h6>
                                        <span class="template-type">Security</span>
                                    </div>
                                </div>
                                <div class="template-content">
                                    <p class="template-description">Sent when user accounts are suspended</p>
                                    <div class="template-variables">
                                        <small class="text-muted">Variables: {name}, {reason}, {contact_email}</small>
                                    </div>
                                </div>
                                <div class="template-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editTemplate('account_suspended')">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="previewTemplate('account_suspended')">
                                        <i class="fas fa-eye me-1"></i>
                                        Preview
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="testTemplate('account_suspended')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Test
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Settings Section -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Email Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mail Driver</label>
                        <select class="form-select" name="mail_driver">
                            <option value="smtp" selected>SMTP</option>
                            <option value="mail">PHP Mail</option>
                            <option value="sendmail">Sendmail</option>
                            <option value="mailgun">Mailgun</option>
                            <option value="ses">Amazon SES</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">From Email</label>
                        <input type="email" class="form-control" name="from_email" value="noreply@elibrary.com" placeholder="From email address">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">From Name</label>
                        <input type="text" class="form-control" name="from_name" value="E-Library" placeholder="From name">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="queue_emails" name="queue_emails" checked>
                            <label class="form-check-label" for="queue_emails">
                                Queue Emails
                                <small class="text-muted d-block">Process emails in the background</small>
                            </label>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary" onclick="saveEmailSettings()">
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
                        <i class="fas fa-chart-line me-2"></i>
                        Email Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-item">
                                <h4 class="stat-number">1,234</h4>
                                <p class="stat-label">Sent Today</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h4 class="stat-number">98.5%</h4>
                                <p class="stat-label">Delivered</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h4 class="stat-number">45</h4>
                                <p class="stat-label">Failed</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Recent Email Activity</label>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="activity-content">
                                    <small class="text-muted">2 minutes ago</small>
                                    <p class="mb-0">Welcome email sent to john@example.com</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="activity-content">
                                    <small class="text-muted">5 minutes ago</small>
                                    <p class="mb-0">Password reset email sent to jane@example.com</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon error">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="activity-content">
                                    <small class="text-muted">10 minutes ago</small>
                                    <p class="mb-0">Failed to send book download notification</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template Editor Modal -->
<div class="modal fade" id="templateEditorModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Email Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Template Name</label>
                            <input type="text" class="form-control" id="template_name" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subject</label>
                            <input type="text" class="form-control" id="template_subject" placeholder="Email subject">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Available Variables</label>
                            <div class="variables-list">
                                <small class="text-muted">Click to insert variables:</small>
                                <div class="variable-tags">
                                    <span class="variable-tag" onclick="insertVariable('{name}')">{name}</span>
                                    <span class="variable-tag" onclick="insertVariable('{email}')">{email}</span>
                                    <span class="variable-tag" onclick="insertVariable('{verification_link}')">{verification_link}</span>
                                    <span class="variable-tag" onclick="insertVariable('{reset_link}')">{reset_link}</span>
                                    <span class="variable-tag" onclick="insertVariable('{book_title}')">{book_title}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Content</label>
                            <textarea class="form-control" id="template_content" rows="15" placeholder="Email content"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preview</label>
                            <div class="preview-box">
                                <div id="template_preview">
                                    <!-- Preview will be shown here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveTemplate()">Save Template</button>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="email_preview_content">
                    <!-- Email preview will be shown here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.template-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 1.5rem;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.template-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.template-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.template-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 1rem;
}

.template-name {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #2c3e50;
}

.template-type {
    font-size: 0.8rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.template-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.template-variables {
    margin-bottom: 1rem;
}

.template-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.stat-item {
    padding: 1rem;
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0;
}

.activity-list {
    max-height: 200px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.activity-icon.success {
    background: #28a745;
    color: white;
}

.activity-icon.error {
    background: #dc3545;
    color: white;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    font-size: 0.9rem;
    margin-bottom: 0;
}

.variable-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.variable-tag {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.variable-tag:hover {
    background: #dee2e6;
}

.preview-box {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    background: #f8f9fa;
    min-height: 200px;
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
</style>
@endpush

@push('scripts')
<script>
// Template data
const templates = {
    welcome: {
        name: 'Welcome Email',
        subject: 'Welcome to E-Library - Your Digital Library Awaits!',
        content: `Dear {name},

Welcome to E-Library! We're excited to have you join our community of readers and learners.

Your account has been successfully created with the email: {email}

To get started, please verify your email address by clicking the link below:
{verification_link}

Once verified, you'll be able to:
- Browse our extensive collection of books
- Create your personal reading lists
- Track your reading progress
- Download books for offline reading

If you have any questions or need assistance, don't hesitate to contact our support team.

Happy reading!

The E-Library Team`
    },
    password_reset: {
        name: 'Password Reset',
        subject: 'Reset Your E-Library Password',
        content: `Dear {name},

We received a request to reset your E-Library password. If you didn't make this request, you can safely ignore this email.

To reset your password, click the link below:
{reset_link}

This link will expire in {expiry_time}. After that, you'll need to request a new password reset.

For your security, please make sure to:
- Choose a strong password with at least 8 characters
- Include a mix of letters, numbers, and symbols
- Don't reuse passwords from other accounts

If you continue to have trouble accessing your account, please contact our support team.

Best regards,
The E-Library Team`
    },
    email_verification: {
        name: 'Email Verification',
        subject: 'Verify Your E-Library Email Address',
        content: `Dear {name},

Thank you for registering with E-Library! To complete your registration and activate your account, please verify your email address.

Click the link below to verify your email:
{verification_link}

This verification link will expire in {expiry_time} for security reasons.

Once verified, you'll have full access to:
- Our complete book collection
- Personalized recommendations
- Reading progress tracking
- Community features

If you didn't create an account with E-Library, you can safely ignore this email.

Welcome to our reading community!

The E-Library Team`
    },
    book_download: {
        name: 'Book Download',
        subject: 'Your Book Download from E-Library',
        content: `Dear {name},

Great news! Your book download is ready.

Book Details:
Title: {book_title}
Download Link: {download_link}

Your download link will be available for 24 hours. After that, you can request a new download link from your library.

We hope you enjoy reading this book! Don't forget to:
- Rate and review the book when you're done
- Add it to your favorites if you liked it
- Share your reading progress with friends

Happy reading!

The E-Library Team`
    },
    reading_reminder: {
        name: 'Reading Reminder',
        subject: 'Continue Reading: {book_title}',
        content: `Dear {name},

We noticed you haven't finished reading "{book_title}" yet. You're currently {progress} through the book!

Would you like to continue where you left off?
{continue_link}

Reading reminders can help you:
- Stay motivated to finish books
- Maintain consistent reading habits
- Make the most of your E-Library membership

If you'd like to adjust your reminder settings, visit your account preferences.

Keep reading!

The E-Library Team`
    },
    account_suspended: {
        name: 'Account Suspended',
        subject: 'Important: Your E-Library Account Status',
        content: `Dear {name},

We're writing to inform you that your E-Library account has been suspended due to the following reason:
{reason}

What this means:
- Your account access is temporarily restricted
- You cannot download new books
- Your reading history is preserved

If you believe this suspension is in error or would like to discuss this matter further, please contact our support team at:
{contact_email}

We're here to help resolve any issues and restore your account access as soon as possible.

Sincerely,
The E-Library Team`
    }
};

// Edit template
function editTemplate(templateId) {
    const template = templates[templateId];
    if (template) {
        document.getElementById('template_name').value = template.name;
        document.getElementById('template_subject').value = template.subject;
        document.getElementById('template_content').value = template.content;
        
        updatePreview();
        
        const modal = new bootstrap.Modal(document.getElementById('templateEditorModal'));
        modal.show();
    }
}

// Preview template
function previewTemplate(templateId) {
    const template = templates[templateId];
    if (template) {
        const previewContent = `
            <div class="email-preview">
                <h6>Subject: ${template.subject}</h6>
                <hr>
                <pre style="white-space: pre-wrap; font-family: inherit;">${template.content}</pre>
            </div>
        `;
        
        document.getElementById('email_preview_content').innerHTML = previewContent;
        
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }
}

// Test template
function testTemplate(templateId) {
    const template = templates[templateId];
    if (template) {
        // In a real application, this would send a test email
        alert(`Test email would be sent for template: ${template.name}\n\nIn production, this would send the email to your test address.`);
    }
}

// Create new template
function createNewTemplate() {
    document.getElementById('template_name').value = '';
    document.getElementById('template_subject').value = '';
    document.getElementById('template_content').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('templateEditorModal'));
    modal.show();
}

// Test all templates
function testAllTemplates() {
    alert('Test emails would be sent for all templates.\n\nIn production, this would send test versions of all email templates to your test address.');
}

// Insert variable
function insertVariable(variable) {
    const textarea = document.getElementById('template_content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + variable.length;
    textarea.focus();
    
    updatePreview();
}

// Update preview
function updatePreview() {
    const content = document.getElementById('template_content').value;
    document.getElementById('template_preview').innerHTML = `<pre style="white-space: pre-wrap; font-family: inherit;">${content || 'Preview will appear here...'}</pre>`;
}

// Save template
function saveTemplate() {
    const name = document.getElementById('template_name').value;
    const subject = document.getElementById('template_subject').value;
    const content = document.getElementById('template_content').value;
    
    // In a real application, this would save to the database
    alert(`Template "${name}" has been saved successfully!\n\nIn production, this would update the database with your changes.`);
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('templateEditorModal'));
    modal.hide();
}

// Save email settings
function saveEmailSettings() {
    // In a real application, this would save to config/database
    alert('Email settings have been saved successfully!\n\nIn production, this would update your email configuration.');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for content textarea
    document.getElementById('template_content')?.addEventListener('input', updatePreview);
});
</script>
@endpush
