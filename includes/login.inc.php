<div class="content">
    <form action="PHP/login.php" method="POST">
        <h2>Login</h2>
        <?php if (isset($_SESSION['notification2'])) {
            echo '<p style="color:darkgreen;">' . $_SESSION['notification2'] . '</p>';
            unset($_SESSION['notification2']);
        }
        if (isset($_SESSION['notification'])) {
            echo '<p style="color:red;">' . $_SESSION['notification'] . '</p>';
            unset($_SESSION['notification']);
        }
        ?>
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="loginuser">Login</button>
        </div>
        <div>
            <?php
            // Display error message if login failed
            if (isset($_GET['error']) && $_GET['error'] === 'login_failed') {
                echo "<p class='error'>Wrong login details. Please try again.</p>";
            } elseif (isset($_GET['error']) && $_GET['error'] === 'login_locked') {
                echo "<p class='error'>Maximum login attempts reached. Please try again in one hour.</p>";
            }
            ?>
        </div>
        <a href="index.php?page=register">Don't have an account? Sign up here!</a><br>
        <a href="index.php?page=sendResetEmail">Forgot password?</a>

    </form>
</div>
