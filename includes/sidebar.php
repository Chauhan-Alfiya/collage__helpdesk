<input type="checkbox" id="openProfile" hidden>

<!-- OVERLAY -->
<label for="openProfile" class="sidebar-overlay"></label>

<!-- SIDEBAR -->
<aside class="profile-sidebar">

    <!-- TOP HEADER -->
    <div class="sidebar-top">

        <div>
            <small class="sidebar-small-text">
                MY ACCOUNT
            </small>
        </div>

        <label for="openProfile" class="close-sidebar">
            <i class="fa-solid fa-xmark"></i>
        </label>

    </div>

    <!-- USER SECTION -->
    <div class="sidebar-user-section">

        <div class="avatar-large">
            <?= strtoupper($initial) ?>
        </div>

        <h4 class="user-name">
            <?= htmlspecialchars($username) ?>
        </h4>

        <span class="user-role">
            <?= strtoupper($role) ?>
        </span>

    </div>

    <!-- MENU -->
    <nav class="sidebar-menu">

        <a href="profile.php">
            <div>
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </div>

            <i class="fa-solid fa-angle-right arrow-icon"></i>
        </a>

        <a href="home.php">
            <div>
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </div>

            <i class="fa-solid fa-angle-right arrow-icon"></i>
        </a>

        <a href="myticket.php">
            <div>
                <i class="fa-solid fa-ticket"></i>
                <span>My Tickets</span>
            </div>

            <i class="fa-solid fa-angle-right arrow-icon"></i>
        </a>

        <a href="messages.php">
            <div>
                <i class="fa-solid fa-envelope"></i>
                <span>Messages</span>
            </div>

            <i class="fa-solid fa-angle-right arrow-icon"></i>
        </a>

        <div class="menu-divider"></div>

        <a href="settings.php">
            <div>
                <i class="fa-solid fa-gear"></i>
                <span>Settings</span>
            </div>

            <i class="fa-solid fa-angle-right arrow-icon"></i>
        </a>

        <a href="contact.php">
            <div>
                <i class="fa-solid fa-phone"></i>
                <span>Contact Us</span>
            </div>

            <i class="fa-solid fa-angle-right arrow-icon"></i>
        </a>

        <!-- LOGOUT -->
        <a href="logout.php" class="logout-link">

            <div>
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Sign Out</span>
            </div>

        </a>

    </nav>

</aside>

<style>

/* OVERLAY */
.sidebar-overlay{
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.45);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: 0.3s;
    backdrop-filter: blur(3px);
}

/* SIDEBAR */
.profile-sidebar{
    position: fixed;
    top: 0;
    right: 0;
    width: 380px;
    max-width: 100%;
    height: 100vh;
    background: #ffffff;
    z-index: 1000;
    transform: translateX(100%);
    transition: 0.4s ease;
    overflow-y: auto;
    box-shadow: -10px 0 40px rgba(0,0,0,0.08);
}

/* OPEN SIDEBAR */
#openProfile:checked ~ .sidebar-overlay{
    opacity: 1;
    visibility: visible;
}

#openProfile:checked ~ .profile-sidebar{
    transform: translateX(0);
}

/* HEADER */
.sidebar-top{
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 26px;
    border-bottom: 1px solid #f1f5f9;
}

.sidebar-small-text{
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
}

/* CLOSE BUTTON */
.close-sidebar{
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
    font-size: 18px;
    color: #0f172a;
}

.close-sidebar:hover{
    background: #e2e8f0;
}

/* USER SECTION */
.sidebar-user-section{
    padding: 38px 25px 32px;
    text-align: center;
    background: linear-gradient(to bottom,#f8fbff,#ffffff);
    border-bottom: 1px solid #f1f5f9;
}

/* AVATAR */
.avatar-large{
    width: 95px;
    height: 95px;
    border-radius: 50%;
    margin: 0 auto 18px;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    font-weight: 700;
    box-shadow: 0 15px 30px rgba(37,99,235,0.25);
}

/* NAME */
.user-name{
    margin: 0;
    font-size: 21px;
    font-weight: 700;
    color: #0f172a;
}

/* ROLE */
.user-role{
    display: inline-block;
    margin-top: 10px;
    background: #dbeafe;
    color: #2563eb;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* MENU */
.sidebar-menu{
    padding: 24px;
}

/* LINKS */
.sidebar-menu a{
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    color: #0f172a;
    padding: 16px 18px;
    border-radius: 18px;
    margin-bottom: 14px;
    background: #ffffff;
    border: 1px solid #f1f5f9;
    transition: 0.3s ease;
    font-weight: 600;
}

/* ICON + TEXT */
.sidebar-menu a div{
    display: flex;
    align-items: center;
    gap: 15px;
}

/* ICON */
.sidebar-menu a i{
    font-size: 17px;
}

/* ARROW */
.arrow-icon{
    font-size: 13px !important;
    color: #94a3b8;
}

/* HOVER */
.sidebar-menu a:hover{
    background: #f8fbff;
    border-color: #dbeafe;
    transform: translateX(5px);
    color: #2563eb;
    box-shadow: 0 8px 20px rgba(37,99,235,0.08);
}

/* DIVIDER */
.menu-divider{
    height: 1px;
    background: #e2e8f0;
    margin: 18px 0;
}

/* LOGOUT */
.logout-link{
    background: #fef2f2 !important;
    border-color: #fecaca !important;
    color: #dc2626 !important;
}

.logout-link:hover{
    background: #fee2e2 !important;
}

/* MOBILE */
@media(max-width:576px){

    .profile-sidebar{
        width: 100%;
    }

    .sidebar-menu{
        padding: 18px;
    }

}

</style>