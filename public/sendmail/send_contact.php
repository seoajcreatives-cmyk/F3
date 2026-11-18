<?php
header("Content-Type: application/json; charset=UTF-8");

// 1. Obtener la IP auténtica del visitante (soporte para Cloudflare / Proxies)
$userIP = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
if (strpos($userIP, ',') !== false) {
    $ips = explode(',', $userIP);
    $userIP = trim($ips[0]);
}

// 2. Técnica Honeypot (Campo trampa invisible para humanos)
// Si un bot rellena este campo oculto, respondemos éxito simulado pero cancelamos todo envío.
$honeypot = trim($_POST['company_website'] ?? '');
if (!empty($honeypot)) {
    echo json_encode(array("success" => true, "message" => "Request processed successfully."));
    exit();
}

// 3. Verificación de Google reCAPTCHA
$secretKey = "6Lc2YOsrAAAAAOl927DoXMIeb_fyHNi-Gl2995EA";
$responseKey = $_POST['g-recaptcha-response'] ?? '';

if (empty($responseKey)) {
    echo json_encode(array("success" => false, "message" => "Please verify the reCAPTCHA."));
    exit();
}

$verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
$verifyData = [
    'secret' => $secretKey,
    'response' => $responseKey,
    'remoteip' => $userIP
];

$ch = curl_init($verifyUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verifyData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$captchaResponse = curl_exec($ch);
curl_close($ch);

$responseKeys = json_decode($captchaResponse, true);
if (!$responseKeys || empty($responseKeys["success"])) {
    echo json_encode(array("success" => false, "message" => "reCAPTCHA verification failed. Please try again."));
    exit();
}

// 4. Captura y sanitización de datos
$name = trim($_POST["name"] ?? "");
$lastName = trim($_POST["lastName"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$email = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($name === "" || $lastName === "" || $phone === "" || $email === "") {
    echo json_encode(array("success" => false, "message" => "Please fill in all required fields."));
    exit();
}

// 5. Validación de formato de Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array("success" => false, "message" => "Please enter a valid email address."));
    exit();
}

// 6. Validación de Teléfono (al menos 10 dígitos numéricos)
$phoneDigits = preg_replace('/[^0-9]/', '', $phone);
if (strlen($phoneDigits) < 10 || strlen($phoneDigits) > 15) {
    echo json_encode(array("success" => false, "message" => "Please enter a valid phone number (at least 10 digits)."));
    exit();
}

// 7. Filtros anti-spam de contenido (Black Hole / Descarte Silencioso)
$allText = $name . ' ' . $lastName . ' ' . $message;

// 7a. Enlaces y URLs: clientes de construcción en NY no envían enlaces web en primer contacto
$hasLinks = preg_match('/(https?:\/\/|www\.|\.ru\b|\.top\b|\.xyz\b|\.cn\b|<a\b|\[url\b)/i', $allText);

// 7b. Caracteres cirílicos (rusos) o alfabetos asiáticos no latinos
$hasCyrillic = preg_match('/[\p{Cyrillic}\p{Han}]/u', $allText);

// 7c. Palabras clave inequívocas de spam automatizado
$spamPatterns = [
    '/\b(seo\s+services?|seo\s+agency|seo\s+expert|seo\s+audit)\b/i',
    '/\b(guest\s*posts?|buy\s+backlinks?|rank\s+on\s+google|google\s+ranking)\b/i',
    '/\b(crypto|cryptocurrency|bitcoin|ethereum|forex\s+trading)\b/i',
    '/\b(telegram\s*:\s*@?|whatsapp\s*:\s*\+?|t\.me\/)\b/i',
    '/\b(viagra|cialis|online\s+casino|sports\s+betting|slot\s+games)\b/i',
    '/\b(increase\s+traffic|domain\s+authority|da\s+score|targeted\s+visitors)\b/i',
    '/\b(porn|adult\s+dating|hookup)\b/i'
];
$hasSpamKeyword = false;
foreach ($spamPatterns as $pattern) {
    if (preg_match($pattern, $allText)) {
        $hasSpamKeyword = true;
        break;
    }
}

// Si es detectado como spam, respondemos éxito simulado pero cancelamos el envío
if ($hasLinks || $hasCyrillic || $hasSpamKeyword) {
    echo json_encode(array("success" => true, "message" => "Thank you! Your request has been sent successfully."));
    exit();
}

// 8. Envío de Correo Legítimo
$to = "antoniagallegom2@gmail.com";
$subject = "F3 Construction Corp - Contact Form";
$headers  = "From: noreply@f3constructionny.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$safeName = htmlspecialchars("$name $lastName", ENT_QUOTES, 'UTF-8');
$safePhone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$safeIP = htmlspecialchars($userIP, ENT_QUOTES, 'UTF-8');

$fullmessage = "
    <h2>New Project Inquiry</h2>
    <p><strong>Name:</strong> $safeName</p>
    <p><strong>Phone:</strong> $safePhone</p>
    <p><strong>Email:</strong> $safeEmail</p>
    <p><strong>Client IP:</strong> $safeIP</p>
    <p><strong>Message:</strong><br>$safeMessage</p>
";

if (mail($to, $subject, $fullmessage, $headers)) {
    enviarAGoogleSheets($name, $lastName, $phone, $email, $message);
    enviarAZapier($name, $lastName, $phone, $email, $message);
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

function enviarAZapier($name, $lastName, $phone, $email, $message) {
    $zapier_url = "https://hooks.zapier.com/hooks/catch/26423574/uelrxfz/";
    $payload = [
        "name" => $name,
        "last_name" => $lastName,
        "phone" => $phone,
        "email" => $email,
        "message" => $message,
        "privacy_consent" => true
    ];

    $ch = curl_init($zapier_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_exec($ch);
    curl_close($ch);
}
?>
