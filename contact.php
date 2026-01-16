<?php
    include 'includes/index_header.php';
?>

<section class="about-hero" style="background-image: url('images/2.jpg');">
    <div class="hero-inner">
        <div class="container">
            <nav class="breadcrumb"><a href="index.php">Home</a> <span class="sep">/</span> <span>Contact</span></nav>
            <h1 style="text-align: left; color: white;1">Contact Us</h1>
            <p class="hero-sub">We're here to help! Reach out with any questions or issues you have regarding the College Helpdesk system.</p>
        </div>
    </div>
</section>

<main class="about-main container">
    <div class="about-grid">
        <div class="about-text">
            <h2>Get in Touch</h2>
            <p>If you need assistance with the College Helpdesk platform, have questions about your tickets or roles, or want to provide feedback, our support team is ready to assist you. You can contact us through the following methods:</p>       
            <form method="POST">
            <div class="form-group">
                <input type="text" name="Full name" class="form-control"style=" width: 50%; display:d " placeholder="Full Name" required>
            </div>
            <div class="form-group">
               
                <input type="email" name="email" class="form-control"style="width: 50%; " placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="text" name="subject" class="form-control" placeholder="Subject" required>
            </div>
            <div class="form-group">
                <textarea name="message" class="form-control" placeholder="Your Message" rows="5" required></textarea>
             </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
                Send Massage <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i>
            </button>
            <div style="text-align: center; margin-top: 20px;">
            
            </div>
            
        </form>
                
            </div>
        </div>


