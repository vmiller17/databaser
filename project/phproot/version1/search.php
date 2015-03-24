<?php
    require_once('database.inc.php');    
    session_start();
    $db = $_SESSION['db'];
    $db->openConnection();
    $cookieNames = $db->getProducts();
    $barcodes = $db->getAllBarcodes();
    $dates = $db->getAllProdDates();
    $db->closeConnection();
?>

<html>
<head><title>Search</title></head>
<body>

<h1 align="center">Search</h1>

<form action="palletInfo.php">
    Search based on barcode:
        <select name="barcode">
            <?php
            foreach ($barcodes as $barcode) {
                ?>
                <option selected><?php print $barcode ?></option>
                <?php
            }
            ?>
        </select>      
        <input type=submit value="Select barcode">
</form>

<form action="searchProduct.php">
    Search based on product:
        <select name="product">
            <?php
            foreach ($cookieNames as $name) {
                ?>
                <option selected><?php print $name ?></option>
                <?php
            }
            ?>
        </select>      
        <input type=submit value="Select Cookie Name">
</form>

<form method="post" action="searchTime.php">
    Search based on time intervall (date,start,end):
    <select name="date">
            <?php
            foreach ($dates as $date) {
                ?>
                <option selected><?php print $date ?></option>
                <?php
            }
            ?>
        </select>      
    <input type="time" size="10" name="startTime" >
    <input type="time" size="10" name="endTime" >
    <input type="submit" value="Search">
</form>

<form method="post" action="searchBlocked.php">
    Search all blocked.
    <input type="submit" value="Search">
</form>

</body>
</html>

