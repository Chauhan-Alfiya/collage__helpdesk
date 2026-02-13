<?php
include 'includes/header.php';
include 'includes/nav.php';
?>
<style>
/* Hero Section */
.hero {
    background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
    color: white;
    padding: 100px 20px;
    text-align: center;
    border-radius: 0 0 50px 50px;
    margin-bottom: -50px;
}

.hero h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.hero p {
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto 30px;
}

/* Stats Container */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    max-width: 1100px;
    margin: 0 auto 60px;
    padding: 0 20px;
}

.stat-card {
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card i {
    font-size: 2rem;
    color: #2563eb;
    margin-bottom: 15px;
}

.stat-card h2 {
    font-size: 2rem;
    margin: 5px 0;
    color: #111827;
}

.stat-card p {
    color: #6b7280;
    font-weight: 500;
}

/* Feature Section */
.section-title {
    text-align: center;
    margin: 100px 0 40px;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto 80px;
    padding: 0 20px;
}

.feature-item {
    display: flex;
    gap: 20px;
    background: #f8fafc;
    padding: 25px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.feature-icon {
    background: #fff;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: #2563eb;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    flex-shrink: 0;
}

/* Buttons */
.cta-group {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn-white {
    background: white;
    color: #2563eb !important;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
}

.btn-outline-white {
    border: 2px solid white;
    color: white !important;
    padding: 10px 25px;  
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
}
</style>
<header class="hero">
    <div class="container">
        <h1>How can we help you today?</h1>
        <p>Search for solutions or submit a ticket to our support team. We're here to ensure your campus experience is seamless.</p>
        <div class="cta-group">
            <a href="create_ticket.php" class="btn-white"><i class="fa-solid fa-plus"></i> Create New Ticket</a>
            <a href="view_ticket.php" class="btn-outline-white"><i class="fa-solid fa-magnifying-glass"></i> Check Status</a>
        </div>
    </div>
</header>

<div class="stats-grid">
    <div class="stat-card" style="">
        <i class="fa-solid fa-circle-check"></i>
        <h2></h2>
        <p>Resolved Cases</p>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-bolt"></i>
        <h2>24 hours</h2>
        <p>Avg. Response</p>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-user-shield"></i>
        <h2>24/7</h2>
        <p>System Monitoring</p>
    </div>
</div>

<div class="container">
    <div class="section-title">
        <span style="color: #2563eb; font-weight: 700; text-transform: uppercase; font-size: 13px;">Our Expertise</span>
        <h2 style="font-size: 32px; margin-top: 10px;">Support Categories</h2>
    </div>

    <div class="feature-grid">
        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-laptop-code"></i></div>
            <div>
                <h4 style="margin: 0 0 10px 0;">Technical Support</h4>
                <p style="color: #64748b; font-size: 14px; margin: 0;">WiFi issues, portal login problems, and hardware troubleshooting.</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-book-open"></i></div>
            <div>
                <h4 style="margin: 0 0 10px 0;">Academic Inquiry</h4>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Grade disputes, course registration, and library resource access.</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon"><i class="fa-solid fa-building-columns"></i></div>
            <div>
                <h4 style="margin: 0 0 10px 0;">Administrative</h4>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Fee payments, ID card requests, and facility bookings.</p>
            </div>
        </div>
    </div>
</div>

<div style="background: #f1f5f9; padding: 80px 0; margin-top: 50px;">
    <div class="container" style="max-width: 1000px; margin: 0 auto; text-align: center;">
        <h2 style="margin-bottom: 50px;">How It Works</h2>
        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 30px;">
            <div style="flex: 1; min-width: 200px;">
                <div style="font-size: 24px; font-weight: 800; color: #cbd5e1; margin-bottom: 15px;">01</div>
                <h4 style="margin-bottom: 10px;">Submit Ticket</h4>
                <p style="color: #64748b; font-size: 14px;">Describe your issue and attach relevant files.</p>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <div style="font-size: 24px; font-weight: 800; color: #cbd5e1; margin-bottom: 15px;">02</div>
                <h4 style="margin-bottom: 10px;">Agent Assignment</h4>
                <p style="color: #64748b; font-size: 14px;">Our specialized staff will pick up your request.</p>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <div style="font-size: 24px; font-weight: 800; color: #cbd5e1; margin-bottom: 15px;">03</div>
                <h4 style="margin-bottom: 10px;">Issue Resolved</h4>
                <p style="color: #64748b; font-size: 14px;">Receive a solution via email or portal notification.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>