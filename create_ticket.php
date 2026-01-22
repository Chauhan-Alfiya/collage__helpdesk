<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

$msg = "";

/* CSRF Token */
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CSRF Check */
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('Invalid CSRF Token');
    }

    $requester_type = trim($_POST['requester_type']);
    $email          = trim($_POST['email']);
    $stream         = trim($_POST['stream']);
    $category       = trim($_POST['category']);
    $title          = trim($_POST['title']);
    $desc           = trim($_POST['description']);

    $assigned_to = getCoordinatorId($pdo, $category, $stream);

    if ($assigned_to) {

        $ticket_num = generateTicketNumber();

        $sql = "INSERT INTO tickets 
                (ticket_number, requester_email, requester_type, stream, category, title, description, assigned_user_id, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'OPEN', NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $ticket_num,
            $email,
            $requester_type,
            $stream,
            $category,
            $title,
            $desc,
            $assigned_to
        ]);

        $ticket_id = $pdo->lastInsertId();

        /* File Upload */
        if (!empty($_FILES['attachment']['name'])) {

            $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $_FILES['attachment']['tmp_name']);
            finfo_close($finfo);

            if ($_FILES['attachment']['size'] > $maxSize) {

                $msg = "<div class='alert error-alert'>File size exceeds 5MB limit.</div>";

            } elseif (!in_array($mime, $allowedTypes)) {

                $msg = "<div class='alert error-alert'>Invalid file format. Only PDF, JPG, PNG allowed.</div>";

            } else {

                $stmtAtt = $pdo->prepare("
                    INSERT INTO ticket_attachments 
                    (ticket_id, filename, file_data, mime_type) 
                    VALUES (?, ?, ?, ?)
                ");

                $stmtAtt->execute([
                    $ticket_id,
                    $_FILES['attachment']['name'],
                    file_get_contents($_FILES['attachment']['tmp_name']),
                    $mime
                ]);
            }
        }

        if ($msg === "") {
            $emailSafe = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

            $msg = "
            <div class='alert success-alert'>
                <div class='alert-icon'><i class='fas fa-check-circle'></i></div>
                <div class='alert-text'>
                    <strong>Ticket Created!</strong> Your reference is <b>#$ticket_num</b>.
                    <br><a href='myticket.php?email=$emailSafe'>Track your ticket here &rarr;</a>
                </div>
            </div>";
        }

    } else {
        $msg = "<div class='alert error-alert'>
                    <i class='fas fa-exclamation-circle'></i>
                    Assignment failed. Please contact support.
                </div>";
    }
}
?>

<?php include 'includes/header.php'; ?>


<link rel="stylesheet" href="css/create_ticket.css">

<div class="wrapper">
    <div class="main-card">
        <div class="form-header">
            <h1 style="color: #1e40af;">Create Ticket</h1>
            <p>Describe your issue and we'll resolve it as soon as possible.</p>
        </div>

        <?= $msg ?>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label>Identification</label>
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
                        <input type="email" name="email" class="form-control" placeholder="" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Academic Stream</label>
                    <div class="input-wrapper">
                        <select name="stream" class="form-control" required>
                            <option value="MCA">MCA</option>
                            <option value="BBA">BBA</option>
                            <option value="BCA">BCA</option>
                            <option value="BCom">BCom</option>
                        </select>
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <div class="input-wrapper">
                        <select name="category" class="form-control" required>
                            <option value="Academic">Academic</option>
                            <option value="Administrative">Administrative</option>
                            <option value="Technical">Technical</option>
                            <option value="Facility">Facility</option>
                        </select>
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Ticket Subject</label>
                <div class="input-wrapper">
                    <input type="text" name="title" class="form-control" placeholder="Brief summary of the issue" required>
                    <i class="fas fa-pen-nib"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Detailed Description</label>
                <textarea name="description" class="form-control" placeholder="Please provide all details..." required></textarea>
            </div>

            <div class="form-group">
                 <label>Attachment (Optional):</label>
                 <input type="file" name="attachment" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Create Ticket <i class="fas fa-arrow-right"></i>
            </button>

        </form>
    </div>
</div>

