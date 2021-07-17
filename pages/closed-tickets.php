<?php
include_once "api/objects/trouble-ticket.php";
include_once "api/objects/user.php";
?><div class="padded">

    <?php if ($_SESSION['hd_supervisor'] == 1) { ?>

        <h3>Closed Tickets</h3>

        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Ticket Number</th>
                    <th>Request POC</th>
                    <th>Assigned Technician</th>
                    <th>Closing Technician</th>
                    <th>Date Opened</th>
                    <th>Date Closed</th>
                    <th>Ticket Age</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $tickets = json_decode(TroubleTicket::fetchTickets(true), true);
                    $technicians = json_decode(User::listTechnicians(), true);
                    foreach ($tickets as $ticket) {
                        $ticketID = $ticket['id'];
                        $ticketAge = round((strtotime($ticket['date_closed']) - strtotime($ticket['date_opened'])) / (60 * 60 * 24));
                        $days = $ticketAge == 1 ? "day" : "days";
                        $aTech = empty($ticket['technician_name']) ? "<span style=\"opacity: .15\">None Assigned</span>" : $ticket['technician_name'];
                        echo "<tr style=\"white-space: nowrap\" class=\"ticket-row\" data-ticket-id=\"$ticketID\">\n";
                        echo "\t<td style=\"text-align: center\">$ticketID</td>\n";
                        echo "\t<td style=\"text-align: center\">" . $ticket['submitter_name'] . "</td>\n";
                        echo "\t<td style=\"text-align: center\">$aTech</td>\n";
                        echo "\t<td style=\"text-align: center\">" . $ticket['closing_tech'] . "</td>\n";
                        echo "\t<td style=\"text-align: center\">" . $ticket['date_opened'] . "</td>\n";
                        echo "\t<td style=\"text-align: center\">" . $ticket['date_closed'] . "</td>\n";
                        echo "\t<td style=\"text-align: center\">$ticketAge $days</td>\n";
                        echo "</tr>\n";
                    }
                ?>
            </tbody>
        </table>

    <?php } else { ?>

        <h3>Unauthorized</h3>

        <p>No.</p>

    <?php } ?>


    
</div>

<script src="js/ticket-scripts.js"></script>