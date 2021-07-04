<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.js"></script>
    <title>Testing a Theory</title>
</head>
<body class="padded">

<a href="#" class="link-button" id="show-form-link">New Ticket</a>

<form action="" method="post" class="new-ticket-form">
    <h3>New Form</h3>
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <input type="submit" value="Submit" class="confirm-btn">
    <input type="reset" value="Cancel" class="cancel-btn" id="cancel-btn">
</form>

<p>Just testing a theory for this deal.</p>

<style>
    .new-ticket-form {
        display: none;
        max-width: 250px;
        background: var(--accent-1);
        padding: 20px;
        border-radius: 10px;
        position: absolute;
        top: 20px;
        z-index: 1984;
    }
</style>

<script>
    $(document).ready(function () {
        $("#show-form-link").click(function(e) {
            $(".new-ticket-form").toggle("fast");
            e.preventDefault();
        })

        $("#cancel-btn").click(function(e) {
            $(".new-ticket-form").toggle();
        });
    });
</script>
    
</body>
</html>