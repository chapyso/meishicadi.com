@extends('layouts.admin')

@section('page-title')
    {{ __('Bulk Transfer') }}
@endsection

@section('content')
<div class="bulk-transfer-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">{{ __('Bulk Transfer') }}</h1>
        <p class="hero-subtitle">{{ __('Send multiple transfers efficiently with our modern bulk transfer system') }}</p>
    </div>

    <!-- Main Transfer Area -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Transfer Form Card -->
                <div class="transfer-form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-send me-2"></i>
                            {{ __('New Bulk Transfer') }}
                        </h3>
                        <p class="card-subtitle">{{ __('Create a new bulk transfer request') }}</p>
                </div>

                                <div class="card-body">
                        <form id="bulkTransferForm">
                            <!-- Transfer Details Section -->
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="ti ti-info-circle me-2"></i>
                                    {{ __('Transfer Details') }}
                                </h4>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Sender Name') }}</label>
                                            <input type="text" class="form-control" id="senderName" placeholder="{{ __('Enter sender name') }}" required>
                                        </div>
                                        </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Sender Email') }}</label>
                                            <input type="email" class="form-control" id="senderEmail" placeholder="{{ __('Enter sender email') }}" required>
                                        </div>
                                        </div>
                                        </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Transfer Title') }}</label>
                                            <input type="text" class="form-control" id="transferTitle" placeholder="{{ __('Enter transfer title') }}" required>
                                    </div>
                                </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Reference Number') }}</label>
                                            <input type="text" class="form-control" id="referenceNumber" placeholder="{{ __('Enter reference number') }}">
                            </div>
                        </div>
                                    </div>

                                <div class="form-group mt-3">
                                    <label class="form-label">{{ __('Message (Optional)') }}</label>
                                    <textarea class="form-control" id="transferMessage" rows="3" placeholder="{{ __('Enter a message for recipients') }}"></textarea>
                        </div>
                    </div>

                    <!-- File Upload Section -->
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="ti ti-upload me-2"></i>
                                    {{ __('Upload Transfer Data') }}
                                </h4>
                                
                                <div class="upload-area" id="uploadArea">
                                    <div class="upload-content">
                                        <div class="upload-icon">
                                        <i class="ti ti-cloud-upload"></i>
                                    </div>
                                        <h5 class="upload-title">{{ __('Upload CSV or Excel File') }}</h5>
                                        <p class="upload-description">{{ __('Drag and drop your file here or click to browse') }}</p>
                                        <div class="upload-actions">
                                            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                                <i class="ti ti-upload me-2"></i>{{ __('Choose File') }}
                                        </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="downloadTemplate()">
                                                <i class="ti ti-download me-2"></i>{{ __('Download Template') }}
                                            </button>
                                    </div>
                                        <input type="file" id="fileInput" accept=".csv,.xlsx,.xls" class="d-none">
                                </div>
                                </div>

                                <!-- File Preview -->
                                <div id="filePreview" class="file-preview" style="display: none;">
                                    <div class="preview-header">
                                        <h6 class="preview-title">{{ __('File Preview') }}</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                            <i class="ti ti-x"></i>
                                    </button>
                                </div>
                                    <div class="preview-content" id="previewContent"></div>
                            </div>
                        </div>

                            <!-- Manual Entry Section -->
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="ti ti-edit me-2"></i>
                                    {{ __('Manual Entry') }}
                                </h4>
                                
                                <div class="manual-entry">
                                    <div class="entry-header">
                                        <h6 class="entry-title">{{ __('Add Recipients Manually') }}</h6>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addRecipient()">
                                            <i class="ti ti-plus me-1"></i>{{ __('Add Recipient') }}
                                        </button>
                    </div>

                                    <div class="recipients-list" id="recipientsList">
                                        <!-- Recipients will be added here dynamically -->
                                    </div>
                                </div>
                            </div>

                            <!-- Transfer Options -->
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="ti ti-settings me-2"></i>
                                    {{ __('Transfer Options') }}
                                </h4>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Transfer Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="transferAmount" placeholder="0.00" step="0.01" required>
                        </div>
                    </div>
                </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Expiry Date') }}</label>
                                            <input type="date" class="form-control" id="expiryDate" required>
            </div>
        </div>
    </div>

                                <div class="form-group mt-3">
                                    <label class="form-label">{{ __('Security Level') }}</label>
                                    <select class="form-control" id="securityLevel">
                                        <option value="standard">{{ __('Standard') }}</option>
                                        <option value="enhanced">{{ __('Enhanced') }}</option>
                                        <option value="premium">{{ __('Premium') }}</option>
                                    </select>
                                </div>
