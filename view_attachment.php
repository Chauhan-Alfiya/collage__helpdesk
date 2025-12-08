<?php
include 'includes/db.php';
if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM ticket_attachments WHERE attachment_id = ?");
    $stmt->execute([$_GET['id']]);
    $file = $stmt->fetch();
    
    if($file) {
        header("Content-Type: " . $file['mime_type']);
        header("Content-Disposition: inline; filename=\"" . $file['filename'] . "\"");
        echo $file['file_data'];
        exit;
    }
}
echo "File not found.";
?>