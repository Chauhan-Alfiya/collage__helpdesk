<style>
    /* Footer Styles */
.main-footer {
     background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%); /* Deep modern navy */
    color: #f8fafc;
    padding: 80px 0 30px;
    margin-top: 60px;
    font-size: 14px;
}

.footer-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
    gap: 40px;
    margin-bottom: 50px;
}

/* Brand Section */
.footer-brand .logo {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.footer-brand .logo i {
    font-size: 28px;
    color: #3b82f6;
}

.footer-brand .logo h3 {
    font-size: 20px;
    font-weight: 800;
    letter-spacing: -0.5px;
    margin: 0;
}

.footer-brand p {
    color: #94a3b8;
    line-height: 1.6;
    margin-bottom: 24px;
}

/* Social Icons */
.social-links {
    display: flex;
    gap: 12px;
}

.social-links a {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: #2563eb;
    transform: translateY(-3px);
}

/* Link Columns */
.footer-column h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 25px;
    color: #fff;
    position: relative;
}

.footer-column h4::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -8px;
    width: 30px;
    height: 2px;
    background: #2563eb;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.2s;
}

.footer-links a:hover {
    color: #3b82f6;
    padding-left: 5px;
}

/* Newsletter Section */
.newsletter-box p {
    color: #94a3b8;
    margin-bottom: 15px;
}

.newsletter-form {
    display: flex;
    background: rgba(255,255,255,0.05);
    padding: 4px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.1);
}

.newsletter-form input {
    background: transparent;
    border: none;
    padding: 10px 15px;
    color: #fff;
    flex: 1;
    outline: none;
}

.newsletter-form button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.2s;
}

.newsletter-form button:hover {
    background: #1d4ed8;
}

/* Bottom Bar */
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #64748b;
    font-size: 13px;
}

.footer-bottom-links {
    display: flex;
    gap: 20px;
}

.footer-bottom-links a {
    color: #64748b;
    text-decoration: none;
}

.footer-bottom-links a:hover {
    color: #fff;
}

/* Responsive */
@media (max-width: 992px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 576px) {
    .footer-grid {
        grid-template-columns: 1fr;
    }
    .footer-bottom {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
    </style>
    <footer class="main-footer">
    <div class="footer-container">
        <div class="footer-grid">
            
            <div class="footer-brand">
                <div class="logo">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <h3>COLLEGE HELPDESK</h3>
                </div>
                <p>Empowering students and faculty with seamless support services. Your success is our priority through efficient communication and technical excellence.</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-column">
                <h4>Academic</h4>
                <ul class="footer-links">
                    <li><a href="#">Academic Departments</a></li>
                    <li><a href="#">Undergraduate Programs</a></li>
                    <li><a href="#">Graduate Programs</a></li>
                    <li><a href="#">Institutes and Centers</a></li>
                    <li><a href="#">Academic Calendar</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Support</h4>
                <ul class="footer-links">
                    <li><a href="create_ticket.php">Create Ticket</a></li>
                    <li><a href="view_ticket.php">View My Tickets</a></li>
                    <li><a href="#">Technical Support</a></li>
                    <li><a href="#">Academic Support</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Stay Updated</h4>
                <div class="newsletter-box">
                    <p>Subscribe to receive the latest college news and support updates.</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Email address" required>
                        <button type="submit">Join</button>
                    </form>
                </div>
                <div style="margin-top: 25px;">
                    <p style="margin-bottom: 5px; color: #fff; font-weight: 500;">Contact Direct:</p>
                    <span style="color: #94a3b8;">📍 Your Campus, Building A</span><br>
                    <span style="color: #94a3b8;">✉️ support@college.edu</span>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> College Helpdesk. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