</div>

                            <!-- Action Buttons -->
                            <div class="form-actions">
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                                        <i class="ti ti-refresh me-2"></i>{{ __('Clear') }}
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="validateForm()">
                                        <i class="ti ti-check me-2"></i>{{ __('Validate') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-send me-2"></i>{{ __('Submit Transfer') }}
                                    </button>
            </div>
                        </div>
                        </form>
                        </div>
                    </div>

                <!-- Preview Section -->
                <div class="preview-section" id="previewSection" style="display: none;">
                    <div class="preview-card">
                        <div class="card-header">
                            <h4 class="preview-title">
                                <i class="ti ti-eye me-2"></i>
                                {{ __('Transfer Summary') }}
                            </h4>
                    </div>
                        <div class="card-body">
                            <div class="summary-content" id="summaryContent">
                                <!-- Summary will be populated here -->
                </div>
                </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection

@push('css-page')
<style>
.bulk-transfer-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.hero-section {
    text-align: center;
    margin-bottom: 3rem;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.hero-subtitle {
    font-size: 1.1rem;
    color: #718096;
    max-width: 600px;
    margin: 0 auto;
}

.transfer-form-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: none;
    overflow: hidden;
    margin-bottom: 2rem;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.card-title {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.card-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
}

.card-body {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f7fafc;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
    outline: none;
}

.input-group-text {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-right: none;
    color: #4a5568;
    font-weight: 500;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-left: none;
}

/* Upload Area */
.upload-area {
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    background: #f7fafc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #667eea;
    background: #edf2f7;
}

.upload-area.dragover {
    border-color: #667eea;
    background: #e6fffa;
}

.upload-icon {
    font-size: 3rem;
    color: #667eea;
    margin-bottom: 1rem;
}

.upload-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.upload-description {
    color: #718096;
    margin-bottom: 1.5rem;
}

.upload-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* File Preview */
.file-preview {
    margin-top: 1.5rem;
    background: #f7fafc;
    border-radius: 8px;
    padding: 1rem;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.preview-title {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

/* Manual Entry */
.manual-entry {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1.5rem;
}

.entry-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.entry-title {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.recipients-list {
    max-height: 300px;
    overflow-y: auto;
}

.recipient-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.recipient-info {
    flex: 1;
}

.recipient-name {
    font-weight: 500;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.recipient-email {
    color: #718096;
    font-size: 0.9rem;
}

.recipient-actions {
    display: flex;
    gap: 0.5rem;
}

/* Action Buttons */
.form-actions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-info {
    background: #4299e1;
    color: white;
}

.btn-info:hover {
    background: #3182ce;
}

.btn-outline-secondary {
    background: transparent;
    color: #718096;
    border: 2px solid #e2e8f0;
}

.btn-outline-secondary:hover {
    background: #f7fafc;
    color: #4a5568;
}

.btn-outline-danger {
    background: transparent;
    color: #e53e3e;
    border: 2px solid #fed7d7;
}

.btn-outline-danger:hover {
    background: #fed7d7;
    color: #c53030;
}

/* Preview Section */
.preview-section {
    margin-top: 2rem;
}

.preview-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: none;
    overflow: hidden;
}

.preview-card .card-header {
    background: #48bb78;
    color: white;
    padding: 1.5rem 2rem;
}

.preview-card .card-body {
    padding: 2rem;
}

.summary-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.summary-item {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1rem;
}

.summary-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.summary-value {
    font-weight: 600;
    color: #2d3748;
    font-size: 1.1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .card-header {
        padding: 1.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .upload-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .recipient-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .recipient-actions {
        align-self: flex-end;
    }
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>

<script>
let selectedFile = null;
let recipients = [];

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    setupFileUpload();
    setupFormValidation();
    setDefaultExpiryDate();
});

// File Upload Setup
function setupFileUpload() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');

    // Drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('dragover');
    }

    uploadArea.addEventListener('drop', handleDrop, false);
    fileInput.addEventListener('change', handleFileSelect);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
    }
}

function handleFiles(files) {
    if (files.length > 0) {
        selectedFile = files[0];
        showFilePreview(selectedFile);
    }
}

function showFilePreview(file) {
    const preview = document.getElementById('filePreview');
    const content = document.getElementById('previewContent');
    
    content.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="ti ti-file-text me-2" style="font-size: 1.5rem; color: #667eea;"></i>
            <div>
                <strong>${file.name}</strong>
                <br>
                <small class="text-muted">${formatFileSize(file.size)}</small>
            </div>
        </div>
    `;
    
    preview.style.display = 'block';
}

function removeFile() {
    selectedFile = null;
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('fileInput').value = '';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Manual Entry Functions
function addRecipient() {
    const recipientId = Date.now();
    const recipient = {
        id: recipientId,
        name: '',
        email: '',
        amount: ''
    };
    
    recipients.push(recipient);
    displayRecipients();
}

function removeRecipient(id) {
    recipients = recipients.filter(r => r.id !== id);
    displayRecipients();
}

function updateRecipient(id, field, value) {
    const recipient = recipients.find(r => r.id === id);
    if (recipient) {
        recipient[field] = value;
    }
}

function displayRecipients() {
    const container = document.getElementById('recipientsList');
    
    if (recipients.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="ti ti-users mb-2" style="font-size: 2rem;"></i>
                <p>No recipients added yet. Click "Add Recipient" to get started.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = recipients.map(recipient => `
        <div class="recipient-item">
            <div class="recipient-info">
                <input type="text" 
                       class="form-control mb-2" 
                       placeholder="Recipient Name" 
                       value="${recipient.name}"
                       onchange="updateRecipient(${recipient.id}, 'name', this.value)">
                <input type="email" 
                       class="form-control" 
                       placeholder="Recipient Email" 
                       value="${recipient.email}"
                       onchange="updateRecipient(${recipient.id}, 'email', this.value)">
            </div>
            <div class="recipient-actions">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRecipient(${recipient.id})">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
}

// Form Functions
function setDefaultExpiryDate() {
    const today = new Date();
    const expiryDate = new Date(today.getTime() + (7 * 24 * 60 * 60 * 1000)); // 7 days from now
    document.getElementById('expiryDate').value = expiryDate.toISOString().split('T')[0];
}

function validateForm() {
    const form = document.getElementById('bulkTransferForm');
    const isValid = form.checkValidity();
    
    if (isValid) {
        showPreview();
        showNotification('Form validation successful!', 'success');
    } else {
        showNotification('Please fill in all required fields.', 'error');
    }
}

function clearForm() {
    document.getElementById('bulkTransferForm').reset();
    selectedFile = null;
    recipients = [];
    removeFile();
    displayRecipients();
    setDefaultExpiryDate();
    hidePreview();
    showNotification('Form cleared successfully!', 'success');
}

function showPreview() {
    const preview = document.getElementById('previewSection');
    const content = document.getElementById('summaryContent');
    
    const formData = {
        senderName: document.getElementById('senderName').value,
        senderEmail: document.getElementById('senderEmail').value,
        transferTitle: document.getElementById('transferTitle').value,
        referenceNumber: document.getElementById('referenceNumber').value,
        transferAmount: document.getElementById('transferAmount').value,
        expiryDate: document.getElementById('expiryDate').value,
        securityLevel: document.getElementById('securityLevel').value,
        recipientsCount: recipients.length,
        hasFile: selectedFile !== null
    };
    
    content.innerHTML = `
        <div class="summary-item">
            <div class="summary-label">Sender</div>
            <div class="summary-value">${formData.senderName}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Transfer Title</div>
            <div class="summary-value">${formData.transferTitle}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Amount</div>
            <div class="summary-value">$${formData.transferAmount}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Recipients</div>
            <div class="summary-value">${formData.recipientsCount}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Expiry Date</div>
            <div class="summary-value">${formData.expiryDate}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Security Level</div>
            <div class="summary-value">${formData.securityLevel}</div>
        </div>
    `;
    
    preview.style.display = 'block';
}

function hidePreview() {
    document.getElementById('previewSection').style.display = 'none';
}

function downloadTemplate() {
    const csvContent = "Recipient Name,Recipient Email,Amount,Reference\nJohn Doe,john@example.com,100.00,REF001\nJane Smith,jane@example.com,250.00,REF002";
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'bulk_transfer_template.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function setupFormValidation() {
    const form = document.getElementById('bulkTransferForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitTransfer();
    });
}

function submitTransfer() {
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<span class="spinner me-2"></span>Processing...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        showNotification('Bulk transfer submitted successfully!', 'success');
        clearForm();
    }, 2000);
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endpush 