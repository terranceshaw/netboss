<div class="padded">

<?php

    include_once "api/objects/trouble-ticket.php";
    $tickets = json_decode(TroubleTicket::fetchTickets(), true);

?>

    <table class="ticket-table">
        <thead>
            <tr>
                <th style="width: 50px">#</th>
                <th>Submitter</th>
                <th>Category</th>
                <th>Assigned Tech</th>
                <th>Closing Tech</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($tickets as $ticket) {
                    $ticketID = $ticket['id'];
                    $closingTech = !empty($ticket['closing_technician']) ? $ticket['closing_technician'] : "None";
                    echo "<tr class=\"ticket-row\" data-ticket-id=\"$ticketID\">\n";
                    echo "<td>" . $ticket['id'] . "</td>\n";
                    echo "<td>" . $ticket['submitter_name'] . "</td>\n";
                    echo "<td>" . $ticket['category'] . "</td>\n";
                    echo "<td>" . $ticket['assigned_technician'] . "</td>\n";
                    echo "<td>$closingTech</td>\n";
                    echo "</tr>\n";
                }
            ?>
        </tbody>
    </table>

</div>

<script>
    $(document).ready(function () {
        $("body").on("click", ".ticket-row", function(e) {
            e.preventDefault();
            var ticketID = $(this).data("ticket-id");
            alert("Clicked ticket " + ticketID);
        })
    });
</script>