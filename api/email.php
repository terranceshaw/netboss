<?php

class Email {
    public static function sendMessage($recipient, $subject, $message) {
        ini_set("SMTP", "iaexet");
        ini_set("smtp_port", 25);
        $headers = 'From: noreply@cvn77.navy.mil' . "\r\n" .
                   'Reply-To: noreply@cvn77.navy.mil' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        $emailAddress = htmlspecialchars($recipient);
        $subject = htmlspecialchars($subject);
        $message = htmlspecialchars($message) . "\n\nMessage sent from ADP Helpdesk.";
        mail($emailAddress, $subject, $message, $headers);
    }
}

?>