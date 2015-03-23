<?php
	require_once('database.inc.php');
	require_once('pallet.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
  	$date = $_REQUEST['date'];
  	$startTime = $_REQUEST['startTime'];
  	$endTime = $_REQUEST['endTime'];
	$db->openConnection();
	$pallets = $db->getPalletsInterval($date, $startTime, $endTime);
	$db->closeConnection();
?>



<html>
<head><title>Pallets</title><head>
<body><h1>Pallets within <?php print $startTime ?> to  <?php print $endTime ?> on the <?php print $date ?></h1>
<?php
print count($pallets);
print ' pallets found ';
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

</body>
</html>