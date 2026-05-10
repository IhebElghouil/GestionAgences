<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// MySQL connection (update credentials)
$mysqli = new mysqli("localhost", "root", "", "your_database");
if ($mysqli->connect_errno) {
    die("Failed to connect: " . $mysqli->connect_error);
}

// Handle uploaded Excel file
if (isset($_FILES['excel_file']['tmp_name'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    $inserted = 0;
    for ($i = 1; $i < count($data); $i++) {
        $row = $data[$i];
        $name = $mysqli->real_escape_string($row[0]);
        $email = $mysqli->real_escape_string($row[1]);

        if (!empty($name) && !empty($email)) {
            $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
            $mysqli->query($sql);
            $inserted++;
        }
    }

    echo "$inserted rows imported successfully.";
} else {
    echo "No file uploaded.";
}
?>