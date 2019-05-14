<?php
{
error_reporting(E_ALL ^ E_DEPRECATED);
include('imported\treeCBRC.php');
include('..\..\databases\dbconnect.php');
mysql_select_db($netflixdb,$conn);
}
error_reporting(E_ERROR | E_PARSE);
$query_Recordset1 = sprintf("SELECT * FROM `customers` WHERE 1 LIMIT 1000");
$query_limit_Recordset1 = sprintf($query_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $conn) or die(mysql_error());
while($row1 = mysql_fetch_assoc($Recordset1))

{
    $users[] = $row1;
}

foreach ($users as $user )
{
	
$query_Recordset2 = sprintf("SELECT movieid 
FROM `ratings` 
WHERE 'userid'= '$user' and rating > 3
");
$query_limit_Recordset2 = sprintf($query_Recordset2);
$Recordset2 = mysql_query($query_limit_Recordset2, $conn) or die(mysql_error());
while($row2 = mysql_fetch_assoc($Recordset2))

{
    $movs[] = $row2;
}
$moviesstringX = implode (“ or “,$movs);
$query_RecordsetX = sprintf("SELECT movieid,genre,rate 
FROM `movies` 
WHERE movieid = moviesstringX and rating > 3
");
$query_limit_RecordsetX = sprintf($query_RecordsetX);
$RecordsetX = mysql_query($query_limit_Recordset2, $conn) or die(mysql_error());


	
$query_Recordset3 = sprintf("SELECT  movieid
FROM `ratings` 
WHERE userid = '$user' and rating < 3
");
$query_limit_Recordset3 = sprintf($query_Recordset3);
$Recordset3 = mysql_query($query_limit_Recordset3, $conn) or die(mysql_error());
while($row3 = mysql_fetch_assoc($Recordset3))
{
    $movs[] = $row3;
}
$moviesstringY = implode (“ or “,$movs);
$query_RecordsetY = sprintf("SELECT movieid,genre,rate 
FROM `movies` 
WHERE movieid = moviesstringX and rating > 3
");
$query_limit_RecordsetY = sprintf($query_RecordsetY);
$RecordsetY = mysql_query($query_limit_Recordset2, $conn) or die(mysql_error());

	
setclass('like',$RecordsetX);
setclass('dislike',$RecordsetY);
$TrainedEngine = Train();
}

$results = GetRecs(27);

	
	echo ($results);

?>