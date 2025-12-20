<?php
session_start();
// Check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
    header("Location: login.php");
    exit;
}

include 'includes/db.php';
include 'includes/header.php';

//  LOGIC 
$limit = 10; // records one page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 1 totals ticket display
$total_tickets = $pdo->query("SELECT count(*) FROM tickets")->fetchColumn();
$total_pages = ceil($total_tickets / $limit);

$open = $pdo->query("SELECT count(*) FROM tickets WHERE status='OPEN'")->fetchColumn();
$resolved = $pdo->query("SELECT count(*) FROM tickets WHERE status='RESOLVED'")->fetchColumn();
$closed = $pdo->query("SELECT count(*) FROM tickets WHERE status='CLOSED'")->fetchColumn();

// 2 closed tickets display
$avg_sql = "SELECT AVG(TIMESTAMPDIFF(HOUR, created_at, closed_at)) FROM tickets WHERE status='CLOSED'";
$avg_raw = $pdo->query($avg_sql)->fetchColumn();
$avg_time = $avg_raw ? round($avg_raw, 1) : 0;

// 3. Fetch Page tickets
// Note: We use bindValue for LIMIT/OFFSET because they require integers
$stmt = $pdo->prepare("SELECT * FROM tickets ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$tickets = $stmt->fetchAll();
?>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
        <h2>Admin Overview</h2>
         <a href="register.php" class="btn btn-secondary" style="font-size: 19px;"><i class="fa-solid fa-lock"></i>Sign up</a>

    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number"><?= $total_tickets ?></div>
            <div class="stat-label">Total Tickets</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $open ?></div>
            <div class="stat-label">Open</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $closed ?></div>
            <div class="stat-label">Closed</div>
        </div>
        <div class="stat-box">
            <div class="stat-number"><?= $avg_time ?>h</div>
            <div class="stat-label">Avg Resolution</div>
        </div>
    </div>

    <div class="card">
        <h3>All Tickets</h3>
        
        <?php if(count($tickets) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th width="15%">Ticket ID</th>
                    <th width="10%">Stream</th>
                    <th width="15%">Category</th>
                    <th width="15%">Status</th>
                    <th width="30%">Title</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tickets as $t): ?>
                <tr>
                    <td><span style="font-weight:600; color:#555;">#<?= $t['ticket_number'] ?></span></td>
                    
                    <td><?= $t['stream'] ? $t['stream'] : '-' ?></td>
                    
                    <td><?= $t['category'] ?></td>
                    
                    <td>
                        <span class="status-<?= $t['status'] ?>">
                            <?= $t['status'] ?>
                        </span>
                    </td>
                    
                    <td><?= htmlspecialchars(strlen($t['title']) > 40 ? substr($t['title'],0,40)."..." : $t['title']) ?></td>
                    
                    <td>
                        <a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;">
                            View Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-chevron-left"></i> Previous
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fa-solid fa-chevron-left"></i> Previous
                </button>
            <?php endif; ?>

            <span style="display: flex; align-items: center; font-weight: 600; color: #666;">
                Page <?= $page ?> of <?= $total_pages ?>
            </span>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn btn-secondary">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled style="opacity: 0.5; cursor: not-allowed;">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </button>
            <?php endif; ?>
        </div>

        <?php else: ?>
            <p style="text-align:center; padding: 20px;">No tickets found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>