<?php
session_start();
require_once 'dbConnect.php';

// Check admin privileges
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Handle response deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    try {
        $responseId = (int)$_GET['id'];
        $pdo->beginTransaction();
        
        // Delete response answers first
        $pdo->prepare("DELETE FROM response_answers WHERE response_id = ?")->execute([$responseId]);
        
        // Then delete response
        $stmt = $pdo->prepare("DELETE FROM responses WHERE id = ?");
        $stmt->execute([$responseId]);
        
        $pdo->commit();
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Response deleted successfully'];
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Error: ' . $e->getMessage()];
    }
    
    header('Location: admin_responses.php');
    exit;
}

// Get all responses with related data
try {
    $responses = $pdo->query("
        SELECT r.*, s.title as survey_title, u.name as user_name, u.email as user_email,
               (SELECT COUNT(*) FROM response_answers WHERE response_id = r.id) as answer_count
        FROM responses r
        JOIN surveys s ON r.survey_id = s.id
        LEFT JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC
    ")->fetchAll();
} catch (PDOException $e) {
    die("Error loading responses: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Responses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="absolute top-0 left-0 w-full bg-white dark:bg-gray-800 shadow-md p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">Survey Portal</h1>
        <button onclick="toggleDarkMode()" class="text-gray-600 dark:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0112 21.75 9.75 9.75 0 0112 2.25c.35 0 .694.018 1.032.053a0.75 0.75 0 01.268 1.415 7.5 7.5 0 1010.49 10.49 0.75 0.75 0 01-1.415.268c-.035.338-.053.682-.053 1.032z" />
            </svg>
        </button>
    </div>

    <div class="flex mt-16">
        <?php include 'admin_sidebar.php'; ?>

        <div class="flex-1 overflow-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Survey Responses</h2>
            </div>

            <!-- Message Alert -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="mb-4 p-4 rounded-lg <?= $_SESSION['message']['type'] === 'success' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' ?>">
                    <?= $_SESSION['message']['text'] ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Survey</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Respondent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Answers</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($responses as $response): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($response['survey_title']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white"><?= htmlspecialchars($response['user_name'] ?? 'Anonymous') ?></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($response['user_email'] ?? '') ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <?= $response['answer_count'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <?= date('M j, Y', strtotime($response['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="?id=<?= $response['id'] ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="?action=delete&id=<?= $response['id'] ?>" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete" onclick="return confirm('Delete this response permanently?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>