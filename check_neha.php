<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

// Search for neha in users table
$nehaUsers = DB::table("users")
    ->where("name", "like", "%neha%")
    ->orWhere("email", "like", "%neha%")
    ->get();

echo "Users matching neha:\n";
echo json_encode($nehaUsers, JSON_PRETTY_PRINT) . "\n\n";

// Count total users created by neha (if there is a created_by field)
$tables = DB::select("SHOW TABLES");
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    $columns = DB::select("SHOW COLUMNS FROM $tableName");
    $hasCreatedBy = false;
    foreach ($columns as $column) {
        if (strtolower($column->Field) === "created_by" || strtolower($column->Field) === "user_id") {
            $hasCreatedBy = true;
            break;
        }
    }
    if ($hasCreatedBy) {
        $count = DB::table($tableName)->where("created_by", "like", "%neha%")->count();
        if ($count > 0) {
            echo "Table $tableName has $count records created by neha\n";
        }
    }
}
