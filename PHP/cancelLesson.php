<?php
include "../db/dbconnection.php";
require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

session_start();

// current date
$canceledOn = date('Y-m-d H:i:s');


if ($_SESSION['role'] == 'admin') {

    if (isset($_POST['acceptCancellation'])) {
        $lessonPlanningId = $_POST['lessonPlanningId'];


        $getInstructorId = $pdo->prepare("SELECT instructorId FROM lessonplanning WHERE lessonPlanningId = :lessonPlanningId");
        $getInstructorId->execute(array(':lessonPlanningId' => $lessonPlanningId));
        $fetchInstructorId = $getInstructorId->fetch(PDO::FETCH_ASSOC);

        $cancelInstructor = $pdo->prepare("UPDATE lessonplanning SET adminPermission = 'accepted', canceledOn = :canceledOn, canceledBy = :canceledBy WHERE lessonPlanningId = :lessonPlanningId  ");
        $cancelInstructor->execute(array(
            ':lessonPlanningId' => $lessonPlanningId,
            ':canceledOn' => $canceledOn,
            ':canceledBy' => $fetchInstructorId['instructorId']
        ));

        $logger = new Logger('Attempt accept lesson cancellation.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Successful.By: ' . $_SESSION['email']);

        $_SESSION['notification2'] = 'Request updated.';
        header("Location: ../index.php?page=adminNotification");
        exit();

    } elseif (isset($_POST['rejectCancellation'])) {

        $lessonPlanningId = $_POST['lessonPlanningId'];

        $cancelInstructor = $pdo->prepare("UPDATE lessonplanning SET adminPermission = 'rejected', canceledBy = :canceledBy WHERE lessonPlanningId = :lessonPlanningId  ");
        $cancelInstructor->execute(array(
            ':lessonPlanningId' => $lessonPlanningId
        ));

        $logger = new Logger('Attempt reject lesson cancellation.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Successful.By: ' . $_SESSION['email']);

        $_SESSION['notification2'] = 'Request updated.';
        header("Location: ../index.php?page=adminNotification");
        exit();
    }
} elseif ($_SESSION['role'] == 'instructor') {
    $lessonPlanningId = $_GET['lessonPlanningId'];


    $cancelInstructor = $pdo->prepare("UPDATE lessonplanning SET adminPermission = 'pending' WHERE lessonPlanningId = :lessonPlanningId  ");
    $cancelInstructor->execute(array(
        ':lessonPlanningId' => $lessonPlanningId,

    ));

    $logger = new Logger('Attempt to send cancellation request.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Successful.By: ' . $_SESSION['email']);

    $_SESSION['notification2'] = 'Notification send successfully.';
    header("Location: ../index.php?page=instructorSchedule");
    exit();

} elseif ($_SESSION['role'] == 'student') {
    $lessonPlanningId = $_GET['lessonPlanningId'];
    $userId = $_SESSION['user_id'];

    $checkLessonDate = $pdo->prepare("SELECT * FROM lessonplanning WHERE lessonPlanningId = :lessonPlanningId");
    $checkLessonDate->execute(array(':lessonPlanningId' => $lessonPlanningId));
    $fetchLessonDate = $checkLessonDate->fetch(PDO::FETCH_ASSOC);

    $currentDatetime = new DateTime();
    $postDatetime = new DateTime($fetchLessonDate['date'] . ' ' . $fetchLessonDate['startTime']);

    $diff = $currentDatetime->diff($postDatetime);

    $intervalInSeconds = $diff->days * 24 * 3600 + $diff->h * 3600 + $diff->i * 60 + $diff->s;

    if ($intervalInSeconds < 24 * 3600) {

        $cancelLesson = $pdo->prepare("UPDATE lessonplanning SET canceledOn = :canceledOn, canceledBy = :canceledBy, sendInvoice = 'YES' WHERE lessonPlanningId = :lessonPlanningId");
        $cancelLesson->execute(array(
            ':canceledOn' => $canceledOn,
            ':lessonPlanningId' => $lessonPlanningId,
            ':canceledBy' => $userId
        ));

        $logger = new Logger('Attempt cancel lesson.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Successful (Less than 24h notice).By: ' . $_SESSION['email']);

        $_SESSION['notification'] = 'Your cancellation with less than 24 hours notice will result in the full price being charged.';
        header("Location: ../index.php?page=scheduleStudent");
        exit();
    } else {

        $cancelLesson = $pdo->prepare("UPDATE lessonplanning SET canceledOn = :canceledOn, canceledBy = :canceledBy, sendInvoice = 'NO' WHERE lessonPlanningId = :lessonPlanningId");
        $cancelLesson->execute(array(
            ':canceledOn' => $canceledOn,
            ':lessonPlanningId' => $lessonPlanningId,
            ':canceledBy' => $userId
        ));

        $logger = new Logger('Attempt cancel lesson.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Successful.By: ' . $_SESSION['email']);

        $_SESSION['notification2'] = 'Lesson canceled successfully.';
        header("Location: ../index.php?page=scheduleStudent");
        exit();
    }
}


?>
