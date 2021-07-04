<?php
include_once "api/objects/trouble-ticket.php";
include_once "api/objects/user.php";
?><div class="padded">

    <div class="row">
        <h3>ADP Trouble Tickets</h3>
	<a href="#" id="new-ticket-button" style="margin-left: auto"><i class="fas fa-plus-circle"></i> New Ticket</a>
    </div>

    <p>Please do not submit non-ADP trouble tickets through this system. Those are trouble calls that <a href="http://tsims/tsims9">must be submitted through TSIMS</a>.</p>

    <table style="width: 100%">
        <thead>
            <tr>
                <th>Submitter</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Problem</th>
                <?php echo ($_SESSION['hd_technician'] == 1 || $_SESSION['hd_supervisor'] == 1) ? "<th>Attachment</td>" : null?>
                <th>Assigned Technician</th>
                <?php echo ($_SESSION['hd_technician'] == 1 || $_SESSION['hd_supervisor'] == 1) ? "<th>Quick Actions</td>" : null?>
            </tr>
        </thead>
        <tbody>
            <?php
                $tickets = json_decode(TroubleTicket::fetchTickets(), true);
                $technicians = json_decode(User::listTechnicians(), true);
                foreach ($tickets as $ticket) {
                    $ticketID = $ticket['id'];
                    @$attachmentLink = "/uploads/trouble_tickets//" . $ticket['uploaded_file'];
                    $attachment = !empty($ticket['uploaded_file']) ? "<a href=\"$attachmentLink\" target=\"_blank\"><i class=\"fas fa-paperclip\"></i></a>" : "-";
                    echo "<tr style=\"white-space: nowrap\" class=\"ticket-row\" data-ticket-id=\"$ticketID\">\n";
                    echo "\t<td style=\"text-align: center\">" . $ticket['submitter_name'] . "</td>\n";
                    echo "\t<td style=\"text-align: center\">" . $ticket['location'] . "</td>\n";
                    echo "\t<td style=\"text-align: center\">" . $ticket['contact_method'] . " (" . $ticket['contact_info'] . ")" . "</td>\n";
                    echo "\t<td class=\"no-wrappy\">" . substr($ticket['problem_description'], 0, 155) . "</td>\n";
                    // echo "<td><select name=\"assigned-technician\">";
                    // foreach ($technicians as $technician) {
                    //     echo "<option value=\"" . $technician['id'] . "\">" . $technician['username'] . "</option>";
                    // }
                    // echo "</select></td>\n";
                    echo ($_SESSION['hd_technician'] == 1 || $_SESSION['hd_supervisor'] == 1) ? "\t<td style=\"text-align: center\">$attachment</td>" : null;
                    echo "\t<td style=\"text-align: center\">" . (empty($ticket['technician_name']) ? "None Assigned" : $ticket['technician_name']) . "</td>\n";
                    if (($_SESSION['hd_technician'] == 1 || $_SESSION['hd_supervisor'] == 1)) {
                        echo "<td>";
                        echo "<i class=\"fas fa-check-circle ticket-button complete-ticket\" data-ticket-id=\"$ticketID\" title=\"Mark Complete\"></i>";
                        echo "<i class=\"fas fa-eraser ticket-button delete-ticket\" data-ticket-id=\"$ticketID\" title=\"Delete Ticket\"></i>";
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
                <input type="text" name="contact-info" id="contact-info">
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

<script>
    $(document).ready(function () {
        $(document).on("click", ".ticket-row", function(e) {
            var ticketID = $(this).data("ticket-id");
            window.location = "?page=ticket-details-admin&ticket_id=" + ticketID;
        });

        $(document).on("click", ".delete-ticket", function(e) {
            e.stopPropagation();
            var row = $(this).parent().parent("tr");
            if (confirm("Are you sure you want to delete this ticket?")) {
                var ticketID = $(this).data("ticket-id");
                $.post("api/trouble-tickets/delete-ticket.php", {
                    "ticket_id":ticketID
                }, function(data) {
                    row.fadeOut("fast", function() {
                        $(row).remove();
                        console.log("Deleted " + ticketID);
                    });
                });
            }
        });

        $(document).on("click", ".complete-ticket", function(e) {
            e.stopPropagation();
            var row = $(this).parent().parent("tr");
            var closeNote = null;
            if (closeNote = prompt("Enter notes for entry closure. Be advised: the requesting user will see these in the closeout e-mail notification.")) {
                var ticketID = $(this).data("ticket-id");
                $.post("api/trouble-tickets/complete-ticket.php", {
                    "closing_remarks":closeNote,
                    "ticket_id":ticketID
                }, function(data) {
                    row.fadeOut("fast", function() {
                        row.remove();
                        console.log("Closed " + ticketID + " with a close note of " + closeNote);
                    });
                });
            }
        });

        $("#new-ticket-button,#cancel-btn").click(function(e) {
            // Show/hide the trouble ticket form.
            e.preventDefault();
            $("#new-ticket-form").fadeToggle();
            $("#name").focus();
        });

        // Trouble ticket submission logic.
        $("#new-ticket-form").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: "api/trouble-tickets/new-ticket.php",
                type: 'POST',
                data: formData,
                success: function(data) {
                    var json = null;
                    if (json = JSON.parse(data)) {
                        alert(json.message);
                        $("#new-ticket-form").fadeOut("fast");
                    } else {
                        alert(data);
                    }
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
</script>