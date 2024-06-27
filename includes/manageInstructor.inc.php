<?php
$userid = $_SESSION['user_id'];
$role = 'instructor';

$sql = "SELECT * FROM users WHERE role = 'instructor' AND deletedOn is null ";
$instructor = $pdo->prepare($sql);
$instructor->execute();
?>

<div class="content">
    <button class="btn btn-success custom-button" onclick="window.location.href='index.php?page=addInstructor'">
        Add instructor
    </button>

    <div class="container mt-4">
        <h1>Manage Instructors</h1>

        <table class="table table-striped">
            <form method="post" action="PHP/manageInstructorAction.php">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
                </thead>

                <tbody>
                <?php
                if ($instructor->rowCount() > 0) {
                    while ($fetchInstructor = $instructor->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $fetchInstructor['full_name'] ?></td>
                            <td><?= $fetchInstructor['email'] ?></td>
                            <td><?= $fetchInstructor['role'] ?></td>
                            <td>
                                <button type="submit" class="btn btn-warning" name="editInstructor"
                                        value="<?= $fetchInstructor['id'] ?>">
                                    Edit
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-danger" name="deleteInstructor"
                                        onclick="return confirm('Are you sure you want to delete this instructor?')"
                                        value="<?= $fetchInstructor["id"] ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </form>
        </table>
    </div>
</div>
