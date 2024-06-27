<?php
session_start();
include "../db/dbconnection.php";
require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

$date = $_POST['date'];
$startTimePost = $_POST['startTime'];
$instructorId = $_POST['instructorId'];
$studentId = $_POST['studentId'];
$currentDate = date("Y-m-d");
$currentTime = date("H:i");

$endTime = date('H:i', strtotime('+1 hour', strtotime($startTimePost)));
$startTime = date('H:i', strtotime($startTimePost));

if ($date < date('Y-m-d') || ($date == date('Y-m-d') && $startTimePost < date("H:i"))) {

    $logger = new Logger('Plan lesson attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Failed. Cannot plan lesson in past. By: ' . $_SESSION['email']);

    $_SESSION['notification'] = 'You cant plan a lesson in the past.';
    header("Location: ../index.php?page=scheduleLesson");
    exit();
}

$getInstructorAvailability = $pdo->prepare("SELECT * FROM availability_instructor WHERE instructor_ID = :instructor_ID");
$getInstructorAvailability->execute(array(
    'instructor_ID' => $instructorId
));
$instructorAvailability = $getInstructorAvailability->fetchAll(PDO::FETCH_ASSOC);

$instructorDates = [];

foreach ($instructorAvailability as $value) {
    $instructorDates[] = $value['avail_date'];
}

if (!in_array($date, $instructorDates)) {

    $logger = new Logger('Plan lesson attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Failed. Instructor not available on this date. By: ' . $_SESSION['email']);

    $_SESSION['notification3'] = 'Your instructor is not available on this date.';
    header("Location: ../index.php?page=scheduleLesson");
    exit();
}

$checkInstructorTime = $pdo->prepare("SELECT * FROM availability_instructor WHERE avail_date = :avail_date");
$checkInstructorTime->execute(array(
    ':avail_date' => $date
));
$instructorTime = $checkInstructorTime->fetch(PDO::FETCH_ASSOC);

$endtimeInstructor = strtotime($instructorTime['avail_end_time']) - 3600;
$endtimeInstructorFinal = date("H:i", $endtimeInstructor);

if (!($startTimePost >= $instructorTime['avail_start_time'] && $startTimePost <= $endtimeInstructorFinal)) {
    $logger = new Logger('Plan lesson attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Failed. Instructor not available on this time. By: ' . $_SESSION['email']);

    $_SESSION['notification3'] = 'Your instructor is not available on this time.';
    header("Location: ../index.php?page=scheduleLesson");
    exit();
}


$selectPlanning = $pdo->prepare("SELECT * FROM lessonplanning WHERE instructorId = :instructorId");
$selectPlanning->execute(array(
    'instructorId' => $instructorId
));

$notAvailable = false;

while ($checkPlanning = $selectPlanning->fetch(PDO::FETCH_ASSOC)) {
    $existingDate = $checkPlanning['date'];
    $existingStartTime = $checkPlanning['startTime'];
    $existingEndTime = $checkPlanning['endTime'];

    if ($existingDate == $date) {
        if (($startTime >= $existingStartTime && $startTime < $existingEndTime) ||
            ($endTime > $existingStartTime && $endTime <= $existingEndTime) ||
            ($startTime <= $existingStartTime && $endTime >= $existingEndTime)) {
            $notAvailable = true;
            break;
        }
    }
}

if ($notAvailable) {
    $_SESSION['notification'] = 'Lesson time overlaps with an existing lesson.';

    $logger = new Logger('Plan lesson attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Failed. Lesson overlaps with existing lesson. By: ' . $_SESSION['email']);

    header("Location: ../index.php?page=scheduleLesson");
    exit();
} else {
    $insertPlanning = $pdo->prepare("INSERT INTO lessonplanning (date, startTime, endTime, studentId, instructorId)
                                 VALUES (:date, :startTime, :endTime, :studentId, :instructorId)");
    $insertPlanning->execute(array(
        ':date' => $date,
        ':startTime' => $startTime,
        ':endTime' => $endTime,
        ':studentId' => $studentId,
        ':instructorId' => $instructorId
    ));

    $logger = new Logger('Plan lesson attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Successful. By: ' . $_SESSION['email']);

    $_SESSION['notification2'] = 'Lesson planned successfully.';
    header("Location: ../index.php?page=scheduleStudent");
    exit();
}

?>
