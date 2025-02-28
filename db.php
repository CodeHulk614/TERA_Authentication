<?php
$host = "localhost"; // Find in MySQL settings
$username = "teratest_test";
$password = "Victor614@";
$database = "teratest_test";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>