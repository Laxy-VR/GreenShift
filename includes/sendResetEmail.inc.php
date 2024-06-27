<div class="content">
    <h1>Send password reset email</h1>
    <form action="PHP/sendMailActionPasswordReset.php" method="post">
        <div>
            <label for="date">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-success">Send Email</button>
    </form>
</div>