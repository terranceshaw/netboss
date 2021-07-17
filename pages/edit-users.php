<div class="padded">
    <h3>Edit Users</h3>

    <form action="" method="post" class="row gap">
        <div class="col">
            <label for="user-accounts">User Accounts</label>
            <select name="user-accounts" id="user-accounts">
                <?php
                    $users = DB::run("SELECT * FROM user_accounts")->fetchAll();
                    foreach ($users as $user) {
                        echo "<option value=\"" . $user['id'] . "\">" . $user['username'] . "</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col">
            <input type="submit" value="Save" class="confirm-btn">
            <input type="reset" value="Reset" class="cancel-btn">
        </div>
    </form>
</div>