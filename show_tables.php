<?php
require 'dbConnect.php';

try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in the database:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 