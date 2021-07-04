<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Testing</title>
</head>
<body class="padded">

    <?php
        ini_set("SMTP", "ex02");
        ini_set("smtp_port", 25);
        $headers = 'From: noreply@cvn77.navy.mil' . "\r\n" .
                   'Reply-To: noreply@cvn77.navy.mil' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        if (isset($_POST['email-address'])) {
            $emailAddress = htmlspecialchars($_POST['email-address']);
            $message = htmlspecialchars($_POST['message']);
            if (mail($emailAddress, "Rando", $message, $headers)) {
                echo "Mail sent!";
	    } else {
                echo "Unable to send message.";
            }
        }
    ?>

    <form action="" method="post">
        <label for="email-address">E-mail Address</label>
        <input type="text" name="email-address" id="email-address">
        <label for="message">Message</label>
        <textarea name="message" id="message" cols="30" rows="10"></textarea>
        <input type="submit" value="Send" class="confirm-btn">
    </form>
    
</body>
</html>