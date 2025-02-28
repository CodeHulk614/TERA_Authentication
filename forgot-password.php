<?php
require 'db.php';
require 'mail.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email)) {
    echo json_encode(["responseSuccessful" => false, "responseMessage" => "Email is required", "responseBody" => null]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$data->email]);
$user = $stmt->fetch();

if ($user) {
    $otp = rand(100000, 999999);
    $update = $conn->prepare("UPDATE users SET email_otp = ? WHERE email = ?");
    $update->execute([$otp, $data->email]);

    sendEmail($data->email, "Password Reset OTP", "Your OTP is: $otp");

    echo json_encode(["responseSuccessful" => true, "responseMessage" => "OTP sent", "responseBody" => null]);
} else {
    echo json_encode(["responseSuccessful" => false, "responseMessage" => "Email not found", "responseBody" => null]);
}
?>
