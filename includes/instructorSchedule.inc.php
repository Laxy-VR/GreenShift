<?php
$instructorId = $_SESSION['user_id'];


    $fetchInstructorPlanning = $pdo->prepare("
        SELECT lp.*, u.full_name AS studentName
        FROM lessonplanning lp 
        LEFT JOIN users u ON lp.studentId = u.id
        WHERE instructorId = :instructorId 
        AND canceledOn IS NULL 
        AND (adminPermission IS NULL OR adminPermission = 'pending' OR adminPermission = 'rejected')
    ");
    $fetchInstructorPlanning->execute(array(
        ':instructorId' => $instructorId
    ));


?>


<div class="content">
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
                <th scope="col">Student</th>
                <th scope="col">Date</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
                <th scope="col">Cancel status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($fetchInstructorPlanning->rowCount() > 0) {
                while ($instructorSchedule = $fetchInstructorPlanning->fetch(PDO::FETCH_ASSOC)) {


                    ?>
                    <tr>
                    <td><?= $instructorSchedule['studentName'] ?>  </td>
                    <td><?= $instructorSchedule['date'] ?>  </td>
                    <td><?= date('g:ia', strtotime($instructorSchedule['startTime'])); ?>  </td>
                    <td><?= date('g:ia', strtotime($instructorSchedule['endTime'])) ?>  </td>
                    <?php if ($instructorSchedule['adminPermission'] == null) { ?>
                        <td>
                            <button class="btn btn-warning" name="cancelLessonAdmin"
                                    onclick="if(confirm('Are you sure you want to cancel this lesson?'))window.location.href='php/cancelLesson.php?lessonPlanningId=<?= $instructorSchedule["lessonPlanningId"] ?>'">
                                Send notification to admin
                            </button>
                        </td>
                    <?php } else { ?>
                        <?php if ($instructorSchedule['adminPermission'] === 'pending'): ?>
                            <td style="color: orange;"><?= $instructorSchedule['adminPermission'] ?></td>
                        <?php elseif ($instructorSchedule['adminPermission'] === 'accepted'): ?>
                            <td style="color: green;"><?= $instructorSchedule['adminPermission'] ?></td>
                        <?php elseif ($instructorSchedule['adminPermission'] === 'rejected'): ?>
                            <td style="color: red;"><?= $instructorSchedule['adminPermission'] ?></td>
                        <?php else: ?>
                            <td><?= $instructorSchedule['adminPermission'] ?></td>
                        <?php endif; ?>
                        </tr>

                        </tr>
                    <?php }
                }
            } ?>
            </tbody>
        </table>
    </div>
</div>
