<?php
include_once dirname(dirname(__FILE__)) . "/core/db.php";

class TroubleTicket {

    public static function fetchTickets() {
        $query = "SELECT tix.*, tix.assigned_technician, a_tech.display_name AS assigned_technician, c_tech.display_name AS closing_technician FROM
                  trouble_tickets tix
                  LEFT JOIN user_accounts a_tech ON
                    tix.assigned_technician = a_tech.id
                  LEFT JOIN user_accounts c_tech ON
                    tix.closing_technician = c_tech.id
                  WHERE tix.close_date IS NULL
                  ORDER BY tix.id DESC";
        return json_encode(DB::run($query)->fetchAll());
    }

}

?>