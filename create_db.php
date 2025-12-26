<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'u916293666_vcardsaas', 'U916293666_vcardsaas1');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS u916293666_japy_tag');
    echo 'Database created successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
