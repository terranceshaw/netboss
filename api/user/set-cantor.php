<?php
include_once dirname(dirname(__FILE__)) . "/objects/user.php";

User::setCantor($_POST['user_id'],$_POST['cantor_mode']);

?>