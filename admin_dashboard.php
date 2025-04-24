<?php
session_start();

if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@example.com') {
    header("Location: index.php");
    exit();
}

require_once 'dbConnect.php';

// Fetch all users
$users = $pdo->query("SELECT id, email FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all surveys
$surveys = $pdo->query("SELECT * FROM surveys")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all comments
$comments = $pdo->query("SELECT * FROM comments")->fetchAll(PDO::FETCH_ASSOC);

// Count surveys per user
$survey_counts = $pdo->query("SELECT user_id, COUNT(*) as count FROM surveys GROUP BY user_id")->fetchAll(PDO::FETCH_KEY_PAIR);

// Count total surveys
$total_surveys = count($surveys);

// HTML Starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 p-6">
    <div class="absolute top-0 left-0 w-full bg-white dark:bg-gray-800 shadow-md p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">Survey Portal</h1>
        <button onclick="toggleDarkMode()" class="text-gray-600 dark:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0112 21.75 9.75 9.75 0 0112 2.25c.35 0 .694.018 1.032.053a0.75 0.75 0 01.268 1.415 7.5 7.5 0 1010.49 10.49 0.75 0.75 0 01-1.415.268c-.035.338-.053.682-.053 1.032z" />
            </svg>
        </button>
    </div>

    <div class="mt-16">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">Admin Dashboard</h1>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Total Surveys</h2>
                <p class="text-2xl text-blue-600 dark:text-blue-400"><?= $total_surveys ?></p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Total Comments</h2>
                <p class="text-2xl text-green-600 dark:text-green-400"><?= count($comments) ?></p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Total Users</h2>
                <p class="text-2xl text-purple-600 dark:text-purple-400"><?= count($users) ?></p>
            </div>
        </div>

        <!-- User Survey Counts -->
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">User Survey Counts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <?php foreach ($users as $user): ?>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow">
                <p class="font-medium text-gray-800 dark:text-white">Email: <?= htmlspecialchars($user['email']) ?></p>
                <p class="text-gray-600 dark:text-gray-300">Surveys: <?= $survey_counts[$user['id']] ?? 0 ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Surveys List -->
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">All Surveys</h2>
        <div class="space-y-4">
            <?php foreach ($surveys as $survey): ?>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow">
                <p class="font-semibold text-lg text-gray-800 dark:text-white">Survey ID: <?= $survey['id'] ?></p>
                <p class="text-gray-600 dark:text-gray-300">Title: <?= htmlspecialchars($survey['title']) ?></p>
                <p class="text-gray-600 dark:text-gray-300">Description: <?= htmlspecialchars($survey['description']) ?></p>
                <p class="text-sm text-gray-500 dark:text-gray-400">User ID: <?= $survey['user_id'] ?> | Status: <?= $survey['status'] ?> | Created: <?= date('M j, Y', strtotime($survey['created_at'])) ?></p>
                <form action="delete_survey.php" method="post" class="mt-2">
                    <input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl">Delete Survey</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
