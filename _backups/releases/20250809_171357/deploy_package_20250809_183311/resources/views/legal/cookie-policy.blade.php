@extends('layouts.app')

@section('content')
<div class="legal-page">
    <div class="legal-container">
        <div class="legal-header">
            <h1>Cookie Policy</h1>
            <p class="effective-date">Effective Date: August 8, 2025</p>
        </div>

        <div class="legal-content">
            <div class="section">
                <h2>1. Introduction</h2>
                <p>This Cookie Policy explains how Meishicadi, operated by Softchap Publishing W.L.L, uses cookies and similar technologies when you visit our website and use our digital business card platform.</p>
                <p>By using our Service, you consent to the use of cookies in accordance with this policy.</p>
            </div>

            <div class="section">
                <h2>2. What Are Cookies?</h2>
                <p>Cookies are small text files that are stored on your device (computer, tablet, or mobile) when you visit a website. They help websites remember information about your visit, such as your preferred language and other settings, which can make your next visit easier and more useful.</p>
            </div>

            <div class="section">
                <h2>3. How We Use Cookies</h2>
                <p>We use cookies for several purposes:</p>
                
                <h3>3.1 Essential Cookies</h3>
                <p>These cookies are necessary for the website to function properly. They enable basic functions like page navigation, access to secure areas, and form submissions. The website cannot function properly without these cookies.</p>
                <ul>
                    <li>Authentication and security</li>
                    <li>Session management</li>
                    <li>Load balancing</li>
                </ul>

                <h3>3.2 Functional Cookies</h3>
                <p>These cookies enable enhanced functionality and personalization, such as remembering your preferences and settings.</p>
                <ul>
                    <li>Language preferences</li>
                    <li>Theme settings</li>
                    <li>User interface customization</li>
                </ul>

                <h3>3.3 Analytics Cookies</h3>
                <p>These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously.</p>
                <ul>
                    <li>Page views and navigation patterns</li>
                    <li>Feature usage statistics</li>
                    <li>Performance monitoring</li>
                </ul>

                <h3>3.4 Marketing Cookies</h3>
                <p>These cookies are used to track visitors across websites to display relevant and engaging advertisements.</p>
                <ul>
                    <li>Ad targeting and optimization</li>
                    <li>Campaign effectiveness measurement</li>
                    <li>Social media integration</li>
                </ul>
            </div>

            <div class="section">
                <h2>4. Types of Cookies We Use</h2>
                
                <h3>4.1 Session Cookies</h3>
                <p>These cookies are temporary and are deleted when you close your browser. They help maintain your session while you navigate through our website.</p>

                <h3>4.2 Persistent Cookies</h3>
                <p>These cookies remain on your device for a set period or until you delete them. They help us remember your preferences and settings for future visits.</p>

                <h3>4.3 Third-Party Cookies</h3>
                <p>Some cookies are placed by third-party services that appear on our pages, such as:</p>
                <ul>
                    <li>Google Analytics for website analytics</li>
                    <li>Payment processors for secure transactions</li>
                    <li>Social media platforms for sharing features</li>
                    <li>Advertising networks for targeted ads</li>
                </ul>
            </div>

            <div class="section">
                <h2>5. Managing Your Cookie Preferences</h2>
                <p>You have several options for managing cookies:</p>
                
                <h3>5.1 Browser Settings</h3>
                <p>Most web browsers allow you to control cookies through their settings. You can:</p>
                <ul>
                    <li>Block all cookies</li>
                    <li>Allow cookies from specific websites</li>
                    <li>Delete existing cookies</li>
                    <li>Set preferences for different types of cookies</li>
                </ul>

                <h3>5.2 Cookie Consent</h3>
                <p>When you first visit our website, you'll see a cookie consent banner that allows you to:</p>
                <ul>
                    <li>Accept all cookies</li>
                    <li>Customize your cookie preferences</li>
                    <li>Reject non-essential cookies</li>
                </ul>

                <h3>5.3 Opt-Out Tools</h3>
                <p>You can opt out of certain types of cookies:</p>
                <ul>
                    <li>Google Analytics opt-out browser add-on</li>
                    <li>Digital Advertising Alliance opt-out tools</li>
                    <li>Network Advertising Initiative opt-out page</li>
                </ul>
            </div>

            <div class="section">
                <h2>6. Impact of Disabling Cookies</h2>
                <p>While you can disable cookies, please note that:</p>
                <ul>
                    <li>Some features may not function properly</li>
                    <li>Your user experience may be affected</li>
                    <li>You may need to re-enter information repeatedly</li>
                    <li>Some services may be unavailable</li>
                </ul>
            </div>

            <div class="section">
                <h2>7. Updates to This Policy</h2>
                <p>We may update this Cookie Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will notify you of any material changes by posting the updated policy on our website.</p>
            </div>

            <div class="section">
                <h2>8. Contact Us</h2>
                <p>If you have any questions about our use of cookies or this Cookie Policy, please contact us:</p>
                
                <div class="contact-info">
                    <p><strong>Softchap Publishing W.L.L</strong></p>
                    <p>Email: <a href="mailto:info@chapysocial.com">info@chapysocial.com</a></p>
                    <p>Phone: 7790 3299 / 34553299</p>
                    <p>Address: Manama, Kingdom of Bahrain</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.legal-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 0;
}

.legal-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.legal-header {
    background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
    color: white;
    padding: 40px;
    text-align: center;
}

.legal-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    letter-spacing: -0.5px;
}

.effective-date {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
}

.legal-content {
    padding: 40px;
}

.section {
    margin-bottom: 40px;
}

.section h2 {
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #e5e7eb;
}

.section h3 {
    color: #374151;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 25px 0 15px 0;
}

.section p {
    color: #4b5563;
    line-height: 1.7;
    margin-bottom: 15px;
}

.section ul {
    color: #4b5563;
    line-height: 1.7;
    margin: 15px 0;
    padding-left: 20px;
}

.section li {
    margin-bottom: 8px;
}

.contact-info {
    background: #f9fafb;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #7c3aed;
}

.contact-info p {
    margin: 8px 0;
}

.contact-info a {
    color: #7c3aed;
    text-decoration: none;
    font-weight: 500;
}

.contact-info a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .legal-container {
        margin: 0 20px;
        border-radius: 12px;
    }
    
    .legal-header {
        padding: 30px 20px;
    }
    
    .legal-header h1 {
        font-size: 2rem;
    }
    
    .legal-content {
        padding: 30px 20px;
    }
    
    .section h2 {
        font-size: 1.25rem;
    }
    
    .section h3 {
        font-size: 1.1rem;
    }
}
</style>
@endsection
