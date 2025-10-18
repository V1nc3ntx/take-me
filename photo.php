<?php
// Define the directory to save photos
$directory = "./photos";

// Check if the directory exists, if not, create it
if (!is_dir($directory)) {
    mkdir($directory, 0777, true); // Create directory with full permissions
}

// Check for the POST request with JSON content
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data["image"])) {
    // Extract the base64 data from the data URL
    $imageData = $data["image"];
    $imageData = str_replace("data:image/png;base64,", "", $imageData);
    $imageData = base64_decode($imageData);

    // Define a unique filename
    $filename = "photo_" . time() . ".png";

    // Save the file to the server
    file_put_contents("$directory/$filename", $imageData);

    // Log information
    logInfo($filename);

    // Return a success response
    echo json_encode(["success" => true, "filename" => $filename]);
} else {
    echo json_encode(["success" => false, "error" => "No image data provided"]);
}

// Function to log information
function logInfo($filename) {
    // Get the user's IP address
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Get the user's user-agent (device name)
    $deviceName = $_SERVER['HTTP_USER_AGENT'];

    // Get the current timestamp
    $timestamp = date('Y-m-d H:i:s');

    // Placeholder for location (you could implement geolocation here)
    $location = "unknown"; // This could be replaced with actual geolocation data

    // Prepare the log entry
    $logEntry = "[$timestamp] File: $filename, IP: $ipAddress, Device: $deviceName, Location: $location" . PHP_EOL;

    // Append the log entry to log.txt
    file_put_contents("log.txt", $logEntry, FILE_APPEND | LOCK_EX);
}
?>
