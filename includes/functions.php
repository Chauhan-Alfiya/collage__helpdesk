<?php
// Function to generate unique ticket number
function generateTicketNumber() {
    return 'HD-' . date('Ymd') . '-' . rand(1000, 9999);
}

// Function to route ticket to correct Coordinator ID based on Category & Stream
function getCoordinatorId($pdo, $category, $stream) {
    $role_name = '';
    
    if ($category == 'Academic') {
        $role_name = strtoupper($stream) . '_CORD';
    } elseif ($category == 'Administrative') {
        $role_name = 'ADMINI_CORD';
    } elseif ($category == 'Technical') {
        $role_name = 'TECH_CORD';
    } elseif ($category == 'Facility') {
        $role_name = 'FACILITY_CORD';
    }
    
    $stmt = $pdo->prepare("SELECT u.user_id FROM users u JOIN roles r ON u.role_id = r.role_id WHERE r.role_name = ? LIMIT 1");
    $stmt->execute([$role_name]);
    $user = $stmt->fetch();
    return $user ? $user['user_id'] : null; 
    // Fallback logic could be added here to assign to generic admin if specific cord not found
}

// Function to route ticket to Staff based on Coordinator
function getStaffIdByCoordinator($pdo, $coordinator_id) {
    // Logic: Find the corresponding staff role for this coordinator's stream/cat
    // Simplify: Get Cord role, replace _CORD with _STAFF, find user
    $stmt = $pdo->prepare("SELECT r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.user_id = ?");
    $stmt->execute([$coordinator_id]);
    $cord_role = $stmt->fetchColumn();
    
    $staff_role = str_replace('_CORD', '_STAFF', $cord_role);
    
    $stmt = $pdo->prepare("SELECT u.user_id FROM users u JOIN roles r ON u.role_id = r.role_id WHERE r.role_name = ? LIMIT 1");
    $stmt->execute([$staff_role]);
    $user = $stmt->fetch();
    return $user ? $user['user_id'] : null;
}

// Simple email mock
function sendNotification($email, $subject, $message) {
    // In production, use mail() or PHPMailer. 
    // For now, we assume success.
    return true;
}
?>