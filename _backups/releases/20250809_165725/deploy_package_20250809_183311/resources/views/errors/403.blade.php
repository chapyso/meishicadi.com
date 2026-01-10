@extends('layouts.admin')

@section('title', 'Access Forbidden')

@section('content')
<div class="error-page-container">
    <div class="error-content">
        <div class="error-illustration">
            <div class="error-icon">
                <i class="ti ti-lock"></i>
            </div>
        </div>
        
        <div class="error-message">
            <h1 class="error-title">403</h1>
            <h2 class="error-subtitle">Oops! Chappy doesn't have permission to access this page!</h2>
            <p class="error-description">
                It looks like this area is restricted. Chappy needs special access to explore this part of the website.
            </p>
            
            <div class="error-actions">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="ti ti-home me-2"></i>
                    Go Back Home
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                    <i class="ti ti-arrow-left me-2"></i>
                    Go Back
                </a>
            </div>
            
            <div class="error-help">
                <p class="text-muted">
                    <i class="ti ti-info-circle me-1"></i>
                    If you believe you should have access, please contact your administrator.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.error-page-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: linear-gradient(135deg, #feca57 0%, #ff9ff3 100%);
}

.error-content {
    text-align: center;
    max-width: 600px;
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.error-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #feca57, #ff9ff3, #48dbfb);
}

.error-illustration {
    margin-bottom: 2rem;
}

.error-icon {
    font-size: 6rem;
    color: #feca57;
    margin-bottom: 1rem;
    animation: shake 2s infinite;
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translateX(-5px);
    }
    20%, 40%, 60%, 80% {
        transform: translateX(5px);
    }
}

.error-title {
    font-size: 4rem;
    font-weight: 700;
    color: #feca57;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.error-subtitle {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 1rem;
    font-weight: 600;
}

.error-description {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.error-actions .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.error-actions .btn-primary {
    background: linear-gradient(135deg, #feca57, #ff9ff3);
    border: none;
    color: white;
}

.error-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(254, 202, 87, 0.3);
}

.error-actions .btn-outline-secondary {
    border: 2px solid #feca57;
    color: #feca57;
    background: transparent;
}

.error-actions .btn-outline-secondary:hover {
    background: #feca57;
    color: white;
    transform: translateY(-2px);
}

.error-help {
    border-top: 1px solid #eee;
    padding-top: 1.5rem;
}

.error-help p {
    margin: 0;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .error-page-container {
        padding: 1rem;
    }
    
    .error-content {
        padding: 2rem 1.5rem;
    }
    
    .error-title {
        font-size: 3rem;
    }
    
    .error-subtitle {
        font-size: 1.25rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .error-actions .btn {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .error-content {
        background: #1a1a1a;
        color: white;
    }
    
    .error-subtitle {
        color: #fff;
    }
    
    .error-description {
        color: #ccc;
    }
    
    .error-help {
        border-top-color: #333;
    }
}
</style>
@endsection 