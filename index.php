<?php
// include_once "api/core/db.php"; // Include the DB class.
include_once "api/objects/user.php";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/js/jquery.js"></script>
    <link rel="stylesheet" href="/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>#netboss</title>
</head>
<body>

<?php include "includes/navigation.php" ?>

<?php include isset($_GET['page']) ? "pages/" . $_GET['page'] . ".php" : "pages/home.php"; ?>

<footer>
    #netboss <?php echo date('Y') ?>
</footer>

</body>
</html>