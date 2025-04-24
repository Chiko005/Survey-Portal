<?php
session_start();
require 'dbConnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: notifications.php");
    exit;
}

$survey_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Debug information
error_log("Survey ID: " . $survey_id);
error_log("User ID: " . $user_id);

// Get all comments for this survey with user information
$stmt = $pdo->prepare("
    SELECT c.*, u.name, u.email
    FROM comments c
    INNER JOIN users u ON c.user_id = u.id
    WHERE c.survey_id = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$survey_id]);
$comments = $stmt->fetchAll();

// Debug information
error_log("Number of comments found: " . count($comments));
if (!empty($comments)) {
    error_log("First comment: " . print_r($comments[0], true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        window.onload = () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        };
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex flex-col">
    <!-- Top bar -->
    <div class="w-full p-4 bg-white dark:bg-gray-800 shadow-md flex justify-between items-center">
        <h1 class="text-2xl font-bold">Comments</h1>
        <div class="flex items-center space-x-4">
            <a href="notifications.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Back to Notifications</a>
            <button onclick="toggleDarkMode()" class="text-gray-800 dark:text-gray-200">🌗</button>
            <a href="logout.php" class="text-red-600 dark:text-red-400 font-semibold">Logout</a>
        </div>
    </div>

    <!-- Main content -->
    <main class="flex-grow px-6 py-10">
        <?php if (empty($comments)): ?>
            <div class="text-center py-8">
                <p class="text-gray-600 dark:text-gray-400">No comments yet.</p>
                <!-- Debug information -->
                <p class="text-sm text-gray-500 mt-2">Survey ID: <?= $survey_id ?></p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($comments as $comment): ?>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold"><?= htmlspecialchars($comment['name']) ?></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($comment['email']) ?></p>
                                <p class="mt-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($comment['comment_text']) ?></p>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <?= date('M j, Y g:i A', strtotime($comment['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 shadow-md border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <p class="text-gray-500 dark:text-gray-400">&copy; <?php echo date('Y'); ?> Survey Portal. All rights reserved.</p>
                </div>
                <div class="flex space-x-6">
                    <a href="about.php" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">About Us</a>
                    <a href="contact.php" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">Contact Us</a>
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">Privacy Policy</a>
                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
</body>
</html> 