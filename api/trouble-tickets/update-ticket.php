<?php
include_once dirname(dirname(__FILE__)) . "/objects/trouble-ticket.php";

TroubleTicket::updateTicket($_POST);

?>