<?php

if(isset($_SESSION['role'])){
    $userRole = $_SESSION['role'];
} else {
    $userRole = 'guest';
}

?>
<?php
$navItems = array(
    "guest" => array(
        array("Home", "home"),
        array("Register", "register"),
        array("Log In", "login")
    ),
    "student" => array(
        array("Home", "home"),
        array("Choose instructor", "chooseInstructor"),
        array("Schedule", "scheduleStudent"),
        array("Packages", "packages"),
        array("History", "lessonHistory")
    ),
    "instructor" => array(
        array("Home", "home"),
        array("Availability", "availability_instructor"),
        array("Schedule", "instructorSchedule"),
        array("Students", "students"),
        array("Notifications", "instructorNotification"),
        array("History", "history_instructor")
    ),
    "admin" => array(
        array("Home", "home"),
        array("Schedule", "schedule_admin"),
        array("Manage Instructors", "manageInstructor"),
        array("Notifications", "adminNotification")
    ),
);



?>
<header class="navbar">
    <nav class="nav">
        <ul>
            <?php foreach ($navItems[$userRole] as $item) { ?>
                <li><a href="?page=<?= $item[1] ?>"><?= $item[0] ?></a></li>
            <?php }if(isset($_SESSION['user_id'])){?>
            <li><a href=" PHP/logout.php">Logout</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>

