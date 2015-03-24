<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$db->openConnection();
	$barcode = $_SESSION['barcode'];
	$location = $_SESSION['location'];
	$cookieName = $_SESSION['product'];
	$blocked = $_SESSION['blocked'];
	$date = $_SESSION['date'];
	$startTime = $_SESSION['startTime'];
	$endTime = $_SESSION['endTime'];
	$pallets = $db->generalSearch($barcode, $location, $blocked, $date, $startTime, $endTime, $cookieName);
	$db->closeConnection();
?>



<html>
<head><title>Pallets</title><head>
<body><h1>Search results</h1>
<?php
print count($pallets);
print ' pallets found ';

if (count($pallets)>0) {
?>
<table>
	<tr>
		<td>Barcode</td>
		<td>Location</td>
		<td>Blocked</td>
		<td>Prod date</td>
		<td>Prod time</td>
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