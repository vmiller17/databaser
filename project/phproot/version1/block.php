<?php
	require_once('database.inc.php');
	require_once('pallet.inc.php');

	session_start();
	$db = $_SESSION['db'];
  	$product = $_REQUEST['product'];
  	$date = $_REQUEST['date'];
  	$startTime = $_REQUEST['startTime'];
  	$endTime = $_REQUEST['endTime'];
	$db->openConnection();
	$ret = $db->blockPallets($product, $date, $startTime, $endTime);
	$db->closeConnection();
?>

<html>
<head><title>Blocked Pallets</title><head>
<body><h1>Number of blocked pallets</h1>
<?php
	print $ret[0];
	print ' new pallets blocked. All blocked pallets in intervall: ';

if (count($ret[1])>0) {
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
foreach ($ret[1] as $pallet ) {
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