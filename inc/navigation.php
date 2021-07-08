<ul class="nav-bar main-nav">
    <li class="nav-link"><a href="/">Home</a></li>
    <?php if (@$_SESSION['is_admin'] == 1) { // Hide the Admin link if the user ain't an admin. ?>
    <li class="nav-link"><a href="#">Admin</a>
        <ul class="nav-bar">
            <li class="nav-link"><a href="?page=users">Users</a></li>
            <li class="nav-link"><a href="?page=admin-ticket-view">Trouble Tickets</a></li>
        </ul>
    </li>
    <?php } ?>
    <li class="nav-link"><a href="?page=user-settings" title="Currently logged in as <?php echo $_SESSION['username'] ?>"><i class="fas fa-user-circle"></i> <?php echo $_SESSION['username'] ?></a></li>
</ul>