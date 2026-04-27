<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

$ticket_id = $_GET['id'] ?? 0;
$is_public = isset($_GET['public']);

$user_role  = $_SESSION['role'] ?? '';
$user_id    = $_SESSION['user_id'] ?? 0;
$user_email = $_SESSION['email'] ?? '';

// =========================
// FETCH TICKET
// =========================
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Ticket not found");
}

// =========================
// ACCESS CONTROL
// =========================

// STUDENT / FACULTY
if (in_array($user_role, ['STUDENT','FACULTY'])) {
    if ($ticket['requester_email'] !== $user_email) {
        die("Unauthorized Access");
    }
}

// STAFF (only assigned ticket)
if ($user_role === 'STAFF') {
    if ($ticket['assigned_user_id'] != $user_id) {
        die("Unauthorized Access");
    }
}

// COORDINATOR (FIXED SAFE LOGIC)
if ($user_role === 'CORD') {

    // get coordinator department from DB (NOT SESSION)
    $stmt = $pdo->prepare("SELECT department FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_dept = $stmt->fetchColumn();

    if (!$user_dept) {
        die("Unauthorized Access");
    }

    // compare with ticket stream
    if (strtoupper(trim($user_dept)) !== strtoupper(trim($ticket['stream']))) {
        die("Unauthorized Access");
    }
}

// =========================
// POST ACTIONS
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$is_public) {

    $comment = trim($_POST['comment'] ?? '');
    $new_status = $ticket['status'];
    $assigned_user_id = $ticket['assigned_user_id'];

    // COORDINATOR ACTIONS
    if ($user_role === 'CORD') {

        // assign staff
        if (isset($_POST['assign_staff']) && $ticket['status'] === 'OPEN') {

            $stmt = $pdo->prepare("
                SELECT user_id 
                FROM users 
                WHERE role='STAFF' 
                AND department = ?
                LIMIT 1
            ");
            $stmt->execute([$ticket['stream']]);

            $assigned_user_id = $stmt->fetchColumn();
            $new_status = 'IN-PROGRESS';
        }

        // close ticket
        if (isset($_POST['close_ticket']) && $ticket['status'] === 'RESOLVED') {
            $new_status = 'CLOSED';

            $pdo->prepare("UPDATE tickets SET closed_at = NOW() WHERE ticket_id = ?")
                ->execute([$ticket_id]);
        }
    }

    // STAFF ACTION
    if ($user_role === 'STAFF') {

        if (isset($_POST['resolve_ticket']) && $ticket['status'] === 'IN-PROGRESS') {

            $new_status = 'RESOLVED';

            // send back to coordinator
            $stmt = $pdo->prepare("
                SELECT user_id 
                FROM users 
                WHERE role='CORD' 
                AND department = ?
                LIMIT 1
            ");
            $stmt->execute([$ticket['stream']]);

            $assigned_user_id = $stmt->fetchColumn();

            $pdo->prepare("UPDATE tickets SET resolved_at = NOW() WHERE ticket_id = ?")
                ->execute([$ticket_id]);
        }
    }

    // UPDATE TICKET
    $pdo->prepare("
        UPDATE tickets 
        SET status=?, assigned_user_id=? 
        WHERE ticket_id=?
    ")->execute([$new_status, $assigned_user_id, $ticket_id]);

    // ADD COMMENT
    if (!empty($comment)) {
        $pdo->prepare("
            INSERT INTO ticket_comments (ticket_id, user_id, comment_text)
            VALUES (?,?,?)
        ")->execute([$ticket_id, $user_id, $comment]);
    }

    header("Location: myticket.php");
    exit;
}

// =========================
// COMMENTS
// =========================
$stmt = $pdo->prepare("
    SELECT tc.*, u.username 
    FROM ticket_comments tc
    LEFT JOIN users u ON tc.user_id = u.user_id
    WHERE tc.ticket_id = ?
    ORDER BY tc.created_at ASC
");
$stmt->execute([$ticket_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =========================
// ATTACHMENT
// =========================
$stmt = $pdo->prepare("
    SELECT attachment_id, filename 
    FROM ticket_attachments 
    WHERE ticket_id = ?
");
$stmt->execute([$ticket_id]);
$attachment = $stmt->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container">

    <h2>Ticket #<?= htmlspecialchars($ticket['ticket_number']) ?></h2>

    <span class="status-<?= strtolower($ticket['status']) ?>">
        <?= htmlspecialchars($ticket['status']) ?>
    </span>

    <div class="card">
        <p><strong>Subject:</strong> <?= htmlspecialchars($ticket['title']) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
        <p>
            <strong>Category:</strong> <?= htmlspecialchars($ticket['category']) ?> |
            <strong>Stream:</strong> <?= htmlspecialchars($ticket['stream']) ?>
        </p>

        <?php if($attachment): ?>
            <p>
                <strong>Attachment:</strong>
                <a href="view_attachment.php?id=<?= $attachment['attachment_id'] ?>" target="_blank">
                    <?= htmlspecialchars($attachment['filename']) ?>
                </a>
            </p>
        <?php endif; ?>
    </div>

    <h3>History & Comments</h3>

    <?php foreach($comments as $c): ?>
        <div class="card" style="background:#f9f9f9; padding:10px;">
            <p>
                <strong><?= htmlspecialchars($c['username'] ?? 'System') ?></strong>
                (<?= $c['created_at'] ?>)
            </p>
            <p><?= nl2br(htmlspecialchars($c['comment_text'])) ?></p>
        </div>
    <?php endforeach; ?>

    <?php if (!$is_public && $ticket['status'] != 'CLOSED'): ?>
    <div class="card">
        <h3>Take Action</h3>

        <form method="POST">

            <textarea name="comment" class="form-control" required></textarea><br>

            <?php if ($user_role === 'CORD' && $ticket['status']=='OPEN'): ?>
                <button type="submit" name="assign_staff" class="btn btn-primary">
                    Assign Staff
                </button>
            <?php endif; ?>

            <?php if ($user_role === 'STAFF' && $ticket['status']=='IN-PROGRESS'): ?>
                <button type="submit" name="resolve_ticket" class="btn btn-success">
                    Resolve
                </button>
            <?php endif; ?>

            <?php if ($user_role === 'CORD' && $ticket['status']=='RESOLVED'): ?>
                <button type="submit" name="close_ticket" class="btn btn-danger">
                    Close
                </button>
            <?php endif; ?>

        </form>
    </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>