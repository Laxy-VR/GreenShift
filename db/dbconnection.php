<?php
$host = 'localhost';
$dbname = 'greenshift';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    // Display error message
    echo "Connection failed: " . $e->getMessage();
    die(); // Terminate script execution
}
?>
