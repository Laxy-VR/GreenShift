<?php
$userid = $_GET['userid'];

$sql = "SELECT * FROM users where id = :userid ";
$queryFetchedInstructor = $pdo->prepare($sql);
$queryFetchedInstructor->bindParam(':userid', $userid);
$queryFetchedInstructor->execute();
$instructorData = $queryFetchedInstructor->fetch(PDO::FETCH_ASSOC);
?>

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
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<p style = "color:red;">' . $_SESSION['notification'] . '</p>';
        unset($_SESSION['notification']);
    }
    ?>
    <h1>Edit Instructor</h1>
    <form method="post" action="PHP/manageInstructorAction.php" onsubmit="return validateForm()">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="full_name" value="<?= $instructorData['full_name'] ?>"><br><br>
        <label for="name">Street:</label><br>
        <input type="text" id="street" name="street" value="<?= $instructorData['street'] ?>"><br><br>
        <label for="name">Housenumber:</label><br>
        <input type="text" id="house_number" name="house_number" value="<?= $instructorData['house_number'] ?>"><br><br>
        <label for="name">Postal code:</label><br>
        <input type="text" id="postal_code" name="postal_code" value="<?= $instructorData['postal_code'] ?>"><br><br>
        <label for="name">City:</label><br>
        <input type="text" id="city" name="city" value="<?= $instructorData['city'] ?>"><br><br>
        <label for="name">Phone:</label><br>
        <input type="number" id="phone" name="phone" value="<?= $instructorData['phone'] ?>"><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= $instructorData['email'] ?>"><br><br>

        <input type="submit" name="updateInstructor" value="Update" class="btn btn-success">
        <input type="hidden" name="userid" value="<?= $userid ?>">
    </form>
</div>
