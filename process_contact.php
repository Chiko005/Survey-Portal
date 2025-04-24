<?php
session_start();
require 'dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'All fields are required'];
        header("Location: contact.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Please enter a valid email address'];
        header("Location: contact.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);


        $_SESSION['message'] = ['type' => 'success', 'text' => 'Thank you for your message! We will get back to you soon.'];
    } catch (PDOException $e) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Sorry, there was an error processing your message. Please try again later.'];
    }

    header("Location: contact.php");
    exit;
} else {
    header("Location: contact.php");
    exit;
} 