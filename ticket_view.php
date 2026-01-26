<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

$ticket_num = $_GET['ticket'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_number = ?");
$stmt->execute([$ticket_num]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die("Ticket nahi mili!");
}

if (isset($_POST['save_changes'])) {
    $new_title = $_POST['title'];
    $new_desc  = $_POST['description'];

    $sql = "UPDATE tickets SET title = ?, description = ? WHERE ticket_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$new_title, $new_desc, $ticket['ticket_id']]);

    header("Location: ticket_view.php?ticket=$ticket_num&status=updated");
    exit();
}

if (isset($_POST['confirm_delete'])) {
    $pdo->prepare("DELETE FROM ticket_comments WHERE ticket_id = ?")->execute([$ticket['ticket_id']]);
    $pdo->prepare("DELETE FROM ticket_attachments WHERE ticket_id = ?")->execute([$ticket['ticket_id']]);
    
    $pdo->prepare("DELETE FROM tickets WHERE ticket_id = ?")->execute([$ticket['ticket_id']]);

    header("Location: myticket.php?msg=deleted");
    exit();
}

include 'includes/header.php';
?>

<div class="wrapper" style="max-width: 800px; margin: 40px auto; padding: 20px;">
    
    <?php if(isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> Ticket successfully update!
        </div>
    <?php endif; ?>

    <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="margin:0;">Manage Ticket #<?= $ticket['ticket_number'] ?></h2>
            <form method="POST" onsubmit="return confirm('Kya aap sach mein ye ticket DELETE karna chahte hain? Ye wapas nahi aayegi!');">
                <button type="submit" name="confirm_delete" style="background: #fee2e2; color: #b91c1c; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-trash"></i> Delete Ticket
                </button>
            </form>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 25px;">

        <form method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Ticket Subject:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($ticket['title']) ?>" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px;" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Detailed Description:</label>
                <textarea name="description" rows="6" 
                          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; font-family: inherit;" required><?= htmlspecialchars($ticket['description']) ?></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="myticket.php" style="text-decoration: none; padding: 12px 20px; color: #64748b; font-weight: 600;">Cancel</a>
                <button type="submit" name="save_changes" 
                        style="background: #2563eb; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>