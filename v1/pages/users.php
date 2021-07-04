<?php
include_once "./api/objects/user.php";
if ($_SESSION['is_admin'] == 1) {   // Validate dat admin status
?>

<div class="padded">
    <h3>Users</h3>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Administrator</th>
                <th>Helpdesk Supervisor</th>
                <th>Helpdesk Tech</th>
                <th>Cantor Mode™</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $users = json_decode(User::getUsers(), true);
                foreach ($users as $user) {
                    if (!strpos($user['username'], "svc")) {
                        // Disregard service accounts, because who cares.
                        $adminCheck = $user['is_admin'] == 1 ? " checked" : "";
                        $techCheck = $user['hd_technician'] == 1 ? " checked" : "";
                        $supCheck = $user['hd_supervisor'] == 1 ? " checked" : "";
                        $cantorMode = $user['cantor_mode'] == 1 ? " checked" : "";
                        echo "<tr>\n";
                        echo "<td>" . $user['username'] . "</td>\n";
                        echo "<td><input type=\"checkbox\" name=\"user_id\" class=\"is-admin\" value=\"" . $user['id'] . "\"$adminCheck></td>\n";
                        echo "<td><input type=\"checkbox\" name=\"user_id\" class=\"is-supervisor\" value=\"" . $user['id'] . "\"$supCheck></td>\n";
                        echo "<td><input type=\"checkbox\" name=\"user_id\" class=\"is-tech\" value=\"" . $user['id'] . "\"$techCheck></td>\n";
                        echo "<td><input type=\"checkbox\" name=\"user_id\" class=\"cantor-mode\" value=\"" . $user['id'] . "\"$cantorMode></td>\n";
                        echo "</tr>\n";
                    }
                }
            ?>
        </tbody>
    </table>
</div>

<script src="js/user-scripts.js"></script>

<?php } else { ?>

<div class="padded">

    <h3>Unauthorized</h3>

    <p>You lack the proper accesses required to view this page.</p>

    <p>Please contact a system administrator if you believe this to be in error.</p>

</div>

<?php } ?>