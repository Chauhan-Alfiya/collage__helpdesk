<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$logged_username = $_SESSION['username'];
$tickets = [];
$found_email = "";


$stmt = $pdo->prepare("SELECT email FROM student WHERE username = ?");
$stmt->execute([$logged_username]);
$user = $stmt->fetch();


if (!$user) {
    $stmt = $pdo->prepare("SELECT email FROM faculty WHERE username = ?");
    $stmt->execute([$logged_username]);
    $user = $stmt->fetch();
}


if ($user) {
    $found_email = $user['email'];
    $sql = "SELECT * FROM tickets WHERE requester_email = ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$found_email]);
    $tickets = $stmt->fetchAll();
}

include 'includes/header.php';
?>

<style>
    body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
    .ticket-container { max-width: 1100px; margin: 50px auto; padding: 0 20px; }
    .card-header { background: white; padding: 25px; border-radius: 15px 15px 0 0; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; }
    .main-card { background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; }
    .table { width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
    .table td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 14px; }
    .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
    .status-open { background: #fef3c7; color: #92400e; }
    .status-in-progress { background: #e0f2fe; color: #075985; }
    .status-resolved { background: #dcfce7; color: #166534; }
    .btn-view { color: #2563eb; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 5px; }
    .btn-view:hover { color: #1e40af; }
    .empty-state { padding: 60px; text-align: center; color: #94a3b8; }
    .btn-new-ticket { background: #1e40af; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; transition: 0.3s; }
</style>

<div class="ticket-container">
    <div class="main-card">
        <div class="card-header">
            <div>
                <h2 style="margin:0; color: #1e293b; font-size: 24px;">My Support Tickets</h2>
                <p style="margin:5px 0 0; color: #64748b; font-size: 14px;">Manage and track your reported issues</p>
            </div>
            <a href="create_ticket.php" class="btn-new-ticket">
                <i class="fas fa-plus"></i> New Ticket
            </a>
        </div>

        <?php if (!$found_email): ?>
            <div style="padding: 20px; background: #fee2e2; color: #b91c1c; margin: 20px; border-radius: 8px;">
                <i class="fas fa-exclamation-circle"></i> <b>System Note:</b> not found email. Please update your profile with a valid email to create and view tickets.

            </div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Issue Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tickets) > 0): ?>
                    <?php foreach ($tickets as $t): ?>
                        <tr>
                            <td style="font-weight: 700; color: #1e40af;">#<?= $t['ticket_number'] ?></td>
                            <td><?= htmlspecialchars($t['title']) ?></td>
                            <td><span style="color: #64748b; font-size: 13px;"><?= $t['category'] ?></span></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($t['status']) ?>">
                                    <i class="fas fa-circle" style="font-size: 8px; vertical-align: middle; margin-right: 5px;"></i>
                                    <?= $t['status'] ?>
                                </span>
                            </td>
                            <td style="color: #94a3b8;"><?= date('M d, Y', strtotime($t['created_at'])) ?></td>
                            <td>
                                <a href="ticket_view.php?ticket=<?= $t['ticket_number'] ?>" class="btn-view">
                                    View <i class="fas fa-arrow-right" style="font-size: 12px;"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x" style="margin-bottom: 15px; color: #cbd5e1;"></i>
                                <p style="font-size: 18px; font-weight: 500;">No tickets found</p>
                                <p style="font-size: 14px;">not Created yet</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>