<?php
include_once dirname(dirname(__FILE__)) . "/objects/user.php";

User::setAdmin($_POST['user_id'],$_POST['is_admin']);

?>