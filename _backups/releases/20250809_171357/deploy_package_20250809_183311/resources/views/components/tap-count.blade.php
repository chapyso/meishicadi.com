{{-- Card Views functionality has been removed --}}

<style>
    .tap-count-container {
        padding: 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        margin: 10px 0;
    }
    
    .tap-count-display {
        margin-bottom: 15px;
    }
    
    .tap-count-number {
        font-size: 2.5em;
        font-weight: bold;
        color: #fff;
        display: block;
    }
    
    .tap-count-label {
        font-size: 1.2em;
        color: #fff;
        opacity: 0.8;
    }
    
    .tap-count-button {
        background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-size: 1.1em;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .tap-count-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        background: linear-gradient(45deg, #ee5a24, #ff6b6b);
    }
    
    .tap-count-button:active {
        transform: translateY(0);
    }
    
    .tap-count-button i {
        margin-right: 8px;
    }
    
    .tap-count-button.loading {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>

<script>
    // Load initial tap count
    document.addEventListener('DOMContentLoaded', function() {
        loadTapCount({{ $business->id }});
    });

    function loadTapCount(businessId) {
        fetch(`/tap-analytics/count/${businessId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`tap-count-${businessId}`).textContent = data.tap_count;
                }
            })
            .catch(error => {
                console.error('Error loading tap count:', error);
            });
    }

    function incrementTapCount(businessId) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        // Add loading state
        button.classList.add('loading');
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Recording...") }}';
        
        fetch('/tap-analytics/increment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                business_id: businessId,
                tap_source: 'Button'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the count display
                document.getElementById(`tap-count-${businessId}`).textContent = data.new_count;
                
                // Show success feedback
                button.innerHTML = '<i class="fas fa-check"></i> {{ __("Recorded!") }}';
                setTimeout(() => {
                    button.classList.remove('loading');
                    button.innerHTML = originalText;
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Error incrementing tap count:', error);
            button.classList.remove('loading');
            button.innerHTML = originalText;
        });
    }
</script> 