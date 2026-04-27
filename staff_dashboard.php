<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// ==========================
// LOGIN CHECK (SAFE)
// ==========================
if (!isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    header("Location: common_login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';

// ==========================
// STAFF CHECK (FIXED)
// ==========================
if ($role !== 'STAFF') {
    header("Location: common_login.php");
    exit();
}

// ==========================
// GET STAFF DEPARTMENT FROM DB (SAFE)
// ==========================
$stmt = $pdo->prepare("SELECT department FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$stream = $stmt->fetchColumn();

if (!$stream) {
    die("Department not found for staff");
}

// ==========================
// FETCH TICKETS
// ==========================
$stmt = $pdo->prepare("
    SELECT * FROM tickets  
    WHERE assigned_user_id = ? 
    AND stream = ?
    AND status != 'CLOSED'
    ORDER BY created_at DESC
");

$stmt->execute([$user_id, $stream]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// HEADER
// ==========================
include 'includes/header.php';
?>

<div class="ticket-container">

    <div class="main-card">

        <div class="card-header">

            <div>
                <h2>Staff Task Queue (<?= htmlspecialchars($stream) ?>)</h2>
                <p>Manage and resolve your assigned tickets</p>
            </div>

            <a href="all_tickets.php" class="btn-new-ticket">
                <i class="fas fa-list"></i> All Tickets
            </a>

        </div>

        <table class="table">

            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($tickets)): ?>

                <?php foreach ($tickets as $t): ?>
                    <tr>

                        <td>#<?= htmlspecialchars($t['ticket_number']) ?></td>

                        <td><?= htmlspecialchars($t['title']) ?></td>

                        <td><?= htmlspecialchars($t['category']) ?></td>

                        <td>
                            <span class="status-badge status-<?= strtolower($t['status']) ?>">
                                <?= htmlspecialchars($t['status']) ?>
                            </span>
                        </td>

                        <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>

                        <td>
                            <a href="ticket_details.php?id=<?= $t['ticket_id'] ?>" class="btn-view">
                                Process
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" style="text-align:center; padding:30px;">
                        No assigned tasks found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include 'includes/footer.php'; ?>