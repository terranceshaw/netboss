<?php
@session_start();
include_once dirname(dirname(__FILE__)) . "/core/db.php";
include_once dirname(dirname(__FILE__)) . "/email.php";

class TroubleTicket {

    /*
    SCHEMA LEMA DING DONG:
        submitter_name
        submitter_email
        contact_method
        contact_info
        location
        workstation_name
        category
        problem_description
        assigned_technician
        date_opened
        closing_remarks
	closing_technician
        date_closed
        is_kb_item
*/

    public static function ticketCount() {
        $query = "SELECT COUNT(*) FROM trouble_tickets WHERE date_closed IS NULL";
        return DB::run($query)->fetchColumn();
    }

    public static function fetchTickets() {
        $query = "SELECT trouble_tickets.*, user_accounts.username AS technician_name
                  FROM trouble_tickets
                  LEFT JOIN user_accounts ON
                    user_accounts.id = trouble_tickets.assigned_technician
                  WHERE trouble_tickets.date_closed IS NULL
                  ORDER BY trouble_tickets.id DESC";
        return json_encode(DB::run($query)->fetchAll());
    }

    public static function fetchTicketDetails($ticketID) {
        $query = "SELECT tix.*, users.username AS assigned_technician_name, users.username AS closing_technician_name
                  FROM trouble_tickets tix
                  LEFT JOIN user_accounts users ON
                    -- users.id = tix.assigned_technician OR
                    users.id = tix.closing_technician
                  WHERE tix.id=?";
        return json_encode(DB::run($query, [$ticketID])->fetch());
    }

    public static function createTicket($ticketInfo) {
        foreach ($ticketInfo as &$info) {
            $info = htmlspecialchars($info);
        }
        $name = $ticketInfo['name'];
        $email = $ticketInfo['email-address'];
        $cMethod = $ticketInfo['contact-method'];
        $cInfo = $ticketInfo['contact-info'];
        $location = $ticketInfo['location'];
        $workstation = $ticketInfo['workstation-name'];
        $category = $ticketInfo['category'];
        $problem = $ticketInfo['problem-description'];
        $file = $_FILES['attachment'];
        $filename = !empty($file['name']) ? substr(hash('md5', 'lolwut'), 0, 10) . $file['name'] : null;   // Randomize the file name to prevent collisions or whatevs.
        $query = "INSERT INTO trouble_tickets (submitter_name, submitter_email, contact_method, contact_info, location, workstation_name, category, problem_description, uploaded_file) VALUES (?,?,?,?,?,?,?,?,?)";
        http_response_code(200);
        // echo json_encode(["message"=>"Here's your info: " . $query . ". Also, file info: " . implode(",", $file)]);
        

        if (DB::run($query, [$name,$email,$cMethod,$cInfo,$location,$workstation,$category,$problem,$filename])) {
            $id = DB::run("SELECT MAX(id) as LastID FROM trouble_tickets")->fetch()['LastID'];
            if (!empty($file['tmp_name'])) 
                copy($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/uploads/trouble_tickets//" . $filename);  // Copy file to uploads directory
            http_response_code(200);
            echo json_encode(["message"=>"Your ticket has been submitted. Your ticket number is $id"]);
        } else {
            http_response_code(400);
            echo json_encode(["message"=>"Unable to submit your ticket due to a server error. Please e-mail the helpdesk."]);
        }

        /*  $_FILES dealie doodler doo
            name
            type
            tmp_name
            error
            size
        */
    }

    public static function deleteTicket($ticketID) {
        if (DB::run("DELETE FROM trouble_tickets WHERE id=?", [$ticketID])) {
            http_response_code(200);
            echo json_encode(["message"=>"Ticket deleted successfully."]);
        } else {
            http_response_code(400);
            echo json_encode(["message"=>"Error deleting ticket."]);
        }
    }

    public static function completeTicket($remarks, $ticketID) {
        if (DB::run("UPDATE trouble_tickets SET closing_remarks=?, date_closed=GETDATE(), closing_technician=? WHERE id=?", [$remarks, $_SESSION['id'], $ticketID])) {
            http_response_code(200);
            echo json_encode(["message"=>"Ticket completed successfully."]);
            $emailAddress = DB::run("SELECT submitter_email FROM trouble_tickets WHERE id=?", [$ticketID])->fetchColumn();
            Email::sendMessage($emailAddress, "Ticket #$ticketID Completed", "Your ticket has been successfully completed by the ADP Helpdesk.\n\nClosing Remarks: $remarks\n\nPlease reach out if you have any further questions or concerns.\n\nThank you!");
        } else {
            http_response_code(400);
            echo json_encode(["message"=>"Error completing ticket."]);
        }
    }

}

?>