<?php
session_start();
require 'dbConnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

$survey_id = $_POST['survey_id'];
$comment = htmlspecialchars($_POST['comment']);

$stmt = $pdo->prepare("INSERT INTO comments (user_id, survey_id, comment_text) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $survey_id, $comment]);

// Optional: Notify the survey owner via email if you implement email logic later
// mail($ownerEmail, $subject, $message); <-- only works if mail server is setup

header("Location: home.php");
exit;
