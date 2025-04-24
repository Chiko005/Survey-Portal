<?php
session_start();
require 'dbConnect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Get latest comments
$stmt = $pdo->prepare("SELECT c.*, s.title as survey_title, u.name, u.email
                       FROM comments c
                       INNER JOIN surveys s ON c.survey_id = s.id
                       INNER JOIN users u ON c.user_id = u.id
                       WHERE s.user_id = ?
                       ORDER BY c.created_at DESC
                       LIMIT 10");
$stmt->execute([$user_id]);
$comments = $stmt->fetchAll();

// Get user's surveys with comment information
$stmt = $pdo->prepare("
    SELECT s.id, s.title, 
           COUNT(c.id) as comment_count,
           MAX(c.created_at) as latest_comment
    FROM surveys s
    LEFT JOIN comments c ON s.id = c.survey_id
    WHERE s.user_id = ?
    GROUP BY s.id, s.title
    ORDER BY COALESCE(MAX(c.created_at), '1970-01-01') DESC
");
$stmt->execute([$user_id]);
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debug information
error_log("User ID: " . $user_id);
error_log("Number of surveys: " . count($surveys));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Notifications</title>
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
    <h1 class="text-2xl font-bold">Your Notifications</h1>
    <div class="flex items-center space-x-4">
      <a href="home.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Home</a>
      <button onclick="toggleDarkMode()" class="text-gray-800 dark:text-gray-200">🌗</button>
      <a href="logout.php" class="text-red-600 dark:text-red-400 font-semibold">Logout</a>
    </div>
  </div>

  <!-- Main content -->
  <main class="flex-grow px-6 py-10">
    <!-- Latest Comments Section -->
    <?php if (!empty($comments)): ?>
      <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Latest Comments</h2>
        <div class="space-y-4">
          <?php foreach ($comments as $comment): ?>
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-semibold"><?= htmlspecialchars($comment['name']) ?></p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">commented on <?= htmlspecialchars($comment['survey_title']) ?></p>
                  <p class="mt-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($comment['comment_text']) ?></p>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400"><?= date('M j, Y g:i A', strtotime($comment['created_at'])) ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Surveys Section -->
    <h2 class="text-xl font-bold mb-6">Your Surveys</h2>
    <?php if (empty($surveys)): ?>
      <p>You haven't created any surveys yet.</p>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach ($surveys as $survey): ?>
          <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-lg font-semibold"><?= htmlspecialchars($survey['title']) ?></h3>
                <?php if ($survey['comment_count'] > 0): ?>
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    <i class="fas fa-comment mr-1"></i>
                    <?= $survey['comment_count'] ?> comment<?= $survey['comment_count'] > 1 ? 's' : '' ?>
                    <?php if ($survey['latest_comment']): ?>
                      <span class="text-xs text-gray-500">(Latest: <?= date('M j, Y', strtotime($survey['latest_comment'])) ?>)</span>
                    <?php endif; ?>
                  </p>
                <?php endif; ?>
              </div>
              <?php if ($survey['comment_count'] > 0): ?>
                <a href="survey_comments.php?id=<?= $survey['id'] ?>" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                  <i class="fas fa-eye"></i> View Comments
                </a>
              <?php endif; ?>
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
