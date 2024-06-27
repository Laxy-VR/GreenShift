<script>
    function validateForm() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        if (password != confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }

        var zipCode = document.getElementById("postal_code").value;
        var zipCodePattern = /^\d{4}[a-zA-Z]{2}$/;

        if (!zipCodePattern.test(zipCode)) {
            alert("Please enter a valid ZIP code (e.g., 1234ab).");
            return false;
        }

        var phoneNumber = document.getElementById("phone").value;
        var phoneNumberPattern = /^\d{10}$/;

        if (!phoneNumberPattern.test(phoneNumber)) {
            alert("Please enter a valid phone number (10 digits only).");
            return false;
        }

        return true;
    }
</script>
</head>
<body>
<form action="PHP/register_process.php" method="POST" onsubmit="return validateForm()">
    <h2>Register</h2><br>
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<p style="color:red;">' . $_SESSION['notification'] . '</p>';
        unset($_SESSION['notification']);
    }
    ?>
    <br>
    <div class="input-group">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['name']; } ?>" required>

        <label for="street">Street:</label>
        <input type="text" id="street" name="street" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['street']; } ?>" required>

        <label for="housenumber">House Number:</label>
        <input type="number" id="housenumber" name="housenumber" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['housenumber']; } ?>" required min="0">

        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['city']; } ?>" required>

        <label for="postal_code">Postal Code:</label>
        <input type="text" id="postal_code" name="postal_code" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['postal_code']; } ?>" required>

        <label for="phone">Phone Number:</label>
        <input type="number" id="phone" name="phone" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['phone']; } ?>" required>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['date_of_birth']; } ?>" required>
<Br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['email']; } ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>


        <input type="submit" value="Register">
    </div>
</form>
</body>
</html>
