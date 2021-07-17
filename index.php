<?php 
error_reporting(E_ALL);
include_once "api/core/db.php";
include_once "api/core/users.php";  // This class is what controls user addition and modification.
include_once "api/email.php";
include_once "./api/objects/trouble-ticket.php";

$pageTitle = isset($_GET['page']) ? " - " . ucwords(str_replace("-", " ", $_GET['page'])) : " - Home";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/fontawesome/css/all.css">
    <script src="js/jquery.js"></script>
    <link rel="shortcut icon" type="image/png" href="favicon.png"/>
    <title>ADP Trouble Tickets <?php echo $pageTitle ?></title>
</head>
<body>
<?php
    if (@$_SESSION['cantor_mode'] == 1) {
        echo "<style>\n";
        echo "html,body { font-family: 'Courier New'; font-size: 8pt}";
        echo "</style>\n";
    }
?>
<ul class="nav-bar main-nav">
    <li class="nav-link"><a href="/">Home</a></li>
    <li class="nav-link"><a href="?page=trouble-tickets">Trouble Tickets</a></li>
    <?php if (@$_SESSION['hd_supervisor'] == 1) { // Hide the Admin link if the user ain't an admin. ?>
    <li class="nav-link"><a href="#">Supervisor</a>
        <ul class="nav-bar">
            <li class="nav-link"><a href="?page=closed-tickets">Closed Tickets</a></li>
            <li class="nav-link"><a href="?page=system-metrics">System Metrics</a></li>
        </ul>
    </li>
    <?php } ?>
    <?php if (@$_SESSION['is_admin'] == 1) { // Hide the Admin link if the user ain't an admin. ?>
    <li class="nav-link"><a href="#">Admin</a>
        <ul class="nav-bar">
            <li class="nav-link"><a href="?page=users">Users</a></li>
        </ul>
    </li>
    <?php } ?>
    <li class="nav-link"><a href="?page=user-settings" title="Currently logged in as <?php echo $_SESSION['username'] ?>"><i class="fas fa-user-circle"></i> <?php echo $_SESSION['username'] ?></a></li>
</ul>

<?php
    @include $_GET['page'] != null ? "./pages/" . $_GET['page'] . ".php" : "./pages/home.php";
?>

<script>
    // Prevent resubmission of data on page refresh.
    if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
    }
</script>

</body>
</html>