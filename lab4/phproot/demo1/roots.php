<html>
<head><title>Double Square Root Results</title><head>
<body>

<?php 
	$number = $_REQUEST['number'];
	if (is_numeric($number)) {
		if ($number >= 0) {
			print "The double square root of $number is ";
			print sqrt(sqrt($number));
		} else {
			print "The number must be >= 0.";
		}
	} else {
		print "$number isn't a number.";
	}
?>
	
<p>
Try another:
<p>

<form method = "get" action = "roots.php">
	<input type = "text" name = "number">
	<input type = "submit" value = "Compute root">
</form>
</body>
</html>
