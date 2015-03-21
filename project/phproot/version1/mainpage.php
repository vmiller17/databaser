<html>
<head><title>Main page</title><head>
<body><h1>Main page</h1>
	<p>
	What to do:
	<p>
	<form method=post action="searchPallet.php">		
		<input type=submit value="Search Pallet">
	</form>
	<form method=post action="blockPallet.php">		
		<input type=submit value="Block Pallet">
	</form>
	<form method=post action="simulate.php">		
		<input type=submit value="Simulate production">
	</form>
</body>
</html>


<!--
 			$first = true;
 			foreach ($movieNames as $name) {
 				if ($first) {
 					print "<option selected>";
 					$first = false;
 				} else {
 					print "<option>";
 				}
 				print $name;
 			}
-->