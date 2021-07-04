<h3>#netboss</h3>
<h4>Welcome to the ADP trouble ticket system.</h4>
<h5>Got you logged in as <?php echo "<strong>" . $_SESSION['username'] . "</strong>" ?></h5>
<p>&nbsp;</p>

<div class="row">
    <div class="col">
        <h3>Open Tickets</h3>
        <?php
            $tickets = DB::run("SELECT * FROM trouble_tickets")->fetchAll();
            if (count($tickets) > 1) {
                foreach ($tickets as $ticket) {
                    echo $ticket['title'];
                }
            }

            $name = ucwords(str_replace(".", " ", explode("\\", $_SERVER['AUTH_USER'])[1]));
        ?>
    </div>
    <div class="col" style="max-width: 400px">
        <h3>Submit a new ticket.</h3>
        <h4>All fields are required; please be as specific as possible.</h4>
        <form action="" method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?php echo $name ?>" placeholder="e.g., SN Timmy" required>
            <label for="contact-info">Contact Information</label>
            <div class="row gap">
                <select name="contact-method" id="contact-method">
                    <option value="J-Dial">J-Dial</option>
                    <option value="HYDRA">HYDRA</option>
                </select>
                <input type="text" name="contact-info" id="contact-info" placeholder="J-Dial, HYDRA, etc." required>
            </div>
            <label for="location">Location</label>
            <input type="text" name="location" id="location" required>
            <label for="workstation-name">Workstation Name</label>
            <input type="text" name="workstation-name" id="workstation-name" value="<?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']) ?>" required>
            <label for="category">Category</label>
            <select name="category" id="category">
                <option value="Network connectivity">Network connectivity</option>
                <option value="Hardware problems">Hardware problems</option>
                <option value="Software problems">Software problems</option>
                <option value="NTCSS account problems">NTCSS account problems</option>
                <option value="Ethernet cable fabrication">Ethernet cable fabrication</option>
                <option value="Other">Other</option>
            </select>
            <label for="description">Problem Description</label>
            <textarea name="description" id="description" cols="30" rows="10" required></textarea>
            <div class="row gap">
                <input type="submit" value="Submit" class="confirm-btn">
                <input type="reset" value="Reset" class="cancel-btn">
            </div>
        </form>
    </div>
</div>