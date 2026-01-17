<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

$ticket_id = $_GET['id'] ?? 0;
$is_public = isset($_GET['public']);
$user_role = $_SESSION['role'] ?? '';
$user_id   = $_SESSION['user_id'] ?? 0;

/* ================= FETCH TICKET ================= */

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die("Ticket not found");
}

/* ================= HANDLE POST (BEFORE OUTPUT) ================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$is_public) {

    $comment = $_POST['comment'] ?? '';
    $new_status = $ticket['status'];
    $assigned_user_id = $ticket['assigned_user_id'];

    // Coordinator logic
    if (strpos($user_role, '_CORD') !== false) {

        if (isset($_POST['assign_staff']) && $ticket['status'] === 'OPEN') {
            $new_status = 'IN-PROGRESS';
            $assigned_user_id = getStaffIdByCoordinator($pdo, $user_id);
        }

        if (isset($_POST['close_ticket']) && $ticket['status'] === 'RESOLVED') {
            $new_status = 'CLOSED';
            $pdo->prepare("UPDATE tickets SET closed_at = NOW() WHERE ticket_id = ?")
                ->execute([$ticket_id]);
        }
    }

    // Staff logic
    if (strpos($user_role, '_STAFF') !== false && isset($_POST['resolve_ticket']) && $ticket['status'] === 'IN-PROGRESS') {
        $new_status = 'RESOLVED';
        $assigned_user_id = getCoordinatorId($pdo, $ticket['category'], $ticket['stream']);
        $pdo->prepare("UPDATE tickets SET resolved_at = NOW() WHERE ticket_id = ?")
            ->execute([$ticket_id]);
    }

    // Update ticket
    $pdo->prepare(
        "UPDATE tickets SET status = ?, assigned_user_id = ? WHERE ticket_id = ?"
    )->execute([$new_status, $assigned_user_id, $ticket_id]);

    // Add comment
    if (!empty($comment)) {
        $pdo->prepare(
            "INSERT INTO ticket_comments (ticket_id, user_id, comment_text)
             VALUES (?, ?, ?)"
        )->execute([$ticket_id, $user_id, $comment]);
    }

    // Redirect (NO OUTPUT HAS HAPPENED YET ✅)
    if ($user_role === 'ADMIN') {
        header("Location: admin_dashboard.php");
    } elseif (strpos($user_role, '_CORD') !== false) {
        header("Location: cord_dashboard.php");
    } elseif (strpos($user_role, '_STAFF') !== false) {
        header("Location: staff_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

/* ================= NOW SAFE TO OUTPUT ================= */

include 'includes/header.php';

/* ================= FETCH COMMENTS ================= */

$stmt = $pdo->prepare("
    SELECT tc.*, u.username 
    FROM ticket_comments tc 
    LEFT JOIN users u ON tc.user_id = u.user_id 
    WHERE ticket_id = ? 
    ORDER BY created_at ASC
");
$stmt->execute([$ticket_id]);
$comments = $stmt->fetchAll();

/* ================= FETCH ATTACHMENT ================= */

$stmtAtt = $pdo->prepare("SELECT attachment_id, filename FROM ticket_attachments WHERE ticket_id = ?");
$stmtAtt->execute([$ticket_id]);
$attachment = $stmtAtt->fetch();
?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0; border: none;">Ticket #<?= $ticket['ticket_number'] ?></h2>
        <span class="status-<?= $ticket['status'] ?>" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
            <?= $ticket['status'] ?>
        </span>
    </div>
    
    <div class="card">
        <p><strong>Subject:</strong> <?= htmlspecialchars($ticket['title']) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
        <p><strong>Category:</strong> <?= $ticket['category'] ?> | <strong>Stream:</strong> <?= $ticket['stream'] ?></p>
        <?php if($attachment): ?>
            <p><strong>Attachment:</strong> <a href="view_attachment.php?id=<?= $attachment['attachment_id'] ?>" target="_blank"><?= $attachment['filename'] ?></a></p>
        <?php endif; ?>
    </div>

    <h3>History & Comments</h3>
    <?php foreach($comments as $c): ?>
        <div class="card" style="padding: 10px; background: #f9f9f9;">
            <p><strong><?= $c['username'] ?? 'System' ?></strong> <small>(<?= $c['created_at'] ?>)</small></p>
            <p><?= nl2br(htmlspecialchars($c['comment_text'])) ?></p>
        </div>
    <?php endforeach; ?>

    <?php if(!$is_public && $ticket['status'] != 'CLOSED'): ?>
    <div class="card" style="border-color: #0056b3;">
        <h3>Take Action</h3>
        <form method="POST">
            <textarea name="comment" class="form-control" placeholder="Add a comment..." required></textarea>
            <br>
            
            <?php if (strpos($user_role, '_CORD') !== false && $ticket['status'] == 'OPEN'): ?>
                <button type="submit" name="assign_staff" class="btn btn-primary">Assign to Staff (In Progress)</button>
            <?php endif; ?>

            <?php if (strpos($user_role, '_STAFF') !== false && $ticket['status'] == 'IN-PROGRESS'): ?>
                <button type="submit" name="resolve_ticket" class="btn btn-success">Mark Resolved</button>
            <?php endif; ?>

            <?php if (strpos($user_role, '_CORD') !== false && $ticket['status'] == 'RESOLVED'): ?>
                <button type="submit" name="close_ticket" class="btn btn-danger">Close Ticket</button>
            <?php endif; ?>

            <?php if ($user_role == 'ADMIN'): ?>
                <div class="card" style="background-color: #f8f9fa; color: #6c757d;">
                    <em>You are viewing this ticket as Administrator (Read-Only).</em>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>