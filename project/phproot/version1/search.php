<html>
<head><title>Search</title></head>
<body>

<h1 align="center">Search</h1>

<form method="post" action="palletInfo.php">
    Search based on barcode:
    <input type="text" size="20" name="barcode" >
    <input type="submit" value="Search">
</form>

<form method="post" action="searchProduct.php">
    Search based on product:
    <input type="text" size="20" name="product" >
    <input type="submit" value="Search">
</form>

<form method="post" action="searchTime.php">
    Search based on time intervall (date,start,end):
    <input type="text" size="20" name="date" >
    <input type="text" size="20" name="startTime" >
    <input type="text" size="20" name="endTime" >
    <input type="submit" value="Search">
</form>

</body>
</html>