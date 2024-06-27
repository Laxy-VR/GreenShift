<?php
session_start();
include_once "db/dbconnection.php";
$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GreenShift - <?= ucfirst($_GET['page']) ?></title>
    <link href="css/stylesheet.css" rel="stylesheet">
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">-->
<!--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>-->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">-->

</head>
<body>
<?php
include 'nav.inc.php';
include './includes/' . $page . '.inc.php';
?>
</body>