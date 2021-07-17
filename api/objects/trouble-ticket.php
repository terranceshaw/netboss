<?php
@session_start();
include_once dirname(dirname(__FILE__)) . "/core/db.php";
include_once dirname(dirname(__FILE__)) . "/email.php";

class TroubleTicket {

    /*
    SCHEMA LEMA DING DONG:
        submitter_id    (to prevent people from saying "I dIdN't SuBmIt ThAt")
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

    public static function metrics($startDate=null) {
        if ($startDate) $startDate = "WHERE tix.date_opened >= '$startDate'";
        $query = "SELECT tix.*, a_tech.username AS assigned_tech, c_tech.username AS closing_tech FROM trouble_tickets tix
                    LEFT JOIN user_accounts a_tech ON
                        a_tech.id = tix.assigned_technician
                    LEFT JOIN user_accounts c_tech ON
                        c_tech.id = tix.closing_technician
                    $startDate";
        return json_encode(DB::run($query)->fetchAll());
    }

    public static function ticketCount() {
        $query = "SELECT COUNT(*) FROM trouble_tickets WHERE date_closed IS NULL";
        return DB::run($query)->fetchColumn();
    }

    public static function fetchTickets($showClosed=false) {
        $closeQuery = $showClosed ? "WHERE trouble_tickets.date_closed IS NOT NULL" : "WHERE trouble_tickets.date_closed IS NULL";
        $query = "SELECT trouble_tickets.*, user_accounts.username AS technician_name, c_tech.username AS closing_tech
                  FROM trouble_tickets
                  LEFT JOIN user_accounts ON
                    user_accounts.id = trouble_tickets.assigned_technician
                  LEFT JOIN user_accounts c_tech ON
                    c_tech.id = trouble_tickets.closing_technician
                  $closeQuery
                  ORDER BY trouble_tickets.id DESC";
        return json_encode(DB::run($query)->fetchAll());
    }

    public static function fetchTicketDetails($ticketID) {
        $query = "SELECT tix.*, tix_audits.*, change_tech.username AS change_tech_name, a_tech.username AS assigned_technician_name, c_tech.username AS closing_technician_name
                  FROM trouble_tickets tix
                  LEFT JOIN user_accounts a_tech ON
                    a_tech.id = tix.assigned_technician
                  LEFT JOIN user_accounts c_tech ON
                    c_tech.id = tix.closing_technician
                  LEFT JOIN trouble_ticket_audits tix_audits ON
                    tix_audits.id = $ticketID
                  LEFT JOIN user_accounts change_tech ON
                    change_tech.id = tix_audits.user_id
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
        @$file = $_FILES['attachment']; // Needed to silence this assignment to fix bug reported by ABH1 Arnold 06JUL21
        $filename = !empty($file['name']) ? substr(hash('md5', 'lolwut'), 0, 10) . $file['name'] : null;   // Randomize the file name to prevent collisions or whatevs.
        $query = "INSERT INTO trouble_tickets (submitter_id, submitter_name, submitter_email, contact_method, contact_info, location, workstation_name, category, problem_description, uploaded_file) VALUES (?,?,?,?,?,?,?,?,?,?)";
        http_response_code(200);
        // echo json_encode(["message"=>"Here's your info: " . $query . ". Also, file info: " . implode(",", $file)]);
        

        if (DB::run($query, [$_SESSION['id'],$name,$email,$cMethod,$cInfo,$location,$workstation,$category,$problem,$filename])) {
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

    public static function updateTicket($ticketData) {
        // Get them sweet, sweet datas...
        @$ticketID = $ticketData['ticket_id'];
        @$ticketRemarks = !empty($ticketData['ticket_remarks']) ? $ticketData['ticket_remarks'] : null;
        @$ticketStatus = ($ticketData['ticket_status'] == "Closed") ? date('Y-m-d') : null;
        @$assignedTech = !empty($ticketData['assigned_technician']) ? $ticketData['assigned_technician'] : null;
        @$techName = !empty($ticketData['assigned_technician']) ? DB::run("SELECT username FROM user_accounts WHERE id=?", [$ticketData['assigned_technician']])->fetchColumn() : "None";
        @$closingTech = $ticketStatus ? $_SESSION['id'] : null;
        @$isKB = isset($ticketData['is_kb_item']) ? 1 : 0;
        // echo json_encode($ticketData);
        if (DB::run("UPDATE trouble_tickets SET date_closed=?, closing_remarks=?, assigned_technician=?, closing_technician=?, is_kb_item=? WHERE id=?", [$ticketStatus, $ticketRemarks, $assignedTech, $closingTech, $isKB, $ticketID])) {
            http_response_code(200);
            self::updateAuditLog($ticketID, "[Assigned Technician: $techName] " . $ticketRemarks);
            echo json_encode(["message"=>"Ticket updated."]);
            if ($ticketStatus) {
                // Send e-mail if it's been closed out.
                $ticketProblem = DB::run("SELECT problem_description FROM trouble_tickets WHERE id=?", [$ticketID])->fetchColumn();
                $emailAddress = DB::run("SELECT submitter_email FROM trouble_tickets WHERE id=?", [$ticketID])->fetchColumn();
                Email::sendMessage($emailAddress, "Ticket #$ticketID Completed", "Your ticket has been successfully completed by the ADP Helpdesk.\n\nTicket Problem: $ticketProblem\n\nClosing Remarks: $ticketRemarks\n\nPlease reach out if you have any further questions or concerns.\n\nThank you!");
            }
        } else {
            http_response_code(400);
            echo json_encode(["message"=>"Error updating ticket."]);
        }
    }

    public static function completeTicket($remarks, $ticketID) {
        if (DB::run("UPDATE trouble_tickets SET closing_remarks=?, date_closed=GETDATE(), closing_technician=? WHERE id=?", [$remarks, $_SESSION['id'], $ticketID])) {
            $ticketProblem = DB::run("SELECT problem_description FROM trouble_tickets WHERE id=?", [$ticketID])->fetchColumn();
            http_response_code(200);
            echo json_encode(["message"=>"Ticket completed successfully."]);
            $emailAddress = DB::run("SELECT submitter_email FROM trouble_tickets WHERE id=?", [$ticketID])->fetchColumn();
            self::updateAuditLog($ticketID, $remarks);
            Email::sendMessage($emailAddress, "Ticket #$ticketID Completed", "Your ticket has been successfully completed by the ADP Helpdesk.\n\nTicket Problem: $ticketProblem\n\nClosing Remarks: $remarks\n\nPlease reach out if you have any further questions or concerns.\n\nThank you!");
        } else {
            http_response_code(400);
            echo json_encode(["message"=>"Error completing ticket."]);
        }
    }

    public static function updateAuditLog($ticketID, $remarks=null) {
        /*
            id
            user_id
            ticket_id
            change_description
            date
        */
        DB::run("INSERT INTO trouble_ticket_audits (user_id, ticket_id, change_description, date) VALUES (?,?,?,GETDATE())", [$_SESSION['id'], $ticketID, $remarks]);
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

}

?>