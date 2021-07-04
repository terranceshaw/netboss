<?php
include_once dirname(dirname(__FILE__)) . "/objects/trouble-ticket.php";

TroubleTicket::deleteTicket($_POST['ticket_id']);

?>