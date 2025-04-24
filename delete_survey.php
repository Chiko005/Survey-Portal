<?php
session_start();
require_once 'dbConnect.php';

// Check if user is admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@example.com') {
    header("Location: index.php");
    exit();
}

// Check if survey_id is provided
if (!isset($_POST['survey_id'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Survey ID is required'];
    header("Location: admin_dashboard.php");
    exit();
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // First delete all questions related to this survey
    $stmt = $pdo->prepare("DELETE FROM questions WHERE survey_id = ?");
    $stmt->execute([$_POST['survey_id']]);

    // Then delete the survey
    $stmt = $pdo->prepare("DELETE FROM surveys WHERE id = ?");
    $stmt->execute([$_POST['survey_id']]);

    // Commit transaction
    $pdo->commit();

    $_SESSION['message'] = ['type' => 'success', 'text' => 'Survey deleted successfully'];
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Error deleting survey: ' . $e->getMessage()];
}

// Redirect back to dashboard
header("Location: admin_dashboard.php");
exit(); 