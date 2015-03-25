<?php
    require_once('database.inc.php');    
    session_start();
    $db = $_SESSION['db'];
    $db->openConnection();
    $cookieNames = $db->getProducts();
    $barcodes = $db->getAllBarcodes();
    $dates = $db->getAllProdDates();
    $locations = $db->getAllLocations();
    $db->closeConnection();
?>

<html>
<head><title>Login</title></head>
<body>

<h1 align="center">Block Pallets</h1>

<form method="post" action="block.php">
    <p>Product:
    <select name="product">
        <option selected>--All--</option>
        <?php foreach ($cookieNames as $name) { ?>
            <option><?php print $name ?></option>
        <?php } ?>
    </select></p>

    <p>Date:
    <select name="date">
        <option selected>-</option>
        <?php foreach ($dates as $date) { ?>
            <option><?php print $date ?></option>
        <?php } ?>
    </select></p>
    <p>

    Start time:
    <input type="time" size="10" name="startTime" >
    <p>
    End time:
    <input type="time" size="10" name="endTime" >
    <p>
    <input type="submit" value="Block!">
    <p>
</form>

<form action="mainpage.php">
    <input type="submit" value="Back to main page">
</form>

</body>
</html>