

    <nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 14px 64px; background: #ffffff; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 1000; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        
        <div class="brand" style="font-weight: 700; font-size: 1.25rem; color: #0f172a; display: flex; align-items: center; gap: 12px; letter-spacing: -0.3px;">
            <div style="background: #2563eb; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);">
                <i class="fa-solid fa-graduation-cap" style="font-size: 1.1rem;"></i>
            </div> 
            <span>College <span style="color: #2563eb;">Helpdesk</span></span>
        </div>

        <div class="nav-right" style="display: flex; align-items: center; gap: 32px;">
            <a href="home.php" style="text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; transition: all 0.2s ease; padding: 6px 12px; border-radius: 6px;" onmouseover="this.style.color='#2563eb'; this.style.background='#f1f5f9';" onmouseout="this.style.color='#475569'; this.style.background='transparent';">
                <i class="fa-solid fa-house" style="font-size: 0.95rem; opacity: 0.8;"></i> 
                Home
            </a>

            <?php if (!empty($user_id)): ?>
                <label for="openProfile" style="cursor: pointer; display: flex; align-items: center; margin: 0; padding-left: 8px; border-left: 1px solid #e2e8f0;">
                    <div class="avatar" style="width: 36px; height: 36px; background: #e0f2fe; color: #0369a1; border: 2px solid #bae6fd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; transition: all 0.2s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.borderColor='#7dd3fc'" onmouseout="this.style.transform='scale(1)'; this.style.borderColor='#bae6fd'">
                        <?= htmlspecialchars($initial) ?>
                    </div>
                </label>
                <?php include 'includes/sidebar.php'; ?>
            <?php endif; ?>
        </div>
    </nav>

    
