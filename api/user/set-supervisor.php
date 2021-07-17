<?php
include_once dirname(dirname(__FILE__)) . "/objects/user.php";

User::setSupervisor($_POST['user_id'],$_POST['is_supervisor']);

?>