<?php
$token = $_GET['token'];
?>

<div class="content">
    <h1>Reset password</h1>
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<p style = "color:red;">' . $_SESSION['notification'] . '</p>';
        unset($_SESSION['notification']);
    } ?>
    <form action="PHP/resetPassword.php" method="post">
        <div>
            <label for="date">New password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <input type="hidden" name="token" value="<?= $token ?>">
        <button type="submit" class="btn btn-success">Reset Password</button>
    </form>
</div>