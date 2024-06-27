<?php
$checkNotifications = $pdo->prepare("SELECT lp.*, u.full_name as instructorName FROM lessonplanning lp
         LEFT JOIN users u on lp.instructorId = u.id
         WHERE adminPermission = 'pending'");
$checkNotifications->execute();
?>

<div class="content">
    <div class="container mt-4">
        <h1>Instructor cancellations</h1><br>
        <?php
        if (isset($_SESSION['notification2'])) {
            echo '<p style="color:darkgreen;">' . $_SESSION['notification2'] . '</p>';
            unset($_SESSION['notification2']);
        }
        ?>
        <table class="table table-striped">
            <form method="post" action="PHP/cancelLesson.php">
                <thead>
                <tr>
                    <th scope="col">Instructor</th>
                    <th scope="col">Date Lesson</th>
                    <th scope="col">From</th>
                    <th scope="col">To</th>
                    <th scope="col">Accept cancellation</th>
                    <th scope="col">Reject cancellation</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($checkNotifications->rowCount() > 0) {
                    while ($allNotifications = $checkNotifications->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $allNotifications['instructorName'] ?>  </td>
                            <td><?= $allNotifications['date'] ?>  </td>
                            <td><?= date('g:ia', strtotime($allNotifications['startTime'])); ?>  </td>
                            <td><?= date('g:ia', strtotime($allNotifications['endTime'])) ?>  </td>
                            <td>
                                <button class="btn btn-success" name="acceptCancellation"
                                        onclick="window.location.href='php/cancelLesson.php?lessonPlanningId=<?= $allNotifications["lessonPlanningId"] ?>'">
                                    Accept cancellation
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-danger" name="rejectCancellation"
                                        onclick="window.location.href='php/cancelLesson.php?lessonPlanningId=<?= $allNotifications["lessonPlanningId"] ?>'">
                                    Reject cancellation
                                </button>
                                <input type="hidden" name="lessonPlanningId"
                                       value="<?= $allNotifications['lessonPlanningId'] ?>">
                            </td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </form>
        </table>
    </div>
</div>
