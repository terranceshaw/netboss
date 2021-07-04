<?php
include_once dirname(dirname(__FILE__)) . "/objects/trouble-ticket.php";

TroubleTicket::completeTicket($_POST['closing_remarks'],$_POST['ticket_id']);

?>