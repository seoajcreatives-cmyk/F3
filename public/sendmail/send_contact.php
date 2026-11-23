<?php
header("Content-Type: application/json; charset=UTF-8");

function logMsg($msg) {
    // Debug logging disabled in production
}

logMsg("--- NEW INCOMING SUBMISSION ---");
logMsg("RAW POST: " . json_encode($_POST));

// 1. Obtener la IP auténtica del visitante
$userIP = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
if (strpos($userIP, ',') !== false) {
    $ips = explode(',', $userIP);
    $userIP = trim($ips[0]);
}
logMsg("Client IP: " . $userIP);

// 2. Técnica Honeypot
$honeypot = trim($_POST['company_website'] ?? '');
if (!empty($honeypot)) {
    logMsg("BLOCKED BY HONEYPOT: value = '" . $honeypot . "'");
    echo json_encode(array("success" => true, "message" => "Request processed successfully."));
    exit();
}

// 3. Verificación de Cloudflare Turnstile
$turnstileSecret = "0x4AAAAAAGHol444mJiJaOlonBxqp0vUL6o";
$turnstileResponse = $_POST['cf-turnstile-response'] ?? $_POST['g-recaptcha-response'] ?? '';

if (empty($turnstileResponse)) {
    logMsg("BLOCKED: Turnstile token empty");
    echo json_encode(array("success" => false, "message" => "Please complete the security check."));
    exit();
}

$verifyUrl = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
$verifyData = [
    'secret' => $turnstileSecret,
    'response' => $turnstileResponse,
    'remoteip' => $userIP
];

$ch = curl_init($verifyUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verifyData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$turnstileResult = curl_exec($ch);
$cfHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$cfError = curl_error($ch);
curl_close($ch);

logMsg("Turnstile API (HTTP $cfHttpCode): " . $turnstileResult . ($cfError ? " Err: $cfError" : ""));

$turnstileKeys = json_decode($turnstileResult, true);
if (!$turnstileKeys || empty($turnstileKeys["success"])) {
    logMsg("BLOCKED: Turnstile verification failed");
    echo json_encode(array("success" => false, "message" => "Security verification failed. Please try again."));
    exit();
}

// 4. Captura y sanitización de datos
$name = trim($_POST["name"] ?? "");
$lastName = trim($_POST["lastName"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$email = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($name === "" || $lastName === "" || $phone === "" || $email === "") {
    logMsg("BLOCKED: Required fields empty");
    echo json_encode(array("success" => false, "message" => "Please fill in all required fields."));
    exit();
}

// 5. Validación de formato de Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    logMsg("BLOCKED: Invalid email format: " . $email);
    echo json_encode(array("success" => false, "message" => "Please enter a valid email address."));
    exit();
}

// 6. Validación de Teléfono (al menos 10 dígitos numéricos)
$phoneDigits = preg_replace('/[^0-9]/', '', $phone);
if (strlen($phoneDigits) < 10 || strlen($phoneDigits) > 15) {
    logMsg("BLOCKED: Invalid phone digits length (" . strlen($phoneDigits) . "): " . $phone);
    echo json_encode(array("success" => false, "message" => "Please enter a valid phone number (at least 10 digits)."));
    exit();
}

// 7. Filtros anti-spam de contenido (Black Hole / Descarte Silencioso)
$allText = $name . ' ' . $lastName . ' ' . $message;
$hasLinks = preg_match('/(https?:\/\/|www\.|\.ru\b|\.top\b|\.xyz\b|\.cn\b|<a\b|\[url\b)/i', $allText);
$hasCyrillic = preg_match('/[\p{Cyrillic}\p{Han}]/u', $allText);

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

if ($hasLinks || $hasCyrillic || $hasSpamKeyword) {
    logMsg("BLOCKED BY CONTENT FILTER: links=" . ($hasLinks ? 1 : 0) . " cyrillic=" . ($hasCyrillic ? 1 : 0) . " keyword=" . ($hasSpamKeyword ? 1 : 0));
    echo json_encode(array("success" => true, "message" => "Thank you! Your request has been sent successfully."));
    exit();
}

// 8. Ejecución independiente de envíos (Google Sheets NO debe depender de mail())
logMsg("PASSES ALL CHECKS: Processing legitimate lead");

// 8a. Google Sheets
$gsResult = enviarAGoogleSheets($name, $lastName, $phone, $email, $message);
logMsg("Google Sheets Response: " . $gsResult);

// 8b. Envío de Correo
$to = "f3ccorp@gmail.com, ajconversionsseo@gmail.com";
$subject = "F3 Construction Corp - Contact Form";
$headers  = "From: F3 Construction <noreply@f3constructionny.com>\r\n";
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

$mailSent = @mail($to, $subject, $fullmessage, $headers, "-f noreply@f3constructionny.com");
logMsg("Mail sent result: " . ($mailSent ? "SUCCESS" : "FAILED"));

// 8c. Zapier (opcional)
enviarAZapier($name, $lastName, $phone, $email, $message);

echo json_encode(array("success" => true));

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
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 12);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    return "HTTP $code | $res" . ($err ? " | Err: $err" : "");
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    curl_close($ch);
}
?>
