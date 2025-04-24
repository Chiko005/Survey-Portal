<?php
session_start();
require 'dbConnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

$survey_id = $_POST['survey_id'];
$vote_type = $_POST['vote_type']; // 'up' or 'down'

// Check if the user already voted
$stmt = $pdo->prepare("SELECT * FROM votes WHERE user_id = ? AND survey_id = ?");
$stmt->execute([$user_id, $survey_id]);
$existing = $stmt->fetch();

if ($existing) {
    $stmt = $pdo->prepare("UPDATE votes SET vote_type = ? WHERE user_id = ? AND survey_id = ?");
    $stmt->execute([$vote_type, $user_id, $survey_id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO votes (user_id, survey_id, vote_type) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $survey_id, $vote_type]);
}

header("Location: home.php");
exit;
