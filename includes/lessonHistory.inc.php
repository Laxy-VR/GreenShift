<?php
$studentId = $_SESSION['user_id'];

$fetchStudentLesson = $pdo->prepare("SELECT lp.*, u.full_name as instructorName FROM lessonplanning lp 
         LEFT JOIN users u on lp.instructorId = u.id
         WHERE studentId = :studentId AND canceledOn is null AND date < :currentDate");
$fetchStudentLesson->execute(array(
    ':studentId' => $studentId,
    ':currentDate' => date('Y-m-d')
));

$totalLessons = $fetchStudentLesson->rowCount();
?>

<div class="content">
    <div class="container mt-4">
        <h1>My lesson history</h1><br>
        <h2 style="float: right;">Total lessons: <?= $totalLessons ?></h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Instructor</th>
                <th scope="col">Date</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($fetchStudentLesson->rowCount() > 0) {
                while ($studentHistory = $fetchStudentLesson->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $studentHistory['instructorName'] ?>  </td>
                        <td><?= $studentHistory['date'] ?>  </td>
                        <td><?= date('g:ia', strtotime($studentHistory['startTime'])); ?>  </td>
                        <td><?= date('g:ia', strtotime($studentHistory['endTime'])) ?>  </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
    </div>
</div>

