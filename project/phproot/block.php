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
<head><title>Block Pallets</title></head>
<body>

<h1>Block Pallets</h1>

<form method="post" action="blockResult.php">
    <p>Product:
    <select name="product">
        <?php foreach ($cookieNames as $name) { ?>
            <option><?php print $name ?></option>
        <?php } ?>
    </select></p>


    <p>Date:
    <select name="date">
        <?php foreach ($dates as $date) { ?>
            <option><?php print $date ?></option>
        <?php } ?>
    </select></p>
    <p>

    Start time:
    <input type="time" size="10" value="00:00:00" name="startTime" >
    <p>
    End time:
    <input type="time" size="10" value="23:59:59" name="endTime" >
    <p>
    <input type="submit" value="Block!">
    <p>
</form>

<form action="mainpage.php">
    <input type="submit" value="Back to main page">
</form>

</body>
</html>
