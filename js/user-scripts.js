// This gives life to the checkboxes on the user settings page.

$(document).ready(function () {
    $("body").on("click", ".is-admin", function(e) {
        var userID = $(this).val();
        var isAdmin = $(this).prop("checked") == true ? 1 : 0;
        $.post("api/user/set-admin.php", {
            "user_id":userID,
            "is_admin":isAdmin
        }, function(data) {
            console.log(data);
        });
    });

    $("body").on("click", ".is-supervisor", function(e) {
        var userID = $(this).val();
        var isAdmin = $(this).prop("checked") == true ? 1 : 0;
        $.post("api/user/set-supervisor.php", {
            "user_id":userID,
            "is_supervisor":isAdmin
        }, function(data) {
            console.log(data);
        });
    });

    $("body").on("click", ".is-tech", function(e) {
        var userID = $(this).val();
        var isAdmin = $(this).prop("checked") == true ? 1 : 0;
        $.post("api/user/set-technician.php", {
            "user_id":userID,
            "is_technician":isAdmin
        }, function(data) {
            console.log(data);
        });
    });

    $("body").on("click", ".cantor-mode", function(e) {
        var userID = $(this).val();
        var cantorMode = $(this).prop("checked") == true ? 1 : 0;
        $.post("api/user/set-cantor.php", {
            "user_id":userID,
            "cantor_mode":cantorMode
        }, function(data) {
            window.location.reload();
            console.log(data);
        });
    });
});