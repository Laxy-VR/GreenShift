<?php
include "../db/dbconnection.php";
session_start();

require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

$token = $_POST['token'];
$password = $_POST['password'];
$currentTime = date('Y-m-d H:i:s', time());

$password_hash = password_hash($password, PASSWORD_DEFAULT);


$checkTokenValidity = $pdo->prepare("SELECT * FROM users WHERE token = :token");
$checkTokenValidity->execute(array(':token' => $token));
$tokenData = $checkTokenValidity->fetch(PDO::FETCH_ASSOC);

if ($checkTokenValidity->rowCount() < 0) {

    $logger = new Logger('Reset password attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Failed. Token not found or has expired. By: ' . $tokenData['email']);

    $_SESSION['notification'] = 'Token not found or has expired, please send another reset password request.';
    header("Location: ../index.php?page=resetPassword");
    exit();
}

if ($tokenData['tokenExpiresAt'] > $currentTime) {

    $updateUserData = $pdo->prepare("UPDATE users SET password_hash = :password,token = null,tokenExpiresAt = null");
    $updateUserData->execute(array(':password' => $password_hash));

    $logger = new Logger('Reset password attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Successful. By: ' . $tokenData['email']);

    $_SESSION['notification2'] = 'Password resetted successfully.';
    header("Location: ../index.php?page=login");
    exit();

} else {

    $logger = new Logger('Reset password attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Failed. Token not found or has expired. By: ' . $tokenData['email']);

    $_SESSION['notification'] = 'Token not found or has expired, please send another reset password request.';
    header("Location: ../index.php?page=resetPassword&token={$token}");
    exit();
}
