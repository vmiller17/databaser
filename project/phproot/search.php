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
<head><title>Search</title></head>
<body>

<h1>Search</h1>

<form action="searchResult.php">
    
    <p> Barcode:
    <select name="barcode">
        <option selected>--All--</option>
        <?php foreach ($barcodes as $barcode) { ?>
            <option><?php print $barcode ?></option>
        <?php } ?>
    </select></p>    

    <p>Product:
    <select name="product">
        <option selected>--All--</option>
        <?php foreach ($cookieNames as $name) { ?>
            <option><?php print $name ?></option>
        <?php } ?>
    </select></p>

    <p>Location:
    <select name="location">
        <option selected>--All--</option>
        <?php foreach ($locations as $loc) { ?>
            <option><?php print $loc ?></option>
        <?php } ?>
    </select></p>       

    <p>Blocked: 
    <select name="blocked">
        <option selected>--All--</option>
        <option>Yes</option>
        <option>No</option>
    </select></p>

    <p>Date:
    <select name="date">
        <option selected>-</option>
        <?php foreach ($dates as $date) { ?>
            <option><?php print $date ?></option>
        <?php } ?>
    </select></p>

    <input type="time" size="10" value="00:00:00" name="startTime" >
    <input type="time" size="10" value="23:59:59" name="endTime" >
    <input type="submit" value="Search">
</form>

<form action="mainpage.php">
    <input type="submit" value="Back to main page">
</form>

</body>
</html>

