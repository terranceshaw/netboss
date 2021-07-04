<?php
// Trouble Ticket class to handle trouble tickets. Derp.
include_once dirname(dirname(__FILE__)) . "/core/db.php";

class TroubleTicket {

    private $connection;
    private $tableName = "trouble_tickets";

    // Object properties
    public $id;
    public $summary;
    public $description;

    public function __construct($db) {
        $this->connection = $db;
    }

    public static function listTickets() {
        return json_encode(DB::run("SELECT * FROM trouble_tickets ORDER BY id DESC")->fetchAll());
    }

}

?>