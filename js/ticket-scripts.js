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
                    window.location.reload();
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