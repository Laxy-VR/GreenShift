<?php
include "../db/dbconnection.php";
include 'mailAction.php';
session_start();
require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

$email = $_POST['email'];
$token = bin2hex(random_bytes(16));
$hashedToken = hash("sha1", $token);
$token_expiry = date("Y-m-d H:i:s", time() + 60 * 15);

$updateTokens = $pdo->prepare('UPDATE users SET token = :token, tokenExpiresAt = :tokenExpiresAt WHERE email = :email');
$updateTokens->execute(array(
    ':token' => $hashedToken,
    ':tokenExpiresAt' => $token_expiry,
    ':email' => $email
));

$mail = require __DIR__ . "/mailAction.php";

$mail->setFrom('noreply@greenshift.com', 'GreenShift');
$mail->addAddress($email);
$mail->Subject = 'Reset Password';
$mail->Body = <<<END
   <div class="container">
    <h1>Dear user,</h1>
    <p>Click the button below to reset your password:</p>
    <a href="http://localhost/greenshift2/index.php?page=resetPassword&token=$hashedToken" class="button">Reset Password</a>
    <p>If the button doesn't work, copy and paste the following link into your browser:</p>
    <p>http://localhost/greenshift2/index.php?page=resetPassword&token=$hashedToken</p>
    <p>Best regards,<br>GreenShift</p>
</div>

END;
try {
    $mail->send();

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: $mail->ErrorInfo";
}
$logger = new Logger('Send 2FA email attempt.');
$logger->pushHandler(new LoggerGreenshift($pdo));
$logger->info('Successful. Sent to: ' . $email);

header('location: ../index.php?page=checkEmail');
