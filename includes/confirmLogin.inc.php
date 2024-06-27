<?php
$email = $_SESSION['email2FA'];
$token = $_GET['token'];
?>

<div class="content">
    <h1>Confirm Login</h1>
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<p style="color:red;">' . $_SESSION['notification'] . '</p>';
        unset($_SESSION['notification']);
    }
    ?>
    <form action="PHP/check2FA.php" method="post">
        <div>
            <label for="date">Enter your verification code:</label>
            <input type="text" id="verificationCode" name="verificationCode" required>
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="hidden" name="token" value="<?= $token ?>">
        </div>
        <button type="submit" class="btn btn-success">Confirm Login</button>
    </form>
</div>