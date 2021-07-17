<div class="padded">
    <?php if ($_SESSION['hd_supervisor'] == 1) { ?>

        <h3>System Metrics</h3>

        <p>Various ticket metrics for the curious.</p>

        <form action="" method="post" class="row gap" style="flex-wrap: nowrap">
            <label for="start-date">Start Date</label>
            <input type="date" name="start-date" id="start-date">
            <input type="submit" value="Filter" class="confirm-btn" style="max-width: 150px" value="2021-07-15">
        </form>

        <?php
            $dateFilter = null;
            if ((isset($_POST['start-date'])) && !empty($_POST['start-date'])) {
                $dateFilter = $_POST['start-date'];
                echo "<p>&nbsp;</p>\nShowing ticket metrics since $dateFilter.";
            }
        ?>

        <p>&nbsp;</p>

        <?php

            $tix = json_decode(TroubleTicket::metrics($dateFilter), true);
            $closingTechs = [];
            $assignedTechs = [];
            $categories = [];
            foreach ($tix as $ticket) {
                // Extract and tally closing technicians.
                if (!empty($ticket['closing_tech']) && !array_key_exists($ticket['closing_tech'], $closingTechs)) {
                    $closingTechs[$ticket['closing_tech']] = 1;
                } else {
                    $closingTechs[$ticket['closing_tech']]++;
                }

                // Establish and tally assigned technicians.
                if (!empty($ticket['assigned_tech']) && !array_key_exists($ticket['assigned_tech'], $assignedTechs)) {
                    $assignedTechs[$ticket['assigned_tech']] = 1;
                } else {
                    $assignedTechs[$ticket['assigned_tech']]++;
                }

                // Establish and tally categories.
                if (!empty($ticket['category']) && !array_key_exists($ticket['category'], $categories)) {
                    $categories[$ticket['category']] = 1;
                } else {
                    $categories[$ticket['category']]++;
                }
            }
            arsort($assignedTechs);
            arsort($closingTechs);
            arsort($categories);
        ?>

        <h3 class="page-title">Technician Metrics</h3>

        <div class="row gap">
            <div class="col">
                <h4>Tickets Assigned to Technicians</h4>

                <?php
                    foreach ($assignedTechs as $k => $v) {
                        if (!empty($k)) {
                            echo "<span><strong>$k</strong> - $v</span>\n";
                        }
                    }
                ?>
            </div>
            <div class="col">
                <h4 >Tickets Closed by Technicians</h4>

                <?php
                    foreach ($closingTechs as $k => $v) {
                        if (!empty($k)) {
                            echo "<span><strong>$k</strong> - $v</span>\n";
                        }
                    }
                ?>
            </div>
        </div>

        <p>&nbsp;</p>

        <h3 class="page-title">Ticket Metrics</h3>
        
        <h4>Tickets by Category Count</h4>
        <?php
            foreach ($categories as $k => $v) {
                if (!empty($k)) {
                    echo "<span><strong>$k</strong> - $v</span><br>\n";
                }
            }
        ?>
    
    <?php } else { ?>

        <h3>Unauthorized</h3>

        <p>You can't be here, broh.</p>

    <?php } ?>
</div>