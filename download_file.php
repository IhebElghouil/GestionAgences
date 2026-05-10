<?php
session_start();

if (!isset($_SESSION['congidGA'])) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$filename = $_GET['file'] ?? '';
$type = $_GET['type'] ?? '';

if (empty($filename)) {
    header('HTTP/1.0 400 Bad Request');
    exit;
}

$uploadDir = 'uploads/';
$filepath = $uploadDir . $filename;

if (!file_exists($filepath)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

// Set appropriate headers for download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

readfile($filepath);
exit;
?>