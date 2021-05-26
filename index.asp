<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/js/jquery.js"></script>
    <link rel="stylesheet" href="/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>#NETBOSS</title>
</head>
<body>

<%
    ' Dimension variables
    dim adoCon, rsGuestbook, strSQL
    set adoCon = Server.CreateObject("ADODB.Connection")
    adoCon.Open "Provider=Microsoft.Jet.OLEDB.4.0; Data Source=C:\inetpub\wwwroot\nemesys_db.accdb"
%>

</body>
</html>