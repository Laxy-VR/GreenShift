<?php
include_once "../db/dbconnection.php";
require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

session_start();
$verificationCode = $_POST['verificationCode'];
$token = $_POST['token'];
$currentTime = date('Y-m-d H:i:s', time());


if (isset($_SESSION['verificationCode'])) {
    $checkTokenValidity = $pdo->prepare("SELECT * FROM users WHERE token = :token");
    $checkTokenValidity->execute(array(':token' => $token));
    $tokenData = $checkTokenValidity->fetch(PDO::FETCH_ASSOC);

    if ($checkTokenValidity->rowCount() < 0) {
        $logger = new Logger('Login Attempt.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Failed. Token expired or not found. By: ' . $tokenData['email']);

        $_SESSION['notification'] = 'Token not valid or has expired, please send another login request.';
        header("Location: ../index.php?page=resetPassword");
        exit();
    }

    if ($tokenData['tokenExpiresAt'] > $currentTime) {
        if ($verificationCode == $_SESSION['verificationCode']) {

            $_SESSION['user_id'] = $tokenData['id'];
            $_SESSION['email'] = $tokenData['email'];
            $_SESSION['role'] = $tokenData['role'];

            $updateUserData = $pdo->prepare("UPDATE users SET token = null,tokenExpiresAt = null");
            $updateUserData->execute();

            $logger = new Logger('Login Attempt.');
            $logger->pushHandler(new LoggerGreenshift($pdo));
            $logger->info('Successful. By: ' . $tokenData['email']);

            header('Location: ../?page=home');
            exit();

        } else {
            $logger = new Logger('Login Attempt.');
            $logger->pushHandler(new LoggerGreenshift($pdo));
            $logger->info('Failed. Incorrect verification code. By: ' . $tokenData['email']);

            $_SESSION['notification'] = 'Verification code is incorrect.';
            header("Location: ../index.php?page=confirmLogin&token={$token}");
            exit();
        }

    } else {
        $logger = new Logger('Login Attempt.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Failed. Token expired or not found. By: ' . $tokenData['email']);

        $_SESSION['notification'] = 'Token is not valid or has expired, please send another password login request.';
        header("Location: ../index.php?page=confirmLogin&token={$token}");
        exit();
    }
} else {
    $_SESSION['notification'] = 'Session verification code was not set.';
    header("Location: ../index.php?page=login");
    exit();
}