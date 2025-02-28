<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email, $data->fax, $data->realtor_membership, $data->profile_photo_url)) {
    echo json_encode(["responseSuccessful" => false, "responseMessage" => "All fields are required", "responseBody" => null]);
    exit;
}

// Update user profile
$stmt = $conn->prepare("UPDATE users SET fax = ?, realtor_membership = ?, profile_photo = ? WHERE email = ?");
$stmt->execute([$data->fax, $data->realtor_membership, $data->profile_photo_url, $data->email]);

echo json_encode(["responseSuccessful" => true, "responseMessage" => "Profile updated successfully", "responseBody" => null]);
?>
