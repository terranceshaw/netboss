<?php
include_once "./api/objects/user.php";
include_once "./api/objects/trouble-ticket.php";
?>

<div class="col padded">
    <h3>Ticket Details (#<?php echo $_GET['ticket_id'] ?>)</h3>
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
                echo "<h4 style=\"color: crimson\">Ticket Closed on " . $ticket['date_closed'] . " by <strong>" . $ticket['closing_technician_name'] . "</strong></h4>";
            }
        }
    ?>

    <form action="" method="post" id="edit-ticket-form">
        <div class="row gap" style="flex-wrap: nowrap">
            <table class="ticket-details-table" style="table-layout: fixed">
            <input type="hidden" name="ticket_id" value="<?php echo $_GET['ticket_id'] ?>">
                <tr>
                    <td class="ticket-details-header">Submitter</td>
                    <td class="ticket-details-header">Contact Info</td>
                    <td class="ticket-details-header">Location</td>
                </tr>
                <tr>
                    <td><a href="mailto:<?php echo $ticket['submitter_email'] ?>"><?php echo $ticket['submitter_name'] ?></a> on <?php echo $ticket['date_opened'] ?></td>
                    <td><?php echo $ticket['contact_info'] . " (" . $ticket['contact_method']  . ")" ?></td>
                    <td><?php echo $ticket['location'] ?></td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td class="ticket-details-header">Category</td>
                    <td class="ticket-details-header">Attachment</td>
                    <td class="ticket-details-header">Assigned Technician</td>
                </tr>
                <tr>
                    <td><?php echo $ticket['category'] ?></td>
                    <td><?php 
                        if (!empty($ticket['uploaded_file'])) {
                            if (($_SESSION['is_admin'] == 1) ||  ($_SESSION['hd_supervisor'] == 1) ||  ($_SESSION['hd_technician'] == 1)) {
                                echo "<a href=\"uploads/trouble_tickets/\\" . $ticket['uploaded_file'] . "\" target=\"_blank\">Click to View</a>";
                            } else {
                                echo "Hidden";
                            }
                        } else {
                            echo "None";
                        }
                    ?></td>
                    <td>
                    <?php
                        if (($_SESSION['hd_supervisor'] == 1)) {
                            // If they're an admin or supervisor, let them assign a technician with a list box.
                            echo "<select name=\"assigned_technician\">\n";
                            echo "\t<option value=\"\">None</option>\n";
                            foreach ($technicians as $tech) {
                                $id = $tech['id'];
                                $name = $tech['username'];
                                $selected = $ticket['assigned_technician'] == $tech['id'] ? " selected" : null;
                                echo "\t<option value=\"$id\"$selected>$name</option>\n";
                            }
                            echo "</select>";
                        } else {
                            // Otherwise just show them the assigned tech.
                            echo !empty($ticket['assigned_technician']) ? $ticket['assigned_technician_name'] : "None Assigned";
                        }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td class="ticket-details-header">Workstation</td>
                    <td class="ticket-details-header">Status</td>
                    <td class="ticket-details-header">Knowledge Base Item</td>
                </tr>
                <tr>
                    <td><?php echo $ticket['workstation_name'] ?></td>
                    <td><?php
                        if (($_SESSION['hd_supervisor']) == 1 || ($_SESSION['hd_technician']) == 1) {
                            // They're the right permissions for this, so let them choose whether or not to close the ticket.
                            $status = empty($ticket['date_closed']) ? null : " selected";
                            echo "<select name=\"ticket_status\" id=\"ticket-status\" style=\"max-width: 150px\">\n";
                            echo "\t<option value=\"Open\">Open</option>\n";
                            echo "\t<option value=\"Closed\"$status>Closed</option>\n";
                            echo "</select>\n";
                        } else {
                            // They're not part of the Goose Club. Just show a status.
                            echo empty($ticket['close_date']) ? "Open" : "Closed";
                        }
                    ?></td>
                    <?php
                        $kbDisabled = "";
                        if (($_SESSION['hd_supervisor'] == 1) || ($_SESSION['hd_technician'] == 1)) {
                            // Only show the save button if the user is admin, HD sup, or HD tech.
                            $kbDisabled = "";
                        } else {
                            $kbDisabled = " disabled";
                        }
                        $isKBActual = $ticket['is_kb_item'] == 1 ? "checked" : null;
                    ?>
                    <td><input type="checkbox" name="is_kb_item" id="is-kb-item"<?php echo $isKBActual . " " . $kbDisabled ?>></td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td class="ticket-details-header">Problem Details</td>
                </tr>
                <tr>
                    <td colspan="3"><?php echo $ticket['problem_description'] ?></td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td class="ticket-details-header">Remarks</td>
                </tr>
                <tr>
                    <td colspan="3"><?php
                        if (($_SESSION['hd_supervisor'] == 1) || ($_SESSION['hd_technician'] == 1)) {
                            // Make sure they wield the powah to update remarks...
                            echo "<br><textarea name=\"ticket_remarks\" style=\"height: 200px\">" . $ticket['closing_remarks'] . "</textarea>";
                        } else {
                            // Or just show them if not.
                            echo $ticket['closing_remarks'];
                        }
                    ?></td>
                </tr><?php if (($_SESSION['is_admin'] == 1) || ($_SESSION['hd_supervisor'] == 1) || ($_SESSION['hd_technician'] == 1)) { ?>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
                <tr>
                    <td class="ticket-details-header">Ticket Updates</td>
                </tr>
                <tr>
                    <td><strong>Technician</strong></td>
                    <td><strong>Change</strong></td>
                </tr>
                    <?php
                        $query = "SELECT tix_tech.username, tix_audits.change_description AS change, tix_audits.date FROM trouble_ticket_audits tix_audits
                                  LEFT JOIN user_accounts tix_tech ON
                                    tix_tech.id = tix_audits.user_id
                                  WHERE tix_audits.ticket_id=?";
                        $ticketAudits = DB::run($query, [$_GET['ticket_id']])->fetchAll();
                        foreach ($ticketAudits as $audit) {
                            $cUser = $audit['username'];
                            $cChange = $audit['change'];
                            echo "<tr>\n";
                            echo "<td>$cUser</td>\n";
                            echo "<td class=\"no-wrappy\" colspan=\"2\">$cChange</td>\n";
                            echo "</tr>\n";
                        }
                    ?>
                <?php } ?>
            </table>
            <?php
                if (($_SESSION['hd_supervisor'] == 1) || ($_SESSION['hd_technician'] == 1)) {
                    // Only show the save button if the user is admin, HD sup, or HD tech.
                    echo "<input type=\"submit\" value=\"Save Changes\" class=\"confirm-btn\" style=\"height: 30px; width: 150px; margin-left: auto; margin-top: 0\">\n";
                }
            ?>
        </div>
    </form>

    <img src="img/goose-is-loose.png" alt="HONK!" class="printable-easter-egg" style="height: 200px">
</div>

<script>
    $(document).ready(function () {
        $("#edit-ticket-form").submit(function(e) {
            e.preventDefault();

            var theDatas = $("#edit-ticket-form").serialize();
            $.post("api/trouble-tickets/update-ticket.php", theDatas, function(data) {
                var json = null;
                if (json = JSON.parse(data)) {
                    alert(json.message);
                    window.location.reload();
                } else {
                    alert(data);
                }
            });
        })
    });
</script>