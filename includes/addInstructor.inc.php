<script>
    function validateForm() {

        var zipCode = document.getElementById("postal_code").value;
        var zipCodePattern = /^\d{4}[a-zA-Z]{2}$/;

        if (!zipCodePattern.test(zipCode)) {
            alert("Please enter a valid ZIP code (e.g., 1234ab).");
            return false;
        }

        var phoneNumber = document.getElementById("phone").value;
        var phoneNumberPattern = /^\d{9}$/;

        if (!phoneNumberPattern.test(phoneNumber)) {
            alert("Please enter a valid phone number (9 digits only).");
            return false;
        }

        return true;
    }
</script>

<div class="content">
    <h1>Add Instructor</h1>
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<p style = "color:red;">' . $_SESSION['notification'] . '</p>';
        unset($_SESSION['notification']);
    }
    ?>
    <form method="post" action="PHP/manageInstructorAction.php" onsubmit="return validateForm()">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="full_name" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['full_name']; } ?>" required><br><br>
        <label for="street">Street:</label><br>
        <input type="text" id="street" name="street" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['street']; } ?>" required><br><br>
        <label for="house_number">House number:</label><br>
        <input type="number" id="house_number" name="house_number" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['house_number']; } ?>" required><br><br>
        <label for="postal_code">Postal code:</label><br>
        <input type="text" id="postal_code" name="postal_code" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['postal_code']; } ?>" required><br><br>
        <label for="city">City:</label><br>
        <input type="text" id="city" name="city" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['city']; } ?>" required><br><br>
        <label for="phone">Phone:</label><br>
        <input type="number" id="phone" name="phone" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['phone']; } ?>" required><br><br>
        <label for="age">Date of birth:</label><br>
        <input type="date" id="age" name="date_of_birth" required><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php if (isset($_SESSION['data']) and !empty($_SESSION['data'])) { echo $_SESSION['data']['post']['email']; } unset($_SESSION['data']) ?>" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password_hash" required><br><br>

        <input type="submit" name="addInstructor" value="Add Instructor" class="btn btn-success">
    </form>
</div>