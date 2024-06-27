<?php
// Start session
session_start();

require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

// Function to increment login attempts
function incrementLoginAttempts()
{
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
    } else {
        $_SESSION['login_attempts']++;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the maximum login attempts have been reached
    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
        // Check if one hour has passed since the last failed attempt
        if (isset($_SESSION['last_failed_attempt_time']) && time() - $_SESSION['last_failed_attempt_time'] < 1) {
            // Display error message and exit
            header("Location: ../index.php?page=login&error=login_locked");
            exit;
        } else {
            // Reset login attempts
            $_SESSION['login_attempts'] = 1;
        }
    }
    include_once "../db/dbconnection.php";

    $email = $_POST['email'];
    $password = $_POST['password'];


    try {
        // Prepare SQL statement to retrieve user with given email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and verify password
        if ($user && password_verify($password, $user['password_hash']) && in_array($user['role'], ['student', 'instructor', 'manager', 'admin'])) {
            // Authentication successful, set session variables
            $_SESSION['email2FA'] = $email;
            $_SESSION['password2FA'] = $password;
            header('Location: sendMailAction2FA.php');
            exit;
        } else {
            $logger = new Logger('Login Attempt.');
            $logger->pushHandler(new LoggerGreenshift($pdo));
            $logger->info('Failed. Incorrect login details. By: ' . $email);

            // Increment login attempts
            incrementLoginAttempts();

            // Store the time of the last failed attempt
            $_SESSION['last_failed_attempt_time'] = time();

            // Authentication failed, redirect back to login page with error message
            header("Location: ../index.php?page=login&error=login_failed");
            exit;
        }
    } catch (PDOException $e) {
        // Error message
        echo "Error: " . $e->getMessage();
    }
    // Close the connection
    $pdo = null;
}
?>
