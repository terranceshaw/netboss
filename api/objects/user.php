<?php
include_once dirname(dirname(__FILE__)) . "/core/db.php";
error_reporting(E_ALL);

class User {

    public static function setAdmin(int $userID, int $isAdmin) {
        $query = "UPDATE user_accounts SET is_admin=? WHERE id=?";
        if (DB::run($query,[$isAdmin,$userID])) {
            http_response_code(200);
            echo "Good to go ($userID, $isAdmin).";
        } else {
            http_response_code(500);
            echo "Error.";
        }
    }

    public static function setTechnician(int $userID, int $isTechnician) {
        $query = "UPDATE user_accounts SET hd_technician=? WHERE id=?";
        if (DB::run($query,[$isTechnician,$userID])) {
            http_response_code(200);
            echo "Good to go ($userID, $isTechnician).";
        } else {
            http_response_code(500);
            echo "Error.";
        }
    }

    public static function setSupervisor(int $userID, int $isSupervisor) {
        $query = "UPDATE user_accounts SET hd_supervisor=? WHERE id=?";
        if (DB::run($query,[$isSupervisor,$userID])) {
            http_response_code(200);
            echo "Good to go ($userID, $isSupervisor).";
        } else {
            http_response_code(500);
            echo "Error.";
        }
    }

    public static function setCantor(int $userID, int $cantorMode) {
        $query = "UPDATE user_accounts SET cantor_mode=? WHERE id=?";
        if (DB::run($query,[$cantorMode,$userID])) {
            http_response_code(200);
            echo "Good to go ($userID, $cantorMode).";
        } else {
            http_response_code(500);
            echo "Error.";
        }
    }

    public static function getUsers() {
        return json_encode(DB::run("SELECT * FROM user_accounts ORDER BY id ASC")->fetchAll());
    }

    public static function getUserInfo($userID) {
        if ($userID) {
            return json_encode(DB::run("SELECT * FROM user_accounts WHERE id=?", [$userID])->fetch());
        }
    }

    public static function listTechnicians() {
        return json_encode(DB::run("SELECT * FROM user_accounts WHERE hd_technician=1")->fetchAll());
    }

    public static function getUserCount() {
        return json_encode(DB::run("SELECT COUNT(id) AS count FROM user_accounts")->fetch());
    }

}

?>