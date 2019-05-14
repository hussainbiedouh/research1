<?php
//START SESSION
//session_start();
include('imported/PDCF.php');
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="../styles/general.css">
</head>
	<br><br><<br>
<H1> Personality Diagnoses CF </H1>
<body>
	
<?php

//READ URL PARAMETER
{if (isset($_GET['target'])) 
$target_user = $_GET['target'];
if (isset($_GET['movie'])) 
$target_movie = $_GET['movie'];
if (isset($_GET['count'])) 
$count = $_GET['count'];
}

//DATABASE CONNECT
{
include('..\databases\dbconnect.php');
mysql_select_db($netflixdb,$conn);
}
	
//TARGET USER DROPDOWN MENU
{
$query_Recordset1 = sprintf("SELECT name FROM customers");
$Recordset1 = mysql_query($query_Recordset1, $conn) or die(mysql_error());	
echo ("<select id='userselect' onChange='fun1();'> ");
while ($row = $row_Recordset1 = mysql_fetch_assoc($Recordset1)) {
$rows[] = $row;}
foreach($rows as $row)
{echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';}
echo("</select>");
echo ("</br>");	
}
	
//MOVIE DROPDOWN MENU
{
if (isset($_GET['target'])){
$query_Recordset2 = sprintf("SELECT title FROM movies");
$Recordset2 = mysql_query($query_Recordset2, $conn) or die(mysql_error());	
echo ("<select id='movieselect' onChange='fun2();'> ");
while ($row2 = $row_Recordset2 = mysql_fetch_assoc($Recordset2)) {
$rows2[] = $row2;}
foreach($rows2 as $row2)
{echo '<option value="'.$row2['title'].'">'.$row2['title'].'</option>';}
echo("</select>");
echo ("</br>");							
}}

//NUMBER OF SAMPLES
{
if (isset($_GET['movie'])){
$total_users_count = mysql_num_rows($Recordset1);
echo ("<select id='userscount' onChange='fun3();'> ");
for ($i = 1; $i <= $total_users_count; $i++) 
    echo '<option value="'.$i.'">'.$i.'</option>';
echo("</select>");
echo ("</br>");	
} 
}

//SHOW RUN BUTTON IF ALL PARAMETERS SATISFIED
{
if (isset($_GET['count']))
{
	if(!isset($_GET['run']))
	echo ("<a id='runbutton' onclick='fun4()'><button>RUN</button></a>");
	
}
	
}

//ALGORITHM LOGIC
{
	if (isset($_GET['run']))
	{
	if ($_GET['run']=='yes')
		{
		
		//GET ID OF SELECTED USER
		{
		$query_algo1 = sprintf("SELECT id FROM customers WHERE name ='".$target_user."'");
		$Recordset_algo1 = mysql_query($query_algo1, $conn) or die(mysql_error());
		$row_algo1 = mysql_fetch_assoc($Recordset_algo1);
		$user_id_algo=$row_algo1['id'];
		}
		
		//GET ID OF SELECTED MOVIE
		{
		$query_algo2 = sprintf("SELECT id FROM movies WHERE title ='".$target_movie."'");
		$Recordset_algo2 = mysql_query($query_algo2, $conn) or die(mysql_error());
		$row_algo2 = mysql_fetch_assoc($Recordset_algo2);
		$movie_id_algo=$row_algo2['id'];
		}
		
		//RUN ALGORITHM
		$db1 = mysql_select_db($netflixdb,$conn);

		$final_result = NBCF_expect($db1, $movie_id_algo, $user_id_algo, $count);
		echo($final_result);
		
		echo('<br>');
		echo ("<a id='resetbut' onclick='fun5()'><button>RESET</button></a>");
		}
	}
}
?>


	</body>
<!-- SCRIPT THAT ASSIGNS SELECTED TARGET USER TO BUTTON -->
<script>
	

function fun1() {
    var x = document.getElementById("userselect").value;
	//alert(x);
    var CuURL = 'http://localhost:8080/Master/Algos/pdcf.php?target=' +  x;
	window.location = CuURL;
}
function fun2() {
    var y = document.getElementById("movieselect").value;
	//alert(y);
    var CuURL2 = document.URL+'&movie=' +  y;
	window.location = CuURL2;
}
function fun3() {
	var z = document.getElementById("userscount").value;
	//alert(y);
    var CuURL3 = document.URL+'&count=' +  z;
	window.location = CuURL3 ;	
}
function fun4() {
	var CuURL4 = document.URL+'&run=yes';
	window.location = CuURL4;
		
	}

function fun5()
	{
		window.location = 'http://localhost:8080/Master/Algos/pdcf.php';
	}
	</script>
</html>