/**
 * Performance Monitor
 * Tracks page load performance and resource usage
 */

(function() {
    'use strict';
    
    // Performance monitoring
    window.addEventListener('load', function() {
        setTimeout(function() {
            measurePerformance();
        }, 100);
    });
    
    function measurePerformance() {
        // Get performance metrics
        var perfData = performance.getEntriesByType('navigation')[0];
        var resources = performance.getEntriesByType('resource');
        
        // Calculate metrics
        var metrics = {
            // Page load times
            domContentLoaded: perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart,
            loadComplete: perfData.loadEventEnd - perfData.loadEventStart,
            totalLoadTime: perfData.loadEventEnd - perfData.fetchStart,
            
            // Resource counts
            totalResources: resources.length,
            cssFiles: resources.filter(r => r.name.includes('.css')).length,
            jsFiles: resources.filter(r => r.name.includes('.js')).length,
            images: resources.filter(r => r.name.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i)).length,
            
            // Resource sizes
            totalSize: resources.reduce((sum, r) => sum + r.transferSize, 0),
            cssSize: resources.filter(r => r.name.includes('.css')).reduce((sum, r) => sum + r.transferSize, 0),
            jsSize: resources.filter(r => r.name.includes('.js')).reduce((sum, r) => sum + r.transferSize, 0),
            imageSize: resources.filter(r => r.name.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i)).reduce((sum, r) => sum + r.transferSize, 0)
        };
        
        // Log performance data
        console.log('🚀 Performance Metrics:', metrics);
        
        // Send to analytics if available
        if (typeof gtag !== 'undefined') {
            gtag('event', 'performance', {
                'event_category': 'page_load',
                'event_label': window.location.pathname,
                'value': Math.round(metrics.totalLoadTime),
                'custom_parameters': {
                    'dom_content_loaded': Math.round(metrics.domContentLoaded),
                    'total_resources': metrics.totalResources,
                    'total_size_kb': Math.round(metrics.totalSize / 1024)
                }
            });
        }
        
        // Store in localStorage for comparison
        var previousMetrics = JSON.parse(localStorage.getItem('performance_metrics') || '{}');
        var currentPage = window.location.pathname;
        
        if (previousMetrics[currentPage]) {
            var improvement = {
                loadTime: previousMetrics[currentPage].totalLoadTime - metrics.totalLoadTime,
                resources: previousMetrics[currentPage].totalResources - metrics.totalResources,
                size: previousMetrics[currentPage].totalSize - metrics.totalSize
            };
            
            console.log('📈 Performance Improvement:', {
                loadTimeReduction: Math.round(improvement.loadTime) + 'ms',
                resourceReduction: improvement.resources + ' files',
                sizeReduction: Math.round(improvement.size / 1024) + 'KB'
            });
        }
        
        // Save current metrics
        previousMetrics[currentPage] = metrics;
        localStorage.setItem('performance_metrics', JSON.stringify(previousMetrics));
        
        // Show performance indicator
        showPerformanceIndicator(metrics);
    }
    
    function showPerformanceIndicator(metrics) {
        // Create performance indicator
        var indicator = document.createElement('div');
        indicator.id = 'performance-indicator';
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 12px;
            font-family: monospace;
            z-index: 9999;
            opacity: 0.8;
            transition: opacity 0.3s ease;
            cursor: pointer;
        `;
        
        var loadTime = Math.round(metrics.totalLoadTime);
        var color = loadTime < 1000 ? '#28a745' : loadTime < 2000 ? '#ffc107' : '#dc3545';
        
        indicator.innerHTML = `
            <div style="color: ${color}; font-weight: bold;">
                ⚡ ${loadTime}ms
            </div>
            <div style="font-size: 10px; opacity: 0.8;">
                ${metrics.totalResources} resources
            </div>
        `;
        
        // Add click to expand
        indicator.addEventListener('click', function() {
            if (this.classList.contains('expanded')) {
                this.classList.remove('expanded');
                this.innerHTML = `
                    <div style="color: ${color}; font-weight: bold;">
                        ⚡ ${loadTime}ms
                    </div>
                    <div style="font-size: 10px; opacity: 0.8;">
                        ${metrics.totalResources} resources
                    </div>
                `;
            } else {
                this.classList.add('expanded');
                this.innerHTML = `
                    <div style="color: ${color}; font-weight: bold; margin-bottom: 5px;">
                        ⚡ Performance Details
                    </div>
                    <div style="font-size: 10px; line-height: 1.4;">
                        <div>Load Time: ${loadTime}ms</div>
                        <div>Resources: ${metrics.totalResources}</div>
                        <div>CSS: ${metrics.cssFiles} files</div>
                        <div>JS: ${metrics.jsFiles} files</div>
                        <div>Images: ${metrics.images} files</div>
                        <div>Total Size: ${Math.round(metrics.totalSize / 1024)}KB</div>
                    </div>
                `;
            }
        });
        
        // Add hover effect
        indicator.addEventListener('mouseenter', function() {
            this.style.opacity = '1';
        });
        
        indicator.addEventListener('mouseleave', function() {
            this.style.opacity = '0.8';
        });
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            indicator.style.opacity = '0.3';
        }, 5000);
        
        document.body.appendChild(indicator);
    }
    
    // Monitor resource loading
    var observer = new PerformanceObserver(function(list) {
        list.getEntries().forEach(function(entry) {
            if (entry.entryType === 'resource' && entry.duration > 1000) {
                console.warn('🐌 Slow resource:', entry.name, Math.round(entry.duration) + 'ms');
            }
        });
    });
    
    observer.observe({ entryTypes: ['resource'] });
    
    // Monitor long tasks
    if ('PerformanceObserver' in window) {
        var longTaskObserver = new PerformanceObserver(function(list) {
            list.getEntries().forEach(function(entry) {
                if (entry.duration > 50) {
                    console.warn('⏱️ Long task detected:', Math.round(entry.duration) + 'ms');
                }
            });
        });
        
        longTaskObserver.observe({ entryTypes: ['longtask'] });
    }
    
    // Monitor memory usage
    if ('memory' in performance) {
        setInterval(function() {
            var memory = performance.memory;
            if (memory.usedJSHeapSize > 50 * 1024 * 1024) { // 50MB
                console.warn('💾 High memory usage:', Math.round(memory.usedJSHeapSize / 1024 / 1024) + 'MB');
            }
        }, 10000);
    }
    
})(); 