@extends('layouts.admin')

@section('title', 'Server Error')

@section('content')
<div class="error-page-container">
    <div class="error-content">
        <div class="error-illustration">
            <div class="error-icon">
                <i class="ti ti-server-off"></i>
            </div>
        </div>
        
        <div class="error-message">
            <h1 class="error-title">500</h1>
            <h2 class="error-subtitle">Oops! Chappy is having a technical moment!</h2>
            <p class="error-description">
                It looks like our servers are taking a little break. 
                Chappy is working hard to fix this issue. Please try again in a few moments!
            </p>
            
            <div class="error-actions">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="ti ti-home me-2"></i>
                    Go Back Home
                </a>
                <button onclick="location.reload()" class="btn btn-outline-secondary btn-lg">
                    <i class="ti ti-refresh me-2"></i>
                    Try Again
                </button>
            </div>
            
            <div class="error-help">
                <p class="text-muted">
                    <i class="ti ti-info-circle me-1"></i>
                    If this problem persists, please contact our support team.
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
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
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
    background: linear-gradient(90deg, #ff6b6b, #ee5a24, #ff9ff3);
}

.error-illustration {
    margin-bottom: 2rem;
}

.error-icon {
    font-size: 6rem;
    color: #ff6b6b;
    margin-bottom: 1rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.error-title {
    font-size: 4rem;
    font-weight: 700;
    color: #ff6b6b;
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
    border: none;
    cursor: pointer;
}

.error-actions .btn-primary {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
}

.error-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
}

.error-actions .btn-outline-secondary {
    border: 2px solid #ff6b6b;
    color: #ff6b6b;
    background: transparent;
}

.error-actions .btn-outline-secondary:hover {
    background: #ff6b6b;
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