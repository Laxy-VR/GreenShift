<?php
$studentId = $_SESSION['user_id'];
$currentDate = date("Y-m-d");

$stmtLessonSchedule = $pdo->prepare("SELECT * FROM lessonplanning WHERE studentId = :studentId AND canceledOn IS NULL AND date >= :currentDate ");
$stmtLessonSchedule->execute(array(
    ':studentId' => $studentId,
    ':currentDate' => $currentDate
));

$getInstructor = $pdo->prepare("SELECT l.instructorId,u.full_name AS instructorName FROM lessonPlanning AS l LEFT JOIN users AS u ON l.instructorId = u.id  WHERE studentId = :studentId");
$getInstructor->execute(array(':studentId' => $studentId));
$fetchInstructorName = $getInstructor->fetch(PDO::FETCH_ASSOC);

$instructorAvailabilty = $pdo->prepare("SELECT * FROM availability_instructor WHERE instructor_ID = :instructor_ID ORDER BY avail_date");
$instructorAvailabilty->execute(array(
    ':instructor_ID' => $fetchInstructorName['instructorId']
));
?>

<div class="content">
    <h1>Schedule your lesson</h1><Br>
    <?php
    if (isset($_SESSION['notification3'])) {
        echo '<p style="color: red"  class="notification notification-error">' . $_SESSION['notification3'] . '</p>';
        unset($_SESSION['notification3']);
    } ?>
    <p>Your instructor is available on these times.</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Start time</th>
            <th>End time</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($fetchInstructorAvailability = $instructorAvailabilty->fetch(PDO::FETCH_ASSOC)) {
            $startTimeInstructor = date('H:i', strtotime($fetchInstructorAvailability['avail_start_time']));
            $endTimeInstructor = date('H:i', strtotime($fetchInstructorAvailability['avail_end_time']));
            $dateInstructor = date('d/m/Y', strtotime($fetchInstructorAvailability['avail_date']));
            ?>
            <tr>
                <td><?= $dateInstructor ?></td>
                <td><?= $startTimeInstructor ?></td>
                <td><?= $endTimeInstructor ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<p style="color: red" class="notification notification-error">' . $_SESSION['notification'] . '</p>';
        unset($_SESSION['notification']);
    } ?>
    <p>These dates and times are <b>NOT</b> available:</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Start time</th>
            <th>End time</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($lessonSchedule = $stmtLessonSchedule->fetch(PDO::FETCH_ASSOC)) {
            $startTime = date('H:i', strtotime($lessonSchedule['startTime']));
            $endTime = date('H:i', strtotime($lessonSchedule['endTime']));
            $date = date('d/m/Y', strtotime($lessonSchedule['date']));
            ?>
            <tr>
                <td><?= $date ?></td>
                <td><?= $startTime ?></td>
                <td><?= $endTime ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
    <p class="instructor">Your instructor is:<b> <?= $fetchInstructorName['instructorName'] ?></b></p>
    <form method="post" action="PHP/scheduleLesson.php">
        <p>Choose your date and start time</p><br>
        <label for="lesson-date">Date:</label>
        <input type="date" class="form-control" id="lesson-date" placeholder="Date" name="date"><br>
        <label for="lesson-startTime">Start time:</label>
        <input type="time" class="form-control" id="lesson-startTime" placeholder="Time" name="startTime">
        <input type="hidden" name="instructorId" value="<?= $fetchInstructorName['instructorId'] ?>">
        <input type="hidden" name="studentId" value="<?= $studentId ?>">
        <input type="submit" value="Schedule Lesson" class="btn btn-success">
    </form>
</div>
