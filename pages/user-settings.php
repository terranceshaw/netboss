<?php
include_once "api/objects/user.php";
$userInfo = json_decode(User::getUserInfo($_SESSION['id']), true);

?><div class="padded">
    <h3>User Settings</h3>
    <form action="" method="post" class="user-settings-form" style="max-width: 500px">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo $_SESSION['username'] ?>" title="Username cannot be modified." disabled>
        <label for="display-name">Display Name</label>
        <input type="text" name="display-name" id="display-name" value="<?php echo $userInfo['display_name'] ?>">
        <label for="email-address">E-Mail Address</label>
        <input type="email" name="email-address" id="email-address" value="<?php echo $userInfo['email_address'] ?>">
        <?php if ($_SESSION['is_admin']) { ?> <label for="cantor-mode"><input type="checkbox" name="cantor-mode" id="cantor-mode"> Cantor Mode™</label> <?php } ?>
    </form>
</div>