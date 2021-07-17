<?php
include_once "api/objects/trouble-ticket.php";
include_once "api/objects/user.php";
?><div class="padded">

    <?php
        $tickets = json_decode(TroubleTicket::fetchTickets(), true);
        $count = count($tickets);
    ?><div class="row">
        <h3>ADP Trouble Tickets (<?php echo $count ?>)</h3>
	    <a href="#" id="new-ticket-button" style="margin-left: auto"><i class="fas fa-plus-circle"></i> New Ticket</a>
    </div>

    <p>Please do not submit non-ADP trouble tickets through this system. Those are trouble calls that <a href="http://tsims/tsims9">must be submitted through TSIMS</a>.</p>

    <table class="ticket-table">
        <thead>
            <tr>
                <?php if (($_SESSION['is_admin'] == 1) || ($_SESSION['hd_supervisor'] == 1) || ($_SESSION['hd_technician'] == 1)) { ?> <th>Age</th> <?php } ?>
                <th>Submitter</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Problem</th>
                <?php echo ($_SESSION['hd_technician'] == 1 || $_SESSION['hd_supervisor'] == 1) ? "<th>Quick Actions</td>" : null?>
            </tr>
        </thead>
        <tbody>
            <?php
                $technicians = json_decode(User::listTechnicians(), true);
                foreach ($tickets as $ticket) {
                    $ticketID = $ticket['id'];
                    $ticketAge = round((strtotime(date('mdy')) - strtotime($ticket['date_opened'])) / (60 * 60 * 24));
                    if ($ticketAge > 5) $ticketAge = "<span style=\"color: crimson; font-weight: bold\">$ticketAge</span>";    // If the ticket is older than five days... make it glaringly obvious.
                    @$attachmentLink = "/uploads/trouble_tickets//" . $ticket['uploaded_file'];
                    $attachment = !empty($ticket['uploaded_file']) ? "<a href=\"$attachmentLink\" target=\"_blank\"><i class=\"fas fa-paperclip\"></i></a>" : "-";
                    echo "<tr style=\"white-space: nowrap\" class=\"ticket-row\" data-ticket-id=\"$ticketID\">\n";
                    if (($_SESSION['is_admin'] == 1) || ($_SESSION['hd_supervisor'] == 1) || ($_SESSION['hd_technician'] == 1)) echo "\t<td style=\"text-align: center\">$ticketAge</td>\n";
                    echo "\t<td style=\"text-align: center\">" . $ticket['submitter_name'] . "</td>\n";
                    echo "\t<td style=\"text-align: center\">" . $ticket['location'] . "</td>\n";
                    echo "\t<td style=\"text-align: center\">" . $ticket['contact_method'] . " (" . $ticket['contact_info'] . ")" . "</td>\n";
                    echo "\t<td class=\"no-wrappy\" title=\"" . $ticket['problem_description'] . "\">" . substr($ticket['problem_description'], 0, 155) . "</td>\n";
                    if (($_SESSION['hd_technician'] == 1 || $_SESSION['hd_supervisor'] == 1)) {
                        echo "<td>";
                        echo "<i class=\"fas fa-check-circle ticket-button complete-ticket\" data-ticket-id=\"$ticketID\" title=\"Mark Complete\"></i>";
                        echo ($_SESSION['is_admin'] == 1) ? "<i class=\"fas fa-eraser ticket-button delete-ticket\" data-ticket-id=\"$ticketID\" title=\"Delete Ticket\"></i>" : null;  // Only admins can delete tickets, because... accountability.
                        echo "</td>\n";
                    }
                    echo "</tr>\n";
                }
            ?>
        </tbody>
    </table>
</div>

<form action="" method="post" id="new-ticket-form" class="modal-form" enctype="multipart/form-data">
    <div class="row gap">
        <div class="col">
            <h3>New Trouble Ticket</h3>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="e.g., SN Timmy" required>
            <label for="email-address">E-Mail Address</label>
            <input type="text" name="email-address" id="email-address" required value="<?php echo $_SESSION['username'] . "@cvn77.navy.mil" ?>">
            <label for="contact-info">Contact Info</label>
            <div class="row gap">
                <select name="contact-method" id="contact-method">
                    <option value="HYDRA">HYDRA</option>
                    <option value="J-Dial">J-Dial</option>
                </select>
                <input type="text" name="contact-info" id="contact-info" required>
            </div>
            <label for="location">Location</label>
            <input type="text" name="location" id="location" placeholder="e.g., 03-108-0-C" required>
            <label for="workstation-name">Workstation Name</label>
            <input type="text" name="workstation-name" id="workstation-name" value="<?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']) ?>">
            <label for="attachment">Attachment</label>
            <span class="subtle">Optional. Attach a screenshot or request form as necessary.</span>
            <input type="file" name="attachment" id="attachment">
            <label for="category">Category</label>
            <select name="category" id="category">
                <option value="Network Connectivity">Network Connectivity</option>
                <option value="Ethernet Cable Fabrication">Ethernet Cable Fabrication</option>
                <option value="New NTCSS Account">New NTCSS Account</option>
                <option value="GAL Update">GAL Update</option>
                <option value="Printer Troubleshooting">Printer Troubleshooting</option>
                <option value="Software Problems">Software Problems</option>
                <option value="Other">Other</option>
            </select>
            <label for="problem-description">Problem</label>
            <textarea name="problem-description" id="problem-description" cols="30" rows="10"></textarea>
        </div>
        <div class="col" style="max-width: 150px">
            <input type="submit" value="Submit" class="confirm-btn">
            <input type="reset" value="Cancel" class="cancel-btn" id="cancel-btn">
        </div>
    </div>
</form>

<script src="js/ticket-scripts.js"></script>