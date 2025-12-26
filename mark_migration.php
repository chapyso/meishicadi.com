<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u916293666_vcardsaas', 'u916293666_vcardsaas', 'U916293666_vcardsaas1');
    $pdo->exec("INSERT INTO migrations (migration, batch) VALUES ('2021_07_28_114939_create_settings_table', 1008) ON DUPLICATE KEY UPDATE batch = 1008");
    echo 'Settings migration marked as completed';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
