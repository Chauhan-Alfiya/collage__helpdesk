<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

$ticket_id = $_GET['id'] ?? 0;
$is_public = isset($_GET['public']);
$user_role = $_SESSION['role'] ?? '';
$user_id   = $_SESSION['user_id'] ?? 0;
$user_email = $_SESSION['email'] ?? '';

// Fetch ticket
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die("Ticket not found");
}

// --- ACCESS CONTROL ---
if (in_array($user_role, ['STUDENT','FACULTY'])) {
    if ($ticket['requester_email'] !== $user_email) die("Unauthorized Access");
}

if (strpos($user_role, 'STAFF') !== false) {
    if ($ticket['assigned_user_id'] != $user_id) die("Unauthorized Access");
}

if (strpos($user_role, 'CORD') !== false) {
    if ($_SESSION['department'] != $ticket['stream']) die("Unauthorized Access");
}

// --- HANDLE POST ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$is_public) {
    $comment = $_POST['comment'] ?? '';
    $new_status = $ticket['status'];
    $assigned_user_id = $ticket['assigned_user_id'];

    // Coordinator actions
    if (strpos($user_role, 'CORD') !== false) {
        if (isset($_POST['assign_staff']) && $ticket['status'] === 'OPEN') {
            $assigned_user_id = getStaffIdByCoordinator($pdo, $user_id, $ticket['stream']);
            $new_status = 'IN-PROGRESS';
        }
        if (isset($_POST['close_ticket']) && $ticket['status'] === 'RESOLVED') {
            $new_status = 'CLOSED';
            $pdo->prepare("UPDATE tickets SET closed_at = NOW() WHERE ticket_id = ?")->execute([$ticket_id]);
        }
    }

    // Staff actions
    if (strpos($user_role, 'STAFF') !== false && isset($_POST['resolve_ticket']) && $ticket['status'] === 'IN-PROGRESS') {
        $new_status = 'RESOLVED';
        $assigned_user_id = getCoordinatorId($pdo, $ticket['category'], $ticket['stream']);
        $pdo->prepare("UPDATE tickets SET resolved_at = NOW() WHERE ticket_id = ?")->execute([$ticket_id]);
    }

    // Update ticket
    $pdo->prepare("UPDATE tickets SET status=?, assigned_user_id=? WHERE ticket_id=?")
        ->execute([$new_status, $assigned_user_id, $ticket_id]);

    // Insert comment
    if (!empty($comment)) {
        $pdo->prepare("INSERT INTO ticket_comments (ticket_id, user_id, comment_text) VALUES (?,?,?)")
            ->execute([$ticket_id, $user_id, $comment]);
    }

    // Redirect back to proper dashboard
    if ($user_role === 'ADMIN') {
        header("Location: my_ticket.php");
    } elseif (strpos($user_role,'CORD') !== false) {
        header("Location: my_ticket.php");
    } elseif (strpos($user_role,'STAFF') !== false) {
        header("Location: my_ticket.php");
    } else {
        header("Location: my_ticket.php");
    }
    exit;
}

// Fetch comments
$stmt = $pdo->prepare("
    SELECT tc.*, u.username 
    FROM ticket_comments tc
    LEFT JOIN users u ON tc.user_id = u.user_id
    WHERE tc.ticket_id = ? 
    ORDER BY tc.created_at ASC
");
$stmt->execute([$ticket_id]);
$comments = $stmt->fetchAll();

// Fetch attachment
$stmtAtt = $pdo->prepare("SELECT attachment_id, filename FROM ticket_attachments WHERE ticket_id = ?");
$stmtAtt->execute([$ticket_id]);
$attachment = $stmtAtt->fetch();

include 'includes/header.php';
?>

<div class="container">
    <h2>Ticket #<?= $ticket['ticket_number'] ?></h2>
    <span class="status-<?= strtolower($ticket['status']) ?>"><?= $ticket['status'] ?></span>

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
        <div class="card" style="background:#f9f9f9; padding:10px;">
            <p><strong><?= $c['username'] ?? 'System' ?></strong> (<?= $c['created_at'] ?>)</p>
            <p><?= nl2br(htmlspecialchars($c['comment_text'])) ?></p>
        </div>
    <?php endforeach; ?>

    <?php if (!$is_public && $ticket['status'] != 'CLOSED'): ?>
    <div class="card" style="border-color:#0056b3;">
        <h3>Take Action</h3>
        <form method="POST">
            <textarea name="comment" class="form-control" placeholder="Add a comment..." required></textarea><br>

            <?php if (strpos($user_role,'CORD') !== false && $ticket['status']=='OPEN'): ?>
                <button type="submit" name="assign_staff" class="btn btn-primary">Assign to Staff (In Progress)</button>
            <?php endif; ?>

            <?php if (strpos($user_role,'STAFF') !== false && $ticket['status']=='IN-PROGRESS'): ?>
                <button type="submit" name="resolve_ticket" class="btn btn-success">Mark Resolved</button>
            <?php endif; ?>

            <?php if (strpos($user_role,'CORD') !== false && $ticket['status']=='RESOLVED'): ?>
                <button type="submit" name="close_ticket" class="btn btn-danger">Close Ticket</button>
            <?php endif; ?>

            <?php if ($user_role === 'ADMIN'): ?>
                <div class="card" style="background:#f8f9fa; color:#6c757d;">
                    <em>You are viewing this ticket as Admin (Read-Only)</em>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
