<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email, $data->otp, $data->password, $data->confirm_password)) {
    echo json_encode([
        "responseSuccessful" => false,
        "responseMessage" => "All fields are required.",
        "responseBody" => null
    ]);
    exit;
}

if ($data->password !== $data->confirm_password) {
    echo json_encode([
        "responseSuccessful" => false,
        "responseMessage" => "Passwords do not match.",
        "responseBody" => null
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND email_otp = ?");
$stmt->execute([$data->email, $data->otp]);
$user = $stmt->fetch();

if ($user) {
    $password_hash = password_hash($data->password, PASSWORD_BCRYPT);
    $update = $conn->prepare("UPDATE users SET password_hash = ?, email_otp = NULL WHERE email = ?");
    $update->execute([$password_hash, $data->email]);

    echo json_encode([
        "responseSuccessful" => true,
        "responseMessage" => "Password reset successful.",
        "responseBody" => null
    ]);
} else {
    echo json_encode([
        "responseSuccessful" => false,
        "responseMessage" => "Invalid OTP.",
        "responseBody" => null
    ]);
}
?>
