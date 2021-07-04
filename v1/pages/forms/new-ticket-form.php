<form action="" method="post" id="new-ticket-form" style="max-width: 400px; display: none; position: absolute; right: 10px; top: 35px; background: var(--accent-2); padding: 10px; border-radius: 5px">
    <span style="margin-bottom: 10px; opacity: .5">All fields required; please be as specific as possible.</span>
    <label for="name">Name</label>
    <input type="text" name="name" id="name">
    <label for="email-address">E-Mail Address</label>
    <input type="text" name="email-address" id="email-address" value="<?php echo $_SESSION['username'] . "@cvn77.navy.mil" ?>">
    <label for="contact-info">Contact Info</label>
    <div class="row gap">
        <select name="contact-method" id="contact-method">
            <option value="HYDRA">HYDRA</option>
            <option value="J-Dial">J-Dial</option>
        </select>
        <input type="text" name="contact-info" id="contact-info">
    </div>
    <label for="location">Location</label>
    <input type="text" name="location" id="location">
    <label for="workstation-name">Workstation Name</label>
    <input type="text" name="workstation-name" id="workstation-name" value="<?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']) ?>">
    <label for="category">Category</label>
    <select name="category" id="category">
        <option value="Network Connectivity">Network Connectivity</option>
        <option value="Ethernet Cable Fabrication">Ethernet Cable Fabrication</option>
    </select>
    <label for="problem-description">Problem</label>
    <textarea name="problem-description" id="problem-description" cols="30" rows="10"></textarea>
    <input type="submit" value="Submit" class="confirm-btn">
    <input type="reset" value="Cancel" class="cancel-btn" id="cancel-btn">
</form>