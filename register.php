<?php
require 'db.php';
require 'mail.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->real_estate_license, $data->state, $data->full_name, $data->email, $data->phone, $data->password)) {
    echo json_encode(["responseSuccessful" => false, "responseMessage" => "All fields are required", "responseBody" => null]);
    exit;
}

$email_otp = rand(100000, 999999);
$password_hash = password_hash($data->password, PASSWORD_BCRYPT);

try {
    $stmt = $conn->prepare("INSERT INTO users (real_estate_license, state, full_name, email, phone, password_hash, email_otp) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$data->real_estate_license, $data->state, $data->full_name, $data->email, $data->phone, $password_hash, $email_otp]);

    sendEmail($data->email, "Email Verification", "Your OTP is: $email_otp");

    echo json_encode(["responseSuccessful" => true, "responseMessage" => "Registration successful. Verify email.", "responseBody" => null]);
} catch (PDOException $e) {
    echo json_encode(["responseSuccessful" => false, "responseMessage" => "Email already exists", "responseBody" => null]);
}
?>
