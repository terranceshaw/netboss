<p>Come get some.</p>

<?php
    // Connection test
    // DB::connectionStatus();

    // $conn = new PDO("sqlsrv:server=sq01; database=nemesys", "netboss", "P@ssw0rd!@#$%^&");
    $conn = null;
    if (!$conn = new PDO("odbc:Driver={SQL Server};server=SQ01; database=netboss", "netboss", "P@ssw0rd!@#$%^&"))
        echo "Error connecting to the database.";

    // $sql = "SELECT * FROM trouble_tickets";
    // $results = $conn->query($sql)->fetchAll();
    // foreach ($results as $ticket) {
    //     echo $ticket['title'];
    // }

?>

<form action="index.php" method="post">
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <input type="submit" value="Submit" class="confirm-btn">
    <input type="reset" value="Reset" class="cancel-btn">
</form>

<?php
    if (isset($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $sql = "INSERT INTO user_accounts (username, password) VALUES (?,?)";
        if ($conn->prepare($sql)->execute([$username, $password])) {
            echo "Account created.";
        } else {
            echo "Error creating account.";
        }
    }
    echo $_SERVER['AUTH_USER'];
?>