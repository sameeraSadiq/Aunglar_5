<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Get the data sent from Angular
$data = json_decode(file_get_contents("php://input"));

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["message" => "JSON decode error: " . json_last_error_msg()]);
    exit();
}

if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
    echo json_encode(["message" => "Missing required fields"]);
    exit();
}

$username = mysqli_real_escape_string($conn, $data->username);
$email = mysqli_real_escape_string($conn, $data->email);
$password = mysqli_real_escape_string($conn, $data->password);
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "message" => "User registered successfully",
        "user" => [
            "username" => $username,
            "email" => $email
        ]
    ]);
} else {
    echo json_encode(["message" => "Error: " . $conn->error]);
}

$conn->close();
?>