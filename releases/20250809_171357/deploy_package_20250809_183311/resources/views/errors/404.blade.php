@extends('layouts.admin')

@section('title', 'Page Not Found')

@section('content')
<div class="error-page-container">
    <div class="error-content">
        <div class="error-illustration">
            <div class="error-icon">
                <i class="ti ti-search-off"></i>
            </div>
        </div>
        
        <div class="error-message">
            <h1 class="error-title">404</h1>
            <h2 class="error-subtitle">Oops! Chappy the page you're looking for is missing!</h2>
            <p class="error-description">
                It seems like this page has gone on a little adventure and can't be found. 
                Don't worry, Chappy is probably just exploring somewhere else!
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
                    If you believe this is an error, please contact our support team.
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
}

.error-illustration {
    margin-bottom: 2rem;
}

.error-icon {
    font-size: 6rem;
    color: #667eea;
    margin-bottom: 1rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.error-title {
    font-size: 4rem;
    font-weight: 700;
    color: #667eea;
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
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
}

.error-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.error-actions .btn-outline-secondary {
    border: 2px solid #667eea;
    color: #667eea;
    background: transparent;
}

.error-actions .btn-outline-secondary:hover {
    background: #667eea;
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