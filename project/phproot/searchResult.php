<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$db->openConnection();
	$barcode = $_REQUEST['barcode'];
	$location = $_REQUEST['location'];
	$cookieName = $_REQUEST['product'];
	$blocked = $_REQUEST['blocked'];
	$date = $_REQUEST['date'];
	$startTime = $_REQUEST['startTime'];
	$endTime = $_REQUEST['endTime'];
	$pallets = $db->generalSearch($barcode, $location, $blocked, $date, $startTime, $endTime, $cookieName);
	$db->closeConnection();
	
?>



<html>
<head><title>Pallets</title><head>
<body><h1>Search results</h1>
<?php
print count($pallets);
if (count($pallets) == 1) {
	print ' pallet found ';
} else {
	print ' pallets found ';
}

if (count($pallets)>0) {
?>
<table>
	<tr>
		<td>Barcode</td>
		<td>Location</td>
		<td>Blocked</td>
		<td>Production date</td>
		<td>Production time</td>
		<td>Cookie Name</td>
	</tr>
<?php
foreach ($pallets as $pallet ) {
	?>
	<tr>
        <td><?php print $pallet->getBarcode() ?></td>
        <td><?php print $pallet->getLocation() ?></td>
        <td><?php print $pallet->getBlocked() ?></td>
        <td><?php print $pallet->getProducedDate() ?></td>
        <td><?php print $pallet->getProducedTime() ?></td>
        <td><?php print $pallet->getCookieName() ?></td>
      </tr>
	<?php
}
?>
</table>

<?php 
}
?>

</body>
</html>