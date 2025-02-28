<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email, $data->password)) {
    echo json_encode(["responseSuccessful" => false, "responseMessage" => "Email and password required", "responseBody" => null]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$data->email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data->password, $user["password_hash"])) {
    if ($user["email_verified"] == 0) {
        echo json_encode(["responseSuccessful" => false, "responseMessage" => "Email not verified", "responseBody" => null]);
    } else {
        echo json_encode(["responseSuccessful" => true, "responseMessage" => "Login successful", "responseBody" => $user]);
    }
} else {
    echo json_encode(["responseSuccessful" => false, "responseMessage" => "Invalid credentials", "responseBody" => null]);
}
?>
