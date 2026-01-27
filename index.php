<?php
include 'includes/index_header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .hero-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%); }
    .feature-card { transition: transform 0.3s ease, box-shadow 0.3s ease; border-radius: 16px; }
    .feature-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .status-dot { height: 10px; width: 10px; background-color: #22c55e; border-radius: 50%; display: inline-block; margin-right: 5px; box-shadow: 0 0 8px #22c55e; }
</style>

<main>
    <section class="hero-gradient text-white py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="mb-3 d-flex align-items-center">
                        <span class="status-dot"></span>
                        <small class="text-info fw-bold text-uppercase tracking-wider">All Systems Operational</small>
                    </div>
                    <h1 class="display-3 fw-extrabold mb-3">Unified <span class="text-info">Support</span> <br>Hub for Students.</h1>
                    <p class="lead mb-5 opacity-75">Collage Helpdesk all your support needs in one place.</p>
                    
                    <div class="card bg-white p-2 rounded-pill shadow-lg d-none d-md-block" style="max-width: 500px;">
                        <form action="ticket_view.php" method="GET" class="d-flex">
                            <input type="text" name="ticket" class="form-control border-0 rounded-pill px-4" placeholder="Enter Ticket Number (e.g. TIC-12345)" required>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Track Now</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <img src="https://img.freepik.com/free-vector/customer-support-flat-design-illustration_23-2148889374.jpg" class="img-fluid rounded-4 shadow-lg" alt="Support">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="margin-top: -40px;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow-sm p-4 h-100">
                        <div class="icon-box bg-primary-subtle text-primary rounded-3 d-inline-flex p-3 mb-3" style="width: fit-content;">
                            <i class="fas fa-bolt fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">Fast Resolution</h4>
                        <p class="text-muted small">We ensure quick resolution of your tickets within 24 hours.</p>
                        <a href="create_ticket.php" class="text-primary text-decoration-none fw-bold small">Open Ticket <i class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow-sm p-4 h-100">
                        <div class="icon-box bg-success-subtle text-success rounded-3 d-inline-flex p-3 mb-3" style="width: fit-content;">
                            <i class="fas fa-lock fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">Secure Privacy</h4>
                        <p class="text-muted small">Your personal information and data are end-to-end encrypted and secure.</p>
                        <a href="about.php" class="text-success text-decoration-none fw-bold small">Read Policy <i class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow-sm p-4 h-100">
                        <div class="icon-box bg-info-subtle text-info rounded-3 d-inline-flex p-3 mb-3" style="width: fit-content;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">Staff & Faculty</h4>
                        <p class="text-muted small">Special access for staff and coordinators to manage requests effectively.</p>
                        <a href="login.php" class="text-info text-decoration-none fw-bold small">Staff Login <i class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Frequently Asked Questions</h2>
                <p class="text-muted">Need help? Find answers to common questions here.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion accordion-flush shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#f1">
                                how are tickets routed to the right department?
                                </button>
                            </h2>
                            <div id="f1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">Tickets are automatically routed to the appropriate department based on their category and stream.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#f2">
                                can i view my ticket history?
                                </button>
                            </h2>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">Yes! You can view your ticket history after logging in to the "My Tickets" section.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="p-5 rounded-5 hero-gradient text-center text-white shadow-lg">
                <h2 class="fw-bold mb-3">Ready to get started?</h2>
                <p class="mb-4 opacity-75">Start creating tickets and managing your support requests efficiently.</p>
                <a href="create_ticket.php" class="btn btn-info btn-lg px-5 rounded-pill fw-bold text-white">Get Started Now</a>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'includes/footer.php'; ?>