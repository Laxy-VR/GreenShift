<?php

$instructorId = $_SESSION['user_id'];

$checkNotification = $pdo->prepare("SELECT lp.*, u.full_name as studentName FROM lessonplanning lp
         LEFT JOIN users u on lp.studentId = u.id
         WHERE instructorId = :instructorId AND canceledOn is not null AND adminPermission is null ");
$checkNotification->execute(array(
    ':instructorId' => $instructorId
));

$checkMyCancellation = $pdo->prepare("SELECT lp.*, u.full_name as studentName FROM lessonplanning lp
         LEFT JOIN users u on lp.studentId = u.id
         WHERE instructorId = :instructorId AND adminPermission = 'accepted'");
$checkMyCancellation->execute(array(
    ':instructorId' => $instructorId
));


?>

<div class="content">
    <div class="container mt-4">
        <h1>Student Cancellations</h1><br>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Student</th>
                <th scope="col">Date</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
                <th scope="col">Canceled on</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($checkNotification->rowCount() > 0) {
                while ($instructorNotification = $checkNotification->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $instructorNotification['studentName'] ?>  </td>
                        <td><?= $instructorNotification['date'] ?>  </td>
                        <td><?= date('g:ia', strtotime($instructorNotification['startTime'])); ?>  </td>
                        <td><?= date('g:ia', strtotime($instructorNotification['endTime'])) ?>  </td>
                        <td><?= $instructorNotification['canceledOn'] ?>  </td>

                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
    </div>


    <div class="container mt-4">
        <h1>My cancellations</h1><br>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
                <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($checkMyCancellation->rowCount() > 0) {
                while ($instructorCancellation = $checkMyCancellation->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $instructorCancellation['date'] ?>  </td>
                        <td><?= date('g:ia', strtotime($instructorCancellation['startTime'])); ?>  </td>
                        <td><?= date('g:ia', strtotime($instructorCancellation['endTime'])) ?>  </td>
                        <td style="color: green"><?= $instructorCancellation['adminPermission'] ?></td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
    </div>
</div>

