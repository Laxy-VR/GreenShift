<?php
session_start();

include "../db/dbconnection.php";
require_once('../class/Instructor.class.php');

require '../vendor/autoload.php';
require_once('logger.php');

use Monolog\Logger;

$instructor = new Instructor($pdo);

if (isset($_POST['deleteInstructor'])) {

    $logger = new Logger('Delete instructor attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Successful. By: .' . $_SESSION['email']);

    $instructor->deleteData('users', 'deletedOn', date('Y-m-d H:i:s'), 'id', $_POST['deleteInstructor'], 'manageInstructor');

} elseif (isset($_POST['editInstructor'])) {

    $userid = $_POST['editInstructor'];
    header("Location: ../index.php?page=editInstructor&userid=$userid");
    exit();

} elseif (isset($_POST['updateInstructor'])) {

    $data = $_POST;
    $sql = "SELECT email FROM users WHERE email = :email AND id != :userId AND deletedOn is null";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':userId', $data['userid']);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        $logger = new Logger('Edit instructor attempt.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Failed. Email exists By: .' . $_SESSION['email']);

        $_SESSION['notification'] = 'Email not availible.';
        header("Location: ../index.php?page=editInstructor&userid={$data['userid']}");
        exit();
    }
    $logger = new Logger('Edit instructor attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Successful. By: .' . $_SESSION['email']);
    $instructor->editInstructor($_POST);

} elseif (isset($_POST['addInstructor'])) {

    $data = $_POST;
    $sql = "SELECT email FROM users WHERE email = :email AND deletedOn is null";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $data['email']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $logger = new Logger('add instructor attempt.');
        $logger->pushHandler(new LoggerGreenshift($pdo));
        $logger->info('Failed. Email exists By: .' . $_SESSION['email']);

        $_SESSION['notification'] = 'Email not availible.';
        header('location: ../index.php?page=addInstructor');
        exit();
    }


    unset($data['addInstructor']);
    $password_hash = password_hash($_POST['password_hash'], PASSWORD_DEFAULT);
    unset($data['password_hash']);

    $extraData = array(
        'role' => 'instructor',

        'password_hash' => $password_hash
    );

    $data = array_merge($data, $extraData);

    $logger = new Logger('Add instructor attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Successful. By: .' . $_SESSION['email']);

    $instructor->insertData('users', $data, 'manageInstructor');
} elseif (isset($_POST['chooseInstructor'])) {

    $data = $_POST;
    unset($data['chooseInstructor']);


    $sql = "SELECT id FROM users WHERE full_name = :full_name";
    $fetchInstructor = $pdo->prepare($sql);
    $fetchInstructor->execute(array(':full_name' => $data['instructorId']));
    $instructorId = $fetchInstructor->fetch(PDO::FETCH_ASSOC);
    $data['instructorId'] = $instructorId['id'];


    $logger = new Logger('Choose instructor attempt.');
    $logger->pushHandler(new LoggerGreenshift($pdo));
    $logger->info('Successful. By: .' . $_SESSION['email']);
    $instructor->insertData('studentInstructor', $data, 'chooseInstructor');

}
?>
