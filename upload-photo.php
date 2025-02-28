<?php
require 'db.php';

header("Content-Type: application/json");

$upload_dir = "uploads/";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Check if email and file are provided
if (!isset($_POST["email"]) || !isset($_FILES["file"])) {
    echo json_encode([
        "responseSuccessful" => false,
        "responseMessage" => "Email and file are required",
        "responseBody" => $_FILES
    ]);
    exit;
}

$email = $_POST["email"];
$file = $_FILES["file"];

// Debugging: Print out file details
if ($file["error"] !== 0) {
    echo json_encode([
        "responseSuccessful" => false,
        "responseMessage" => "File upload error: " . $file["error"],
        "responseBody" => null
    ]);
    exit;
}

// Ensure the user folder exists
$user_dir = $upload_dir . $email . "/";
if (!is_dir($user_dir)) {
    if (!mkdir($user_dir, 0777, true)) {
        echo json_encode([
            "responseSuccessful" => false,
            "responseMessage" => "Failed to create user directory",
            "responseBody" => null
        ]);
        exit;
    }
}

// Generate a unique filename
$filename = time() . "_" . basename($file["name"]);
$file_path = $user_dir . $filename;

// Try moving the uploaded file
if (move_uploaded_file($file["tmp_name"], $file_path)) {
    echo json_encode([
        "responseSuccessful" => true,
        "responseMessage" => "File uploaded successfully",
        "responseBody" => ["profile_photo_url" => $file_path]
    ]);
} else {
    echo json_encode([
        "responseSuccessful" => false,
        "responseMessage" => "move_uploaded_file failed",
        "responseBody" => [
            "file_tmp_name" => $file["tmp_name"],
            "destination_path" => $file_path,
            "permissions" => substr(sprintf('%o', fileperms($upload_dir)), -4),
            "user_dir_exists" => is_dir($user_dir) ? "yes" : "no"
        ]
    ]);
}
?>
