<?php
// User class to do usery things.
include_once dirname(dirname(__FILE__)) . "/core/db.php";

class User {
    public static function login($username, $password) {
        print_r(DB::run("SELECT * FROM trouble_tickets")->fetchAll());
    }
}

?>