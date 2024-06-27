<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once "../db/dbconnection.php";


require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or show an error message

    $logger = new Logger('Attempt to add availability.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Failed. No user was logged in.');

    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $date = $_POST["date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];

    // Get instructor ID from session
    $instructor_id = $_SESSION['user_id'];

    // Check if the selected date is in the past
    if (strtotime($date) < strtotime('today')) {
        // Redirect back with an error message

        $logger = new Logger('Attempt to add availability.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Failed. Cannot add availability for past dates. By: ' . $_SESSION['email']);

        $_SESSION['notification'] = "Cannot add availability for past dates.";
        header("Location: ../?page=availability_instructor");
        exit();
    }

    // Check if the end time is less than the start time
    if (strtotime($end_time) <= strtotime($start_time)) {
        // Redirect back with an error message

        $logger = new Logger('Attempt to add availability.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Failed. End time must be greater than start time. By: ' . $_SESSION['email']);

        $_SESSION['notification'] = "End time must be greater than start time.";
        header("Location: ../?page=availability_instructor");
        exit();
    }

    // Check if the availability already exists
    $sql_check = "SELECT * FROM availability_instructor WHERE Instructor_ID = :instructor_id 
                  AND avail_date = :date AND avail_start_time = :start_time AND avail_end_time = :end_time";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['instructor_id' => $instructor_id, 'date' => $date, 'start_time' => $start_time, 'end_time' => $end_time]);
    $existing_availability = $stmt_check->fetch();

    if ($existing_availability) {
        // Redirect back with an error message

        $logger = new Logger('Attempt to add availability.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Failed. Availability already exists for this date and time. By: ' . $_SESSION['email']);

        $_SESSION['notification'] = "Availability already exists for this date and time.";
        header("Location: ../?page=availability_instructor");
        exit();
    } else {
        // Insert data into the database
        $sql_insert = "INSERT INTO availability_instructor (Instructor_ID, avail_date, avail_start_time, avail_end_time) 
                       VALUES (:instructor_id, :date, :start_time, :end_time)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute(['instructor_id' => $instructor_id, 'date' => $date, 'start_time' => $start_time, 'end_time' => $end_time]);

        $logger = new Logger('Attempt to add availability.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Successful. By: ' . $_SESSION['email']);

        // Redirect back to the page with availability
        header("Location: ../?page=availability_instructor");
        exit();
    }
}
?>
