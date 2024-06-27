<?php
include "../db/dbconnection.php";
include 'mailAction.php';
session_start();

require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

$email = $_SESSION['email2FA'];


$token = bin2hex(random_bytes(16));
$hashedToken = hash("sha1", $token);
$token_expiry = date("Y-m-d H:i:s", time() + 60 * 15);

$updateTokens = $pdo->prepare('UPDATE users SET token = :token, tokenExpiresAt = :tokenExpiresAt WHERE email = :email');
$updateTokens->execute(array(
    ':token' => $hashedToken,
    ':tokenExpiresAt' => $token_expiry,
    ':email' => $email
));

$verificationCode = mt_rand(100000, 999999);
$_SESSION['verificationCode'] = $verificationCode;

$mail = require __DIR__ . "/mailAction.php";

$mail->SMTPDebug = 0;

$mail->setFrom('noreply@greenshift.com', 'GreenShift');
$mail->addAddress($email);
$mail->Subject = 'Confirm Login';
$mail->Body = <<<END
   <div class="container">
    <h1>Dear user,</h1>
    <p>Click the button below to submity your login code:</p>
    <p>Your unique code is: $verificationCode</p>
    <a href="http://localhost/greenshift2/index.php?page=confirmLogin&token=$hashedToken" class="button">Confirm login</a>
    <p>If the button doesn't work, copy and paste the following link into your browser:</p>
    <p>http://localhost/greenshift2/index.php?page=confirmLogin&token=$hashedToken</p>
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
exit();



