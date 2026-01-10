@extends('layouts.app')

@section('content')
<div class="legal-page">
    <div class="legal-container">
        <div class="legal-header">
            <h1>Terms of Service</h1>
            <p class="effective-date">Effective Date: August 8, 2025</p>
        </div>

        <div class="legal-content">
            <div class="section">
                <h2>Introduction</h2>
                <p>Welcome to Meishicadi, a digital business card platform provided by Softchap Publishing W.L.L ("we," "our," "us"). These Terms of Service ("Terms") govern your use of the Meishicadi platform and related services (the "Service").</p>
                <p>By accessing or using Meishicadi, you agree to be bound by these Terms. If you do not agree, please do not use the Service.</p>
            </div>

            <div class="section">
                <h2>1. Use of the Service</h2>
                <p>You agree to use Meishicadi only for lawful purposes and in accordance with these Terms. You are responsible for all activity that occurs under your account.</p>
                
                <p>You must:</p>
                <ul>
                    <li>Provide accurate information when creating your digital card</li>
                    <li>Keep your login credentials secure</li>
                    <li>Not use the service to impersonate or misrepresent others</li>
                </ul>
            </div>

            <div class="section">
                <h2>2. Eligibility</h2>
                <p>You must be at least 13 years old or the age of digital consent in your country to use Meishicadi. If you are using the Service on behalf of a business or organization, you represent that you are authorized to accept these Terms on its behalf.</p>
            </div>

            <div class="section">
                <h2>3. User Content</h2>
                <p>You are responsible for all content you upload, display, or share using Meishicadi. This includes names, logos, contact information, media, and links.</p>
                
                <p>You agree not to post or share:</p>
                <ul>
                    <li>False, misleading, or fraudulent information</li>
                    <li>Content that is defamatory, obscene, or offensive</li>
                    <li>Content that violates intellectual property rights of others</li>
                    <li>Viruses or malicious code</li>
                </ul>
                
                <p>We reserve the right to remove content or suspend accounts that violate these terms.</p>
            </div>

            <div class="section">
                <h2>4. Intellectual Property</h2>
                <p>All content and materials on the Meishicadi platform, including software, logos, and trademarks (excluding your submitted content), are the property of Softchap Publishing W.L.L or its licensors.</p>
                
                <p>You may not:</p>
                <ul>
                    <li>Copy, modify, or reverse engineer any part of the platform</li>
                    <li>Use our branding without permission</li>
                    <li>Resell or license access to the Service without written approval</li>
                </ul>
            </div>

            <div class="section">
                <h2>5. Plans, Payments & Refunds</h2>
                <p>Some features may require a paid subscription. By subscribing:</p>
                <ul>
                    <li>You agree to pay the applicable fees</li>
                    <li>All payments are final and non-refundable unless stated otherwise</li>
                    <li>We may update our pricing and plans at any time with notice</li>
                </ul>
            </div>

            <div class="section">
                <h2>6. Account Termination</h2>
                <p>You may delete your account at any time. We may suspend or terminate your access if you:</p>
                <ul>
                    <li>Violate these Terms</li>
                    <li>Abuse the platform or other users</li>
                    <li>Fail to make payments for paid services</li>
                </ul>
                <p>Upon termination, your data may be deleted.</p>
            </div>

            <div class="section">
                <h2>7. Availability and Support</h2>
                <p>We strive to keep Meishicadi available and functional, but we do not guarantee uninterrupted service. We may perform maintenance or make updates without notice.</p>
                <p>Support is available via our contact email or through in-app support features.</p>
            </div>

            <div class="section">
                <h2>8. Limitation of Liability</h2>
                <p>Meishicadi is provided "as is" and "as available." We do not guarantee accuracy, availability, or fitness for a specific purpose.</p>
                <p>To the maximum extent allowed by law, Softchap Publishing W.L.L shall not be liable for any damages, including indirect or incidental losses, arising from your use of the platform.</p>
            </div>

            <div class="section">
                <h2>9. Changes to the Terms</h2>
                <p>We may revise these Terms at any time. Updated terms will be posted with the new effective date. Continued use of the Service after updates constitutes your acceptance.</p>
            </div>

            <div class="section">
                <h2>10. Governing Law</h2>
                <p>These Terms shall be governed by the laws of the Kingdom of Bahrain, without regard to conflict of laws.</p>
            </div>

            <div class="section">
                <h2>11. Contact</h2>
                <p>For questions about these Terms or the platform, contact:</p>
                
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
}
</style>
@endsection
