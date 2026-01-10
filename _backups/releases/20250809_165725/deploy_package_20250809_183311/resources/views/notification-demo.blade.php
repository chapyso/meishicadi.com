<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Notification UI Demo</title>
    <link rel="stylesheet" href="{{ asset('css/notification-ui.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .demo-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .demo-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .demo-header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        
        .demo-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1em;
        }
        
        .demo-content {
            padding: 40px;
        }
        
        .demo-section {
            margin-bottom: 40px;
        }
        
        .demo-section h2 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .notification-examples {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .example-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e9ecef;
            position: relative;
        }
        
        .example-item h3 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 1.1em;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background: white;
            border-radius: 6px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .menu-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .menu-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
        }
        
        .menu-text {
            flex: 1;
            font-weight: 500;
            color: #333;
        }
        
        .controls {
            background: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .control-group {
            margin-bottom: 15px;
        }
        
        .control-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #495057;
        }
        
        .control-group input, .control-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #5a6fd8;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .feature-card h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .feature-card p {
            margin: 0;
            color: #666;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="demo-container">
        <div class="demo-header">
            <h1>Enhanced Notification UI</h1>
            <p>Modern, accessible, and interactive notification system for your admin dashboard</p>
        </div>
        
        <div class="demo-content">
            <div class="demo-section">
                <h2>Notification Examples</h2>
                <div class="notification-examples">
                    <div class="example-item">
                        <h3>Pending Appointments</h3>
                        <div class="menu-item">
                            <div class="menu-icon">
                                <i class="ri-calendar-time-line"></i>
                            </div>
                            <div class="menu-text">Appointments</div>
                            <span class="notification-badge notification-badge--pending" title="5 pending appointments">5</span>
                        </div>
                    </div>
                    
                    <div class="example-item">
                        <h3>Low Wallet Balance</h3>
                        <div class="menu-item">
                            <div class="menu-icon">
                                <i class="ri-wallet-3-line"></i>
                            </div>
                            <div class="menu-text">Wallet</div>
                            <span class="notification-badge notification-badge--warning" title="Low wallet balance">
                                <i class="ri-alert-line"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="example-item">
                        <h3>Plan Expired</h3>
                        <div class="menu-item">
                            <div class="menu-icon">
                                <i class="ri-error-warning-line"></i>
                            </div>
                            <div class="menu-text">Plan Expired</div>
                            <span class="notification-badge notification-badge--danger" title="Plan has expired">
                                <i class="ri-alert-line"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="example-item">
                        <h3>Support Available</h3>
                        <div class="menu-item">
                            <div class="menu-icon">
                                <i class="ri-customer-service-2-line"></i>
                            </div>
                            <div class="menu-text">Contact Support</div>
                            <span class="notification-badge notification-badge--info" title="24/7 Support Available">24/7</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="demo-section">
                <h2>Interactive Controls</h2>
                <div class="controls">
                    <div class="control-group">
                        <label for="notificationType">Notification Type:</label>
                        <select id="notificationType">
                            <option value="pending">Pending</option>
                            <option value="warning">Warning</option>
                            <option value="danger">Danger</option>
                            <option value="info">Info</option>
                            <option value="success">Success</option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <label for="notificationMessage">Message:</label>
                        <input type="text" id="notificationMessage" placeholder="Enter notification message">
                    </div>
                    
                    <div class="control-group">
                        <label for="notificationCount">Count (optional):</label>
                        <input type="number" id="notificationCount" placeholder="Enter count or leave empty for icon">
                    </div>
                    
                    <button class="btn" onclick="addNotification()">Add Notification</button>
                    <button class="btn btn-danger" onclick="removeNotification()">Remove Notification</button>
                    <button class="btn" onclick="updateCount()">Update Count</button>
                </div>
            </div>
            
            <div class="demo-section">
                <h2>Key Features</h2>
                <div class="features-list">
                    <div class="feature-card">
                        <h4>🎨 Modern Design</h4>
                        <p>Beautiful gradient backgrounds with smooth animations and hover effects</p>
                    </div>
                    
                    <div class="feature-card">
                        <h4>♿ Accessibility</h4>
                        <p>Full keyboard navigation support, ARIA labels, and screen reader compatibility</p>
                    </div>
                    
                    <div class="feature-card">
                        <h4>📱 Responsive</h4>
                        <p>Optimized for all screen sizes with mobile-first approach</p>
                    </div>
                    
                    <div class="feature-card">
                        <h4>🔧 Interactive</h4>
                        <p>Click handlers, tooltips, and dynamic content updates</p>
                    </div>
                    
                    <div class="feature-card">
                        <h4>🎯 Smart Positioning</h4>
                        <p>Automatic positioning and overflow handling for different content lengths</p>
                    </div>
                    
                    <div class="feature-card">
                        <h4>⚡ Performance</h4>
                        <p>Optimized animations with reduced motion support and efficient rendering</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/notification-ui.js') }}"></script>
    <script>
        function addNotification() {
            const type = document.getElementById('notificationType').value;
            const message = document.getElementById('notificationMessage').value;
            const count = document.getElementById('notificationCount').value;
            
            if (!message) {
                alert('Please enter a message');
                return;
            }
            
            const countValue = count ? parseInt(count) : null;
            NotificationUI.addNotification('.demo-content', type, message, countValue);
        }
        
        function removeNotification() {
            const badges = document.querySelectorAll('.notification-badge');
            if (badges.length > 0) {
                const lastBadge = badges[badges.length - 1];
                lastBadge.remove();
            }
        }
        
        function updateCount() {
            const count = document.getElementById('notificationCount').value;
            if (count) {
                const badges = document.querySelectorAll('.notification-badge');
                if (badges.length > 0) {
                    const lastBadge = badges[badges.length - 1];
                    lastBadge.textContent = count > 99 ? '99+' : count;
                }
            }
        }
    </script>
</body>
</html> 