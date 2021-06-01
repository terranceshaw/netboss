<?php
include_once "api/core/db.php"; // Include the DB config file.
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/js/jquery.js"></script>
    <link rel="stylesheet" href="/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>#netboss</title>
</head>
<body>

<p>Testing for great justice.</p>

<?php
    echo "Document root: " . $_SERVER['DOCUMENT_ROOT'];
    // Test run of the DB connection.
    $results = DB::run("SELECT * FROM trouble_tickets")->fetchAll();
    print_r($results);
?>

</body>
</html>