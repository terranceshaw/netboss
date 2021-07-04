<?php
include_once "api/objects/user.php";
include_once "api/objects/trouble-ticket.php";
?><div class="padded">
    <h2>ADP Trouble Ticketing System</h3>

    <div class="row gap">
        <div class="col">
            <h4>Trouble Ticket Metrics</h4>
            <?php
                $tickets = TroubleTicket::ticketCount();
                echo "There are currently " . $tickets . " open tickets.<br>\n";
            ?>
        </div>
    </div>
</div>