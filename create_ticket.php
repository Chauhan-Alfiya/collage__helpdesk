<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

$msg = ""; 

if (!isset($_SESSION['username'])) {
    header("Location: common_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requester_type = $_POST['requester_type'];
    $email          = $_POST['email'];
    $stream         = $_POST['stream'];
    $category       = $_POST['category'];
    $title          = $_POST['title'];
    $desc           = $_POST['description'];

    $assigned_to = getCoordinatorId($pdo, $category, $stream);

    if ($assigned_to) {
        $ticket_num = generateTicketNumber();
        $sql = "INSERT INTO tickets (ticket_number, requester_email, requester_type, stream, category, title, description, assigned_user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ticket_num, $email, $requester_type, $stream, $category, $title, $desc, $assigned_to]);

        $ticket_id = $pdo->lastInsertId();

        if (!empty($_FILES['attachment']['name'])) {
            $stmtAtt = $pdo->prepare("INSERT INTO ticket_attachments (ticket_id, filename, file_data, mime_type) VALUES (?, ?, ?, ?)");
            $stmtAtt->execute([$ticket_id, $_FILES['attachment']['name'], file_get_contents($_FILES['attachment']['tmp_name']), $_FILES['attachment']['type']]);
        }

        $msg = "<div class='alert success'>
                    <i class='fas fa-check-circle'></i> Ticket Created! No: <b>$ticket_num</b>. 
                    <a href='myticket.php'>View My Tickets</a>
                </div>";
    } else {
        $msg = "<div class='alert error'>No coordinator found for this category.</div>";
    }
}
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="css/create_ticket.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="wrapper">
    <div class="main-card">
        <div class="form-header">
            <h1 style="color:#1e40af">Create Ticket</h1>
            <p>Submit your issue and we'll help you soon.</p>
        </div>

        <?= $msg ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label>Identity</label>
                    <div class="input-wrapper">
                        <select name="requester_type" class="form-control" required>
                            <option value="Student">Student</option>
                            <option value="Faculty">Faculty</option>
                        </select>
                        <i class="fas fa-user-tag"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" class="form-control" placeholder="Email used in registration" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Stream</label>
                    <div class="input-wrapper">
                        <select name="stream" class="form-control" required>
                            <option value="MCA">MCA</option>
                            <option value="BBA">BBA</option>
                            <option value="BCA">BCA</option>
                        </select>
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <div class="input-wrapper">
                        <select name="category" class="form-control" required>
                            <option value="Academic">Academic</option>
                            <option value="Technical">Technical</option>
                            <option value="Facility">Facility</option>
                            <option value="Other">Other</option>
                        </select>
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Subject</label>
                <div class="input-wrapper">
                    <input type="text" name="title" class="form-control" placeholder="Brief title" required>
                    <i class="fas fa-pen"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" placeholder="Describe your issue..." required></textarea>
            </div>

            <div class="form-group">
                <label>Attachment (Optional)</label>
                <input type="file" name="attachment" class="form-control" style="padding-left:15px;">
            </div>

            <button type="submit" class="btn-submit">Create Ticket <i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>