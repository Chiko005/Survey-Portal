<?php
require 'dbConnect.php';

if (!isset($_GET['table'])) {
    die("Please specify a table name to delete. Example: delete_table.php?table=table_name");
}

$table = $_GET['table'];

try {
    // First check if the table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$table]);
    if (!$stmt->fetch()) {
        die("Table '$table' does not exist.");
    }

    // Drop the table
    $pdo->exec("DROP TABLE IF EXISTS `$table`");
    echo "Table '$table' has been deleted successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 