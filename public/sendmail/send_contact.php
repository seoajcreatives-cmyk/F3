<?php
$secretKey = "6Lc2YOsrAAAAAOl927DoXMIeb_fyHNi-Gl2995EA";
$responseKey = $_POST['g-recaptcha-response'];
$userIP = $_SERVER['REMOTE_ADDR'];

$verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
$response = file_get_contents($verifyUrl);
$responseKeys = json_decode($response, true);

if (!$responseKeys["success"]) {
    echo json_encode(array("success" => false, "message" => "Please verify the reCAPTCHA."));
    exit();
}

$name = $_POST["name"] ?? "";
$lastName = $_POST["lastName"] ?? "";
$phone = $_POST["phone"] ?? "";
$email = $_POST["email"] ?? "";
$message = $_POST["message"] ?? "";

if (trim($name) == "" || trim($lastName) == "" || trim($phone) == "" || trim($email) == "") {
    echo json_encode(array("success" => false, "message" => "You cannot send an empty message."));
    exit();
}

$to = "f3ccorp@gmail.com, ajconversionsseo@gmail.com"; 
$subject = "F3 Construction Corp - Contact Form";

$headers = "From: F3 Construction <noreply@f3constructionny.com>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$fullmessage = "
    <h2>New Project Inquiry</h2>
    <p><strong>Name:</strong> $name $lastName</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Message:</strong><br>$message</p>
";

if (mail($to, $subject, $fullmessage, $headers)) {
    enviarAGoogleSheets($name, $lastName, $phone, $email, $message);
    echo json_encode(array("success" => true));
} else {
    echo json_encode(array("success" => false, "message" => "Server error. Please try again."));
}

function enviarAGoogleSheets($name, $lastName, $phone, $email, $message) {
    $gs_url = "https://script.google.com/macros/s/AKfycbxONf11PQ0jpZ9T73_abZ76_1DkIrsd8lpTlUTtRK81Tc_oPDlEXm3L5S42Xj_i3Tvu/exec";
    $gs_token = "AdjaisjdaijSDAJDI84f8df8wda43adwdadjasidj4839ajd";
    $payload = [
        "token" => $gs_token,
        "name" => $name,
        "lastName" => $lastName,
        "phone" => $phone,
        "email" => $email,
        "message" => $message,
        "page" => $_SERVER["HTTP_REFERER"] ?? "Contact Page"
    ];

    $ch = curl_init($gs_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_exec($ch);
    curl_close($ch);
}
?>