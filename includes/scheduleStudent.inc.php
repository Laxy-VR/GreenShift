<?php
$userid = $_SESSION['user_id'];

$currentDate = date('Y-m-d');

$sql = "SELECT l.*, u.full_name AS instructorName
        FROM lessonplanning AS l 
        LEFT JOIN users AS u ON l.instructorId = u.id 
        WHERE l.studentId = :studentId AND l.canceledOn is null AND date >= :currentDate ";
$fetchStudentSchedule = $pdo->prepare($sql);
$fetchStudentSchedule->execute(array(':studentId' => $userid, ':currentDate' => $currentDate));
?>

<div class="content">
    <button class="btn btn-success custom-button" onclick="window.location.href='index.php?page=scheduleLesson'">
        Plan lesson
    </button>
    <div class="container mt-4">
        <h1>My schedule</h1><br>
        <?php
        if (isset($_SESSION['notification'])) {
            echo '<p style="color:red;">' . $_SESSION['notification'] . '</p>';
            unset($_SESSION['notification']);
        }
        if (isset($_SESSION['notification2'])) {
            echo '<p style="color:darkgreen;">' . $_SESSION['notification2'] . '</p>';
            unset($_SESSION['notification2']);
        }
        ?>
        <table class="table table-striped">

            <thead>
            <tr>
                <th scope="col">Instructor</th>
                <th scope="col">Date</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
                <th scope="col">Cancel</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($fetchStudentSchedule->rowCount() > 0) {
                while ($studentSchedule = $fetchStudentSchedule->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $studentSchedule['instructorName'] ?>  </td>
                        <td><?= $studentSchedule['date'] ?>  </td>
                        <td><?= date('g:ia', strtotime($studentSchedule['startTime'])); ?>  </td>
                        <td><?= date('g:ia', strtotime($studentSchedule['endTime'])) ?>  </td>
                        <td>

                            <button class="btn btn-danger" name="cancelLessonStudent"
                                    onclick="if(confirm('Are you sure you want to cancel this lesson? (Note: If a lesson is canceled with less than 24 hours notice, the full price is still applicable.)'))window.location.href='php/cancelLesson.php?lessonPlanningId=<?= $studentSchedule["lessonPlanningId"] ?>'">
                                Cancel lesson
                            </button>

                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
    </div>
</div>
