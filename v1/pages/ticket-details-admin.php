<?php
include_once "./api/objects/user.php";
include_once "./api/objects/trouble-ticket.php";
if ($_SESSION['is_admin'] == 1) {   // Validate dat admin status
?>

<div class="col padded">
    <h3>Ticket Details</h3>
    <?php
        error_reporting(1);
        $ticketID = isset($_GET['ticket_id']) ? $_GET['ticket_id'] : null;
        $technicians = json_decode(User::listTechnicians(), true);
        if ($ticketID) {
            // If a valid ticket ID was provided...
            $ticket = json_decode(TroubleTicket::fetchTicketDetails($ticketID), true);
            $disabled = null;
            if (!empty($ticket['date_closed'])) {
                $disabled = " disabled";
                echo "<h4 style=\"color: crimson\">Ticket Closed</h4>";
            }
        }
    ?>

    <form action="" method="post" id="edit-ticket-form" class="gap" style="flex: 0 0 auto; justify-content: flex-start">
        <div class="row gap">
            <div class="col">
                <strong>Submitter Name</strong>
                <a href="mailto:<?php echo $ticket['submitter_email'] ?>"><?php echo $ticket['submitter_name'] ?></a>
            </div>
            <div class="col">
                <strong>Contact Info</strong>
                <?php echo $ticket['contact_info'] . " (" . $ticket['contact_method']  . ")" ?>
            </div>
            <div class="col">
                <strong>Location</strong>
                <?php echo strtoupper($ticket['location']) ?>
            </div>
        </div>
        <div class="row gap">
            <div class="col">
                <strong>Category</strong>
                <?php echo $ticket['category'] ?>
            </div>
            <div class="col">
                <strong>Attachment</strong>
                <?php echo !empty($ticket['uploaded_file']) ? "<a href=\"uploads/trouble_tickets/\\" . $ticket['uploaded_file'] . "\" target=\"_blank\">Click to View</a>" : "None" ?>
            </div>
            <div class="col">
            </div>
        </div>
        <div class="row gap">
            <div class="col">
                <strong>Problem Description</strong>
                <p><?php echo $ticket['problem_description'] ?></p>
            </div>
        </div>
    </form>

    <?php 
            // echo "<p>&nbsp;</p><pre>";
            // print_r($ticket);
            // echo "</pre>\n";
    ?>
</div>

<?php } else { ?>

<div class="padded">

    <h3>Unauthorized</h3>

    <p>You lack the proper accesses required to view this page.</p>

    <p>Please contact a system administrator if you believe this to be in error.</p>

</div>

<?php } ?>