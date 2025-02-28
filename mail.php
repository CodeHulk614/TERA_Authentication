<?php
function sendEmail($to, $subject, $message) {
    $headers = "From: no-tera@tera-testing.com\r\n";
    $headers .= "Reply-To: tera@tera-testing.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($to, $subject, $message, $headers);
}
?>
