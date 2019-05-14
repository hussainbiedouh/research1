
<html>
<head>
<link rel="stylesheet" type="text/css" href="../styles/general.css">
</head>
	<br><br><<br>
<H1> SVD CF </H1>
<body>
<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//DATABASE CONNECT
{
include('..\databases\dbconnect.php');
mysql_select_db($netflixdb,$conn);
}

$query_Recordset1 = sprintf("SELECT userid from ratings");
$query_limit_Recordset1 = sprintf($query_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $conn) or die(mysql_error());
echo $Recordset1['userid'];
error_reporting(E_ERROR | E_PARSE);	
while($row1 = mysql_fetch_assoc($Recordset1))
{
    $users[] = $row1;
}

$query_Recordset2 = sprintf("SELECT movieid from ratings");
$query_limit_Recordset2 = sprintf($query_Recordset2);
$Recordset2 = mysql_query($query_limit_Recordset2, $conn) or die(mysql_error());
while($row1 = mysql_fetch_assoc($Recordset2))
{
    $movies[] = $row1;
}

//calculate ratings avertage
$query_Recordset3 = sprintf("SELECT avg(rating) from ratings group by movieid");
$query_limit_Recordset3 = sprintf($query_Recordset3);
$Recordset3 = mysql_query($query_limit_Recordset3, $conn) or die(mysql_error());
while($row1 = mysql_fetch_assoc($Recordset3))
{
    $movies[] = $row1;
}
	
$movie_index_counter=0;
foreach ($users as $user)
	foreach ($movies as $movie)
	{{
		$movie_index_counter++;
		$query_Recordset4 = sprintf("SELECT rating from ratings where userid = '$user' and movieid = '$movie'");
		$query_limit_Recordset4 = sprintf($query_Recordset4);
		$Recordset4 = mysql_query($query_limit_Recordset4, $conn) or die(mysql_error());
		if (mysql_num_rows($Recordset4)==0) { $t_array[$user][$movie] = $movies[$movie_index_counter]; }
		else {$t_array[$user][$movie] = mysql_fetch_assoc($Recordset4);}
	}}
include('imported/svd.php');
//SVD($t_array);
echo (get_svd_val(2,3));
?>
</body>