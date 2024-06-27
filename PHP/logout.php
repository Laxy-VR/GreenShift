<?php

include "../db/dbconnection.php";
require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

session_start();

$logger = new Logger('Logout Attempt.');
$logger->pushHandler(new LoggerGreenshift($pdo));
$logger->info('Successfull. By: ' . $_SESSION['email']);

session_destroy();
session_unset();
header('location: ../index.php?page=login');
?>
