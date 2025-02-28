<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email, $data->otp)) {
    echo json_encode(["error" => "Email and OTP required"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND email_otp = ?");
$stmt->execute([$data->email, $data->otp]);
$user = $stmt->fetch();

if ($user) {
    $update = $conn->prepare("UPDATE users SET email_verified = 1, email_otp = NULL WHERE email = ?");
    $update->execute([$data->email]);
    echo json_encode(["message" => "Email verified successfully"]);
} else {
    echo json_encode(["error" => "Invalid OTP"]);
}
?>