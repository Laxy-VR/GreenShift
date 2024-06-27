<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once "db/dbconnection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or show an error message
    header("Location: login.php");
    exit();
}

// Get the current date
$current_date = date('Y-m-d');

// Fetch user's availability from the database for future dates
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM availability_instructor 
        WHERE Instructor_ID = :user_id AND avail_date >= :current_date";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id, 'current_date' => $current_date]);
$availabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<body>
<div class="content">
    <h1>My Availability</h1>
    <?php
    if (isset($_SESSION['notification'])) {
        echo '<p style="color:red;">' . $_SESSION['notification'] . '</p>';
        unset($_SESSION['notification']);
    }
    ?>
    <button class="btn btn-success custom-button" onclick="window.location.href='index.php?page=add_availability'">Add Availability</button>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">From</th>
            <th scope="col">To</th>
        </tr>
        </thead>
        <?php foreach ($availabilities as $availability): ?>
            <tr>
                <td><?= $availability['avail_date'] ?></td>
                <td><?= $availability['avail_start_time'] ?></td>
                <td><?= $availability['avail_end_time'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
</div>
