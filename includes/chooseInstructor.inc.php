<?php
$userid = $_SESSION['user_id'];

$fetchInstructors = $pdo->prepare("SELECT full_name FROM users WHERE role = 'instructor' AND deletedOn is null ");
$fetchInstructors->execute();

$checkStudent = $pdo->prepare("SELECT * FROM studentinstructor WHERE studentId = :studentId");
$checkStudent->execute(array(
        ':studentId' => $userid
));

if($checkStudent->rowCount() == 0){
?>

<div class="content">
    <h1>Choose Instructor</h1>
    <?php
    if (isset($_SESSION['alreadyChose'])) {
        echo '<p style = "color:red;">' . $_SESSION['alreadyChose'] . '</p>';
        unset($_SESSION['alreadyChose']);
    }
    ?>
    <form method="post" action="PHP/manageInstructorAction.php">
        <label>Choose an instructor:</label>
        <select name="instructorId">
            <?php
            while ($fetchedInstructors = $fetchInstructors->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $fetchedInstructors['full_name'] . '">' . $fetchedInstructors['full_name'] . '</option>';
            } ?>
        </select>
        <input type="hidden" name="studentId" value="<?= $userid ?>">
        <input type="submit" name="chooseInstructor" value="Choose instructor" class="btn btn-success">
    </form>
</div>

<?php }else{?>
<div class="content">
    <h1>You have already chose an instructor.</h1>
    <p>Conctact the service desk if u want to change your instructor.</p>
</div>

<?php } ?>
